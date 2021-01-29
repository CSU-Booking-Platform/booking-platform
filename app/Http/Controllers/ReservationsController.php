<?php

namespace App\Http\Controllers;

use App\Events\BookingRequestUpdated;
use App\Models\BookingRequest;
use App\Models\Reservation;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ReservationsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
//  public function index()
//  {
//    //
//  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
//  public function create()
//  {
//    //
//  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $this->reservationValidate($request, 'store');

//    //lazy for now
    $booking = BookingRequest::factory()->create([
      'user_id' => $request->user()->id,
      'status' => "review",
    ]);

    foreach ($request->reservations as $pair){
      $reservation = new Reservation();
      $reservation->room_id = $request->room_id;
      $reservation->booking_request_id = $booking->id;
      $reservation->start_time = $pair['start_time'];
      $reservation->end_time = $pair['end_time'];
      $reservation->save();
    }

    return back();
  }

  /**
   * Display the specified resource.
   *
   * @param Reservation $reservation
   * @return \Illuminate\Http\Response
   */
//  public function show(Reservation $reservation)
//  {
//    //
//  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param Reservation $reservation
   * @return \Illuminate\Http\Response
   */
//  public function edit(Reservation $reservation)
//  {
//    //
//  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param Reservation $reservation
   * @return RedirectResponse
   */
  public function update(Request $request, Reservation $reservation)
  {
    $this->reservationValidate($request, 'update', $reservation);

    $date_format = "F j, Y, g:i a";

    //for now only one
//    $reservation = $booking->reservations()->first();
    $reservation->room_id = $request->room_id;
    $reservation->start_time = $request->reservations[0]['start_time'];
    $reservation->end_time = $request->reservations[0]['end_time'];
    $reservation->save();

    $booking = $reservation->bookingRequest()->first();

    if($reservation->wasChanged())
    {
      $log = '[' . date($date_format) . ']' . ' - Updated booking request location and/or date';
      BookingRequestUpdated::dispatch($booking, $log);
    }

    return back();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param Reservation $reservation
   * @return RedirectResponse
   * @throws \Exception
   */
  public function destroy(Reservation $reservation): RedirectResponse
  {
    $booking = $reservation->bookingRequest()->first();
    $reservation->delete();
    if (!$booking->reservations()->exists()) {
      $booking->delete();
    }
    return back();
  }

  /**
   * Remove the specified resource from storage.
   * @param Request $request
   * @param Room $room
   * @return \Illuminate\Http\Response
   */
  public function roomReservation(Request $request, Room $room)
  {
    $date = $request->get('date');

    if (empty($date)) {
      return response()->json([]);
    }

    $roomReservations = Reservation::query()->where('room_id', '=', $room->id)
      ->whereDate('start_time', '=', Carbon::parse($date)->toDateString())
      ->whereHas('bookingRequest', function (Builder $subQuery) {
        $subQuery->where('status', 'like', '%approved%');
      })
      ->get();

    return response()->json($roomReservations);
  }

  private function reservationValidate(Request $request, $function, $reservation = null){
    $request->validateWithBag($function.'ReservationsRequest', array(
      'room_id' => ['required', 'integer', 'exists:rooms,id'],
      'reservations' => ['required'],
      'reservations.*.start_time' => ['required', 'date'],
      'reservations.*.end_time' => ['required', 'date'],
    ));

    $request->validateWithBag($function.'ReservationsRequest', array(
      'reservations.*' => ['array', 'size:2',
        function ($attribute, $value, $fail) use ($request, $reservation){
          $room = Room::query()->findOrFail($request->room_id);
          $room->verifyDatesAreWithinRoomRestrictionsValidation($value['start_time'], $fail, $attribute);
          $room->verifyDatetimesAreWithinAvailabilitiesValidation($value['start_time'], $value['end_time'], $fail, $attribute);
          $room->verifyRoomIsFreeValidation($value['start_time'], $value['end_time'], $fail, $attribute, $reservation);

          if (!$request->user()->canMakeAnotherBookingRequest($value['start_time'])) {
            $fail($attribute . ' Cannot make more than ' .
              $request->user()->getUserNumberOfBookingRequestPerPeriod() .
              ' bookings in the next ' .
              $request->user()->getUserNumberOfDaysPerPeriod() .
              ' days.');
          }
        }
      ]
    ));
  }
}
