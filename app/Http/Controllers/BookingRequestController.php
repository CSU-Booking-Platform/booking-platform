<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\Room;
use Illuminate\Http\Request;

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
            'booking_requests' => BookingRequest::all(),
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
            'user_id' => ['required', 'integer'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
        ]);

        BookingRequest::create([
            'room_id' => $request->room_id,
            'user_id' => $request->user_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'available' => false
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
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookingRequest $bookingRequest)
    {
        $request->validateWithBag('updateBookingRequest', [
            'user_id' => ['required', 'integer'],
            'roomd_id' => ['required', 'integer'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
        ]);

        $bookingRequest->fill($request->all())->save();

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

        return redirect(route('bookingRequest.index'));
    }
}
