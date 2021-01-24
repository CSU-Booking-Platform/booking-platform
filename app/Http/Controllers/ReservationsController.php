<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Reservation $reservation
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Reservation $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Reservation $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservation $reservation)
    {
      $request->validateWithBag('updateBookingRequest', array(
        'room_id' => ['required', 'integer'],
        'start_time' => ['required', 'string', 'max:255'],
        'end_time' => ['required', 'string', 'max:255'],
      ));

      $room = Room::query()->findOrFail($request->room_id);
      $room->verifyDatetimesAreWithinAvailabilities($request->get('start_time'), $request->get('end_time'));
      $room->verifyDatesAreWithinRoomRestrictions($request->get('start_time'), $request->get('end_time'));
      $reservation->room_id = $request->room_id;
      $reservation->start_time = $request->start_time;
      $reservation->end_time = $request->end_time;
      $reservation->save();
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
        if (!$booking->reservations()->exists()){
            $booking->delete();
        }
        return back();
    }
}
