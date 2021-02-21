<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomDateRestrictionsController extends Controller
{
  public function __invoke(Request $request, Room $room)
  {
    //Validation that min is smaller than max.
    //validation that inputs are positive numbers


    $request->validateWithBag('updateRoomRestriction', [
      'date_restrictions' => 'array|present',
      'date_restrictions.*.min_days_advance' => 'nullable|integer',
      'date_restrictions.*.max_days_advance' => 'nullable|integer',
      'date_restrictions.*' => 'nullable|array',
    ],  ['integer' => 'This field must be an integer.']);


    $values = $request->date_restrictions;
    foreach ($values as $index => $role) {
      if ($role == null || ($role['min_days_advance'] === null && $role['max_days_advance'] === null)) {
        unset($values[$index]);
      }
    }

    $room->dateRestrictions()->sync($values);

    return redirect(route('admin.rooms.index'))->with('flash', ['updated' => $room]);

  }
}
