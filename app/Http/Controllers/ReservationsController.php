<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
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
     * @param Reservation $reservations
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservations)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Reservation $reservations
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservations)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Reservation $reservations
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservation $reservations)
    {
        //
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
