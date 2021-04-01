<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\BookingRequest;
use App\Models\Reservation;
use App\Models\Room;
use App\Events\BookingRequestUpdated;
use App\Models\Settings;
use DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
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
        'date_format:Y-m-d\TH:i'
      ],
      'reservations.*.end_time' => [
        'required',
        'date',
        'date_format:Y-m-d\TH:i',
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

        $referenceFolder = null;
        if (array_key_exists('files', $data)) {
            // save the uploaded files
            $referenceFolder = "{$room->id}_".strtotime($reservation['start_time']).'_reference';

            foreach($data['files'] as $file) {
                $name = $file->getClientOriginalName();
                Storage::disk('public')->putFileAs($referenceFolder. '/', $file, $name);
            }
        }
        DB::beginTransaction();

        // store booking in db
        $booking = BookingRequest::create([
            'user_id' => $request->user()->id,
            'start_time' => $reservation['start_time'],
            'end_time' => $reservation['end_time'],
            'status' => BookingRequest::PENDING,
            'event' => $data['event'],
            'onsite_contact' => $data['onsite_contact'] ?? [],
            'notes' => $data['notes'] ?? '',
            'reference' => (array_key_exists('files', $data) && count($data['files']) > 0) ?
                ["path" => $referenceFolder] : [],
        ]);


        foreach ($data['reservations'] as $reservation) {
            Reservation::create([
                'room_id' => $room->id,
                'booking_request_id' => $booking->id,
                'start_time' => $reservation['start_time'],
                'end_time' => $reservation['end_time'],
            ]);
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
            'booking' => $booking->load('requester', 'reservations', $this->reservationRoom),
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

        $reservation = $booking->reservations->first();

        $update = collect($request->validated())->except(['files']);
        $booking->fill($update->toArray());
        $booking->status = BookingRequest::PENDING;
        $booking->save();

        if($booking->wasChanged()) {
            $log = '[' . date(self::DATE_FORMAT). '] - Updated booking request location and/or date';
            BookingRequestUpdated::dispatch($booking, $log);
        }

        if ($request->file()) {
            if(!array_key_exists('path', $booking->reference)) {
              $referenceFolder = "{$reservation->room_id}_".strtotime($reservation->start_time).'_reference';
              $booking->fill(['reference' => ['path' => $referenceFolder]])->save();
            }
            // save the uploaded files
            foreach($request->allFiles()['files'] as $file) {
                $name = $file->getClientOriginalName();
                Storage::disk('public')->putFileAs($booking->reference['path'] . '/', $file, $name);
            }
            $log = '[' . date(self::DATE_FORMAT). '] - Updated booking request reference files';
            BookingRequestUpdated::dispatch($booking, $log);
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
        Reservation::where('booking_request_id', $booking->id)->delete();
        $booking->delete();

        return redirect()->back();
    }

    public function downloadReferenceFiles($folder)
    {
        $path = Storage::disk('public')->path($folder);

        $zip = new ZipArchive;

        $fileName = $folder . '.zip';

        $zip->open(Storage::disk('public')->path($fileName), ZipArchive::CREATE);
        $files = File::files($path);

        foreach ($files as $file) {
            $relativeNameInZipFile = basename($file);
            $zip->addFile($file, $relativeNameInZipFile);
        }
        $zip->close();

        return response()->download(Storage::disk('public')->path($fileName))->deleteFileAfterSend(true);

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
                    $user =  $request->user();
                    $room = Room::query()->findOrFail($request->room_id);
                    $room->verifyDatesAreWithinRoomRestrictionsValidation($value['start_time'], $fail, $user);//
                    $room->verifyDatetimesAreWithinAvailabilitiesValidation($value['start_time'], $value['end_time'], $fail);//
                    $room->verifyRoomIsFreeValidation($value['start_time'], $value['end_time'], $fail);
                    if (!$request->user()->canMakeAnotherBookingRequest($value['start_time'])) {
                        $fail('You cannot have more than ' .
                            $user->getUserNumberOfBookingRequestPerPeriod() .
                            ' booking(s) in the next ' .
                            $user->getUserNumberOfDaysPerPeriod() .
                            ' days.');
                    }
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
            'date_range_start' => ['string'],
            'date_range_end' => ['string'],
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
            $query->where('created_at', '<', $request->date_range_start);
        }

        if($request->date_range_end){
            $query->where('created_at', '>', $request->date_range_end);
        }

        if($request->data_reviewers){
            $uids = collect($request->data_reviewers)->pluck('text');
            $query->whereIn('id', $uids);
        }

        return response()->json(new BookingCollection($query->get()));
    }


}
