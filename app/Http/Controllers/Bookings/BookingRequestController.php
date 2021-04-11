<?php

namespace App\Http\Controllers\Bookings;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\BookingRequest;
use App\Models\Reservation;
use App\Models\Room;
use App\Events\BookingRequestUpdated;
use App\Models\Settings;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\ResponseFactory;
use ZipArchive;
use File;
use App\Http\Resources\BookingCollection;


class BookingRequestController extends Controller
{

  /**
   * @var string sole purpose is to silence SonorCloud
   */
  private string $reservationRoom = 'reservations.room';

  private const RESERVATIONS_SESSION_KEY = "create_booking_form";

  private const DATE_FORMAT = "F j, Y, g:i a";

  private const ROOM_PAGINATOR_AMOUNT = 10;

  /**
   * Display a listing of the resource.
   *
   * @param Request $request
   * @return Response|\Inertia\Response|ResponseFactory
   */
    public function index(Request $request)
    {
        return inertia('Admin/BookingRequests/Index', [
            'booking_requests' => BookingRequest::with('requester', 'reservations', $this->reservationRoom)->get(),
            'rooms' => Room::hideUserRestrictions($request->user())->with('availabilities', 'blackouts')->get(),
            'paginator' => Room::hideUserRestrictions($request->user())->with('availabilities', 'blackouts')->paginate(self::ROOM_PAGINATOR_AMOUNT)
        ]);
    }

  public function createInit(Request $request)
  {
    $data = $request->validate([
      'room_id' => ['integer', 'exists:rooms,id'],
      'reservations' => ['required'],
      'reservations.*.start_time' => [
        'required',
        'date',
      ],
      'reservations.*.end_time' => [
        'required',
        'date',
      ],
      'reservations.*.duration' => [
        'required',
        'integer',
        'min:30',
      ],
    ]);

    $this->reservationValidate($request);

    Session::remove(self::RESERVATIONS_SESSION_KEY);
    Session::push(self::RESERVATIONS_SESSION_KEY, $data);

    return redirect()->route('bookings.create');
  }

    /**
     * Show the form for creating a new resource.
     *
     * @return RedirectResponse|\Inertia\Response
     */
    public function create()
    {
      if (!Session::has(self::RESERVATIONS_SESSION_KEY)) {
        return redirect()->route('bookings.search');
      } else {
        $data = Session::get(self::RESERVATIONS_SESSION_KEY)[0];

        return inertia('Requestee/BookingForm', [
          // example of the expected reservations format
          'room' => Room::findOrFail($data['room_id']),
          'reservations' => $data['reservations'],
          'bookings_general_information' => Settings::select('data')->where('slug', '=', 'general_information')->first(),
          'bookings_event_description' => Settings::select('data')->where('slug', '=', 'event_description')->first()
        ]);
      }
    }

  /**
   * Store a newly created resource in storage.
   *
   * @param CreateBookingRequest $request
   * @return RedirectResponse
   * @throws ValidationException
   */
    public function store(CreateBookingRequest $request)
    {
        $data = $request->validated();

        $this->reservationValidate($request);

        $reservation = $data['reservations'][0];
        $room = Room::findOrFail($data['room_id']);

        DB::beginTransaction();

        $booking = BookingRequest::create([
            'user_id' => $request->user()->id,
            'start_time' => $reservation['start_time'],
            'end_time' => $reservation['end_time'],
            'status' => BookingRequest::PENDING,
            'event' => $data['event'],
            'onsite_contact' => $data['onsite_contact'] ?? [],
            'notes' => $data['notes'] ?? '',
        ]);

        foreach ($data['reservations'] as $reservation) {
            Reservation::create([
                'room_id' => $room->id,
                'booking_request_id' => $booking->id,
                'start_time' => $reservation['start_time'],
                'end_time' => $reservation['end_time'],
            ]);
        }

        if ($request->hasFile('files')) {
            $files = collect();

            foreach ($request->file('files') as $file) {
                $files->add([
                    'name' => $file->getClientOriginalName(),
                    'path' => $file->store("bookings/$booking->referenceFolderName", 'public'),
                ]);
            }

            $booking->reference = $files;
            $booking->save();
        }

        DB::commit();

        $log = '[' . date(self::DATE_FORMAT) . '] - Created booking request';
        BookingRequestUpdated::dispatch($booking, $log);

        return redirect()->route('bookings.index')->with('flash', ['banner' => 'Your Booking Request was submitted']);
    }

