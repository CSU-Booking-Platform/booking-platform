<?php

namespace App\Http\Controllers;

use App\Events\BookingRequestUpdated;
use App\Models\BookingRequest;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    $this->reservationValidate($request);

//    $room = Room::query()->findOrFail($request->room_id);
//    $room->verifyDatetimesAreWithinAvailabilities($request->get('start_time'), $request->get('end_time'));
//    $room->verifyDatesAreWithinRoomRestrictions($request->get('start_time'), $request->get('end_time'));
//    //lazy for now
    $booking = BookingRequest::create([
      'user_id' => $request->user()->id,
      'status' => "review",
      'reference' => ["path" => '']//not sure what to do here tbh
    ]);

    foreach ($request->recurrences as $pair){
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
    $this->reservationValidate($request);

    $date_format = "F j, Y, g:i a";

    $booking = $reservation->bookingRequest()->first();
    $booking->fill($request->except(['reference']))->save();

    if($booking->wasChanged())
    {
      $log = '[' . date($date_format) . ']' . ' - Updated booking request location and/or date';
      BookingRequestUpdated::dispatch($booking, $log);
    }

    //for now only one
//    $reservation = $booking->reservations()->first();
    $reservation->room_id = $request->room_id;
    $reservation->start_time = $request->recurrences[0]['start_time'];
    $reservation->end_time = $request->recurrences[0]['end_time'];
    $reservation->save();

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

  private function reservationValidate(Request $request){
    $request->validateWithBag('createReservationsRequest', array(
      'room_id' => ['required', 'integer', 'exists:rooms,id'],
      'recurrences' => ['required'],
      'recurrences.*.start_time' => ['required', 'date'],
      'recurrences.*.end_time' => ['required', 'date'],
    ));

    $request->validateWithBag('createReservationsRequest', array(
      'recurrences.*' => ['array', 'size:2',
        function ($attribute, $value, $fail) use ($request){
          $room = Room::query()->findOrFail($request->room_id);
          $room->verifyDatesAreWithinRoomRestrictionsValidation($value['start_time'], $fail, $attribute);
          $room->verifyDatetimesAreWithinAvailabilitiesValidation($value['start_time'], $value['end_time'], $fail, $attribute);

        }
      ]
    ));
  }
}
