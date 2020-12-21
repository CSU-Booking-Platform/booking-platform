<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookingRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return inertia('Admin/BookingRequests/Index', [
            'booking_requests' => BookingRequest::with('user', 'room')->get(),
            'rooms' => Room::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validateWithBag('createBookingRequest', [
            'room_id' => ['required', 'integer'],
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date'],
        ]);

        $room = Room::findOrFail($request->room_id); 
            
        BookingRequest::create([
            'room_id' => $request->room_id,
            'user_id' => $request->user()->id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => "review"
        ]);


        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return \Illuminate\Http\Response
     */
    public function show(BookingRequest $bookingRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(BookingRequest $bookingRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BookingRequest  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookingRequest $booking)
    {
        $request->validateWithBag('updateBookingRequest', [
            'room_id' => ['required', 'integer'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
        ]);

        $booking->fill($request->all())->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingRequest $bookingRequest)
    {
        $bookingRequest->delete();

        return redirect(route('bookings.index'));
    }
}