    public function show(BookingRequest $booking)
    {
        $booking->loadMissing('requester', 'reservations', 'reservations.room');
        return inertia('Requestee/ViewBooking', [
            'booking' => new BookingResource($booking)
        ]);
    }

    /**
   * Show the form for editing the specified resource.
   *
   * @param BookingRequest $booking
   * @return RedirectResponse|Response|\Inertia\Response|ResponseFactory
   */
    public function edit(BookingRequest $booking)
    {
        if ($booking->status == BookingRequest::REVIEW || $booking->status == BookingRequest::APPROVED ) {
            return redirect()->route('bookings.view', ['booking' => $booking]);
        }

        return inertia('Requestee/EditBookingForm', [
            'booking' => new BookingResource($booking->load('requester', 'reservations', $this->reservationRoom)),
            'bookings_general_information' => Settings::select('data')->where('slug', '=', 'general_information')->first(),
            'bookings_event_description' => Settings::select('data')->where('slug', '=', 'event_description')->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateBookingRequest  $request
     * @param BookingRequest $booking
     * @return RedirectResponse
     */
    public function update(UpdateBookingRequest $request, BookingRequest $booking)
    {

        //If comments don't call this
        if ($booking->status == BookingRequest::REVIEW || $booking->status == BookingRequest::APPROVED ) {
            return redirect()->route('bookings.view', ['booking' => $booking]);
        }

        $update = collect($request->validated())->except(['files']);
        $booking->fill($update->toArray());
        $booking->status = BookingRequest::PENDING;
        $booking->save();

        if($booking->wasChanged()) {
            $log = '[' . date(self::DATE_FORMAT). '] - Updated booking request location and/or date';
            BookingRequestUpdated::dispatch($booking, $log);
        }

        if ($request->hasFile('files')) {
            $files = collect($booking->reference);

            foreach ($request->file('files') as $file) {
                $files->add([
                    'name' => $file->getClientOriginalName(),
                    'path' => $file->store("bookings/$booking->referenceFolderName", 'public'),
                ]);
            }
            $booking->fill(['reference' => $files])->save();
        }

        return redirect(route('bookings.index'))
            ->with('flash', ['banner' => 'Your Booking Request was updated!']);
    }

  /**
   * Remove the specified resource from storage.
   *
   * @param BookingRequest $booking
   * @return RedirectResponse
   * @throws Exception
   */
    public function destroy(BookingRequest $booking)
    {
        $reservation = $booking->reservations->first();
        $path = "bookings/{$reservation->room_id}_".strtotime($reservation->start_time).'_reference';
        Storage::disk('public')->deleteDirectory($path);

        Reservation::where('booking_request_id', $booking->id)->delete();
        $booking->delete();

        return redirect()->back();
    }

    public function download(BookingRequest $booking)
    {
        $zip = new ZipArchive;

        $fileName = "Booking#{$booking->id} " . now()->toDateTimeString() . '.zip';

        if($zip->open(Storage::path($fileName), ZipArchive::CREATE) === true) {
            foreach ($booking->reference as $file){
                $name = $file['name'];
                $path = Storage::disk('public')->path($file['path']);
                $zip->addFile($path, $name);
            }
            $zip->close();
        }

        return response()->download(Storage::path($fileName))->deleteFileAfterSend();

    }

    public function list()
    {
        return inertia('Requestee/BookingsList', [
            'bookings' => BookingRequest::with('requester', $this->reservationRoom)
                ->where('user_id', auth()->user()->id)
                ->get(),
        ]);
    }

    private function reservationValidate(Request $request)
    {
        $request->validate(array(
            'reservations.*' => ['array', 'size:3',
                function ($attribute, $value, $fail) use ($request) {
                    unset($attribute);
                    $user =  $request->user();
                    $room = Room::query()->findOrFail($request->room_id);
                    $room->minimumReservationTime($value['start_time'], $value['end_time'], $fail);
                    if (!$user->hasPermissionTo('bookings.restrictions.override')) {
                        $room->verifyDatesAreWithinRoomRestrictionsValidation($value['start_time'], $fail, $user);//
                        if (!$request->user()->canMakeAnotherBookingRequest($value['start_time'])) {
                            $fail('You cannot have more than ' .
                                $user->getUserNumberOfBookingRequestPerPeriod() .
                                ' booking(s) in the next ' .
                                $user->getUserNumberOfDaysPerPeriod() .
                                ' days.');
                        }
                    }
                    $room->verifyDatetimesAreWithinAvailabilitiesValidation($value['start_time'], $value['end_time'], $fail);//
                    $room->verifyRoomIsNotBlackedOutValidation($value['start_time'], $value['end_time'], $fail);//
                    $room->verifyRoomIsFreeValidation($value['start_time'], $value['end_time'], $fail);
                }
            ]
        ));
    }


    /**
     * Filter booking requests by given json payload
     *
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function filter(Request $request)
    {
        // None of the request fields are mandatory, only
        // filter the ones provided from request
        $request->validate([
            'status_list.*' => ['boolean'],
            'date_range_start' => ['date'],
            'date_range_end' => ['date'],
            'data_reviewers' => ['array']
        ]);

        // Filter by status, assignee, dates if present in query
        $query = BookingRequest::with('requester');

        if($request->status_list){
            $activeStatuses = [];
            foreach ($request->status_list as $key => $value) {
                if ($value) {
                    array_push($activeStatuses, $key);
                }
            }
            $query->whereIn('status', $activeStatuses);
        }


        if($request->date_range_start){
            $query->where('created_at', '>', $request->date_range_start);
        }

        if($request->date_range_end){
            $query->where('created_at', '<', $request->date_range_end);
        }

        if($request->data_reviewers){
            $uids = collect($request->data_reviewers)->pluck('text');
            $query->whereIn('id', $uids);
        }

        return response()->json(new BookingCollection($query->get()));
    }

    public function removeFile(Request $request, BookingRequest $booking)
    {
        $request->validate([
            'filenames' => ['required'],
        ]);

        $toDelete = collect($request->filenames);

        $booking->reference = collect($booking->reference)->filter(function ($file) use ($toDelete) {
            $exists = $toDelete->contains($file['name']);

            if ($exists) {
                Storage::disk('public')->delete($file['path']);
            }

            return !$exists;
        });

        $booking->save();

        return response()->json(new BookingResource($booking));
    }

    /**
     * Filter booking requests by given json payload for a specific user
     *
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function filterUserBookings(Request $request)
    {
        // None of the request fields are mandatory, only
        // filter the ones provided from request
        $request->validate([
            'dateCheck' => ['date','nullable'],
            'selectStatus' => ['string','nullable', Rule::in(BookingRequest::STATUS_TYPES)],
        ]);

        $query = BookingRequest::with('requester', $this->reservationRoom)
            ->where('user_id', auth()->user()->id);

        if ($request->selectStatus){
            $query = $query->where('status', $request->selectStatus);
        }

        if ($request->dateCheck) {
            $query = $query->whereHas('reservations', function($q) use ($request) {
                $date = Carbon::parse($request->dateCheck);
                $q->whereDate('start_time', $date)->orWhereDate('end_time', $date);
            });
        }

        return response()->json($query->get());
    }

}
