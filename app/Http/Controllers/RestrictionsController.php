<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RestrictionsController extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $room)
    {

        $request->validateWithBag('updateRoomRestriction', [
            'restrictions' => 'array|present',
            'restrictions.*' => 'integer',
        ]);
        $room = Room::where('id', $room)->firstOrFail();

        $room->restrictions()->sync(collect($request->restrictions));


        return redirect(route('rooms.index'))->with('flash', ['updated' => $room]);
    }

}
