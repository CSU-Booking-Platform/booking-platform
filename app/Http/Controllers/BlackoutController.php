<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Blackout;

class BlackoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     

    *public function index()
    *{
        
    *}
    */
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        
        $request->validateWithBag('createBlackout', [
            'room_id' => ['required', 'integer', 'exists:'.Room::class.",id"],
            'start' => ['required', 'date'],
            'end' => ['required', 'date', "after:start"],
        ]);
        
        Blackout::create([
            'start_time' => $request->start,
            'end_time' => $request->end
        ])->rooms()->attach($request->room_id);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    
    *public function edit($id)
    *{
       
    *}

  
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blackout $blackout)
    {
        $request->validateWithBag('createBlackout', [
            'start' => ['required', 'date'],
            'end' => ['required', 'date', "after:start"],
        ]);

        if ($request->start) {
            $blackout->start_time = $request->start;
        }

        if ($request->end) {
            $blackout->end_time = $request->end;
        }

        $blackout->save();
        return back()->with('flash', ['updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blackout $blackout)
    {   
        $blackout->rooms()->sync([]);
        $blackout->delete();
        return back()->with('flash', ['deleted']);
    }

    public function room(Room $room)
    {
        return inertia('Admin/Blackouts/Room', [
            "blackouts"=>$room->blackouts()->get(),
            "room"=>$room]);

    }

}
