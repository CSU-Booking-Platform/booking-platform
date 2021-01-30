<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\BookingRequest;
use App\Models\Reservation;
use App\Models\Room;
use App\Events\BookingRequestUpdated;
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

class BookingRequestController extends Controller
{

  /**
   * @var string sole purpose is to silence SonorCloud
   */
  private string $reservationRoom = 'reservations.room';


  private const RESERVATIONS_SESSION_KEY = "create_booking_form";

  /**
   * Display a listing of the resource.
   *
   * @param Request $request
   * @return Response|\Inertia\Response|ResponseFactory
   */
    public function index(Request $request)
    {
        return inertia('Admin/BookingRequests/Index', [
            'booking_requests' => BookingRequest::with('user', 'reservations', $this->reservationRoom)->get(),
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
    ]);

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
        return redirect()->route('bookings.index');
      } else {
        $data = Session::get(self::RESERVATIONS_SESSION_KEY)[0];

        return inertia('Requestee/BookingForm', [
          // example of the expected reservations format
          'room' => Room::findOrFail($data['room_id']),
          'reservations' => $data['reservations'],
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
        $room = Room::findOrFail($data['room_id']);
        $reservation = $data['reservations'][0];

        // validate room still available at given times
        foreach ($data['reservations'] as $value) {
          $room->verifyDatesAreWithinRoomRestrictions($value['start_time']);
          $room->verifyDatetimesAreWithinAvailabilities($value['start_time'], $value['end_time']);
          $room->verifyRoomIsFree($value['start_time'], $value['end_time']);
          if (!$request->user()->canMakeAnotherBookingRequest($value['start_time'])) {
            throw ValidationException::withMessages([
              'booking_request_exceeded' => 'Cannot make more than ' .
                $request->user()->getUserNumberOfBookingRequestPerPeriod() .
                ' bookings in the next ' .
                $request->user()->getUserNumberOfDaysPerPeriod() .
                ' days.'
            ]);
          }
        }

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
            'status' => 'review',
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

        $log = '[' . date("F j, Y, g:i a") . '] - Created booking request';
        BookingRequestUpdated::dispatch($booking, $log);

        return redirect()->route('bookings.list')->with('flash', ['banner' => 'Your Booking Request was submitted']);
    }

  /**
   * Show the form for editing the specified resource.
   *
   * @param BookingRequest $booking
   * @return Response|\Inertia\Response|ResponseFactory
   */
    public function edit(BookingRequest $booking)
    {
        return inertia('Requestee/EditBookingForm', [
            'booking' => $booking->load('user', 'reservations', $this->reservationRoom),
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
        $reservation = $booking->reservations->first();

        $update = collect($request->validated())->except(['files']);
        $booking->fill($update->toArray())->save();

        if($booking->wasChanged()) {
            $log = '[' . date("F j, Y, g:i a"). '] - Updated booking request location and/or date';
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
        }

        return redirect(route('bookings.list'))
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

        return redirect()->route('bookings.index');
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
            'bookings' => BookingRequest::with('user', $this->reservationRoom)->get(),

        ]);
    }



}
