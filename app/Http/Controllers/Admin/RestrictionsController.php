<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

class RestrictionsController extends Controller
{

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param Room $room
   * @return Application|RedirectResponse|Response|Redirector
   */
  public function update(Request $request, Room $room)
  {

    $request->validateWithBag('updateRoomRestriction', [
      'restrictions' => 'array|present',
      'restrictions.*' => 'integer',
    ]);
    $room = Room::where('id', $room->id)->firstOrFail();

    $room->restrictions()->sync(collect($request->restrictions));


    return redirect(route('admin.rooms.index'))->with('flash', ['updated' => $room]);
  }

}
