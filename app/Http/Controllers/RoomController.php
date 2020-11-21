<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return inertia('Admin/Rooms/Index', [
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

        $request->validateWithBag('createRoom', [
            'name' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:255'],
            'floor' => ['required', 'integer'],
            'building' => ['required', 'string', 'max:255'],
        ]);

        Room::create([
            'name' => $request->name,
            'number' => $request->number,
            'floor' => $request->floor,
            'building' => $request->building
        ]);

        return back()->with('flash', ['we good']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    // public function show(Room $room)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    // public function edit(Room $room)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, Room $room)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Room $room)
    // {
    //     //
    // }
}
