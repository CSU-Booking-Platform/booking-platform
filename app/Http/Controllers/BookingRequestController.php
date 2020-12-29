<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\BookingRequest;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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
        
        $referenceFolder = NULL;
        
        if($request->file())
        {
            $referenceFolder = $request->room_id.'_'.strtotime($request->start_time).'_reference/';
            
            foreach($request->reference as $file)
            {
                $name = $file->getClientOriginalName();
                Storage::disk('public')->putFileAs($referenceFolder, $file, $name);
            }    
        }

        $room = Room::available()->findOrFail($request->room_id);
        $room->verifyDatetimesAreWithinAvailabilities($request->get('start_time'), $request->get('end_time'));

        BookingRequest::create([
            'room_id' => $room->id,
            'user_id' => $request->user()->id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time, 
            'status' => "review",
            'reference' => ["path" => $referenceFolder]
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return \Illuminate\Http\Response
     */
    // public function show(BookingRequest $bookingRequest)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return \Illuminate\Http\Response
     */
    // public function edit(BookingRequest $bookingRequest)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BookingRequest  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookingRequest $booking)
    {
        $request->validateWithBag('updateBookingRequest', array(
            'room_id' => ['required', 'integer'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
        ));

        $room = Room::query()->findOrFail($request->room_id);
        $room->verifyDatetimesAreWithinAvailabilities($request->get('start_time'), $request->get('end_time'));

        $booking->fill($request->except(['reference']))->save();
        
        if($request->file())
        {    
            $referenceFolder = $request->room_id.'_'.strtotime($request->start_time).'_reference/';

            if(isset($booking->reference["path"]))
            {
                $referenceFolder = $booking->reference["path"];
            }
            foreach($request->reference as $file)
            {
                $name = $file->getClientOriginalName();
                Storage::disk('public')->putFileAs($referenceFolder, $file, $name);
            }  
            $booking->reference = ['path' => $referenceFolder];
            $booking->save();
        }
        
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingRequest $booking)
    {
        $booking->delete();

        return redirect(route('bookings.index'));
    }
}
