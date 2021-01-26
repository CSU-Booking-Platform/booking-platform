<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Role;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        return inertia('Admin/Rooms/Index', [
            'rooms' => Room::with('restrictions', 'availabilities')->get(),
            'roles' => Role::all(),
            'availableRoomTypes'=> Room::ROOM_TYPES,
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
            'status' => ['required', 'string', 'max:255'],
            'capacity_standing' => ['nullable', 'integer'],
            'capacity_sitting' => ['nullable', 'integer'],
            'food' => ['required', 'boolean'],
            'alcohol' => ['required', 'boolean'],
            'a_v_permitted' => ['required', 'boolean'],
            'projector' => ['required', 'boolean'],
            'television' => ['required', 'boolean'],
            'computer' => ['required', 'boolean'],
            'whiteboard' => ['required', 'boolean'],
            'sofas' => ['nullable', 'integer'],
            'coffee_tables' => ['nullable', 'integer'],
            'tables' => ['nullable', 'integer'],
            'chairs' => ['nullable', 'integer'],
            'ambiant_music' => ['required', 'boolean'],
            'sale_for_profit' => ['required', 'boolean'],
            'fundraiser' => ['required', 'boolean'],
            'room_type' => ['required', 'string', 'max:255'],
            'availabilities.Monday.opening_hours' => 'nullable|required_with:availabilities.Monday.closing_hours|before:availabilities.Monday.closing_hours',
            'availabilities.Monday.closing_hours' => 'nullable|required_with:availabilities.Monday.opening_hours|after:availabilities.Monday.opening_hours',
            'availabilities.Tuesday.opening_hours' => 'nullable|required_with:availabilities.Tuesday.closing_hours|before:availabilities.Tuesday.closing_hours',
            'availabilities.Tuesday.closing_hours' => 'nullable|required_with:availabilities.Tuesday.opening_hours|after:availabilities.Tuesday.opening_hours',
            'availabilities.Wednesday.opening_hours' => 'nullable|required_with:availabilities.Wednesday.closing_hours|before:availabilities.Wednesday.closing_hours',
            'availabilities.Wednesday.closing_hours' => 'nullable|required_with:availabilities.Wednesday.opening_hours|after:availabilities.Wednesday.opening_hours',
            'availabilities.Thursday.opening_hours' => 'nullable|required_with:availabilities.Thursday.closing_hours|before:availabilities.Thursday.closing_hours',
            'availabilities.Thursday.closing_hours' => 'nullable|required_with:availabilities.Thursday.opening_hours|after:availabilities.Thursday.opening_hours',
            'availabilities.Friday.opening_hours' => 'nullable|required_with:availabilities.Friday.closing_hours|before:availabilities.Friday.closing_hours',
            'availabilities.Friday.closing_hours' => 'nullable|required_with:availabilities.Friday.opening_hours|after:availabilities.Friday.opening_hours',
            'availabilities.Saturday.opening_hours' => 'nullable|required_with:availabilities.Saturday.closing_hours|before:availabilities.Saturday.closing_hours',
            'availabilities.Saturday.closing_hours' => 'nullable|required_with:availabilities.Saturday.opening_hours|after:availabilities.Saturday.opening_hours',
            'availabilities.Sunday.opening_hours' => 'nullable|required_with:availabilities.Sunday.closing_hours|before:availabilities.Sunday.closing_hours',
            'availabilities.Sunday.closing_hours' => 'nullable|required_with:availabilities.Sunday.opening_hours|after:availabilities.Sunday.opening_hours',
        ]);

        $room = Room::create([
            'name' => $request->name,
            'number' => $request->number,
            'floor' => $request->floor,
            'building' => $request->building,
            'status' => $request->status,
            'attributes' => [
                'capacity_standing' => $request->capacity_standing,
                'capacity_sitting' => $request->capacity_sitting,
                'food' => $request->food,
                'alcohol' => $request->alcohol,
                'a_v_permitted' => $request->a_v_permitted,
                'projector' => $request->projector,
                'television' => $request->television,
                'computer' => $request->computer,
                'whiteboard' => $request->whiteboard,
                'sofas' => $request->sofas,
                'coffee_tables' => $request->coffee_tables,
                'tables' => $request->tables,
                'chairs' => $request->chairs,
                'ambiant_music' => $request->ambiant_music,
                'sale_for_profit' => $request->sale_for_profit,
                'fundraiser' => $request->fundraiser,
            ],

            'room_type' => $request->room_type
        ]);

        $availabilities = $request->get('availabilities');

        if (!empty($availabilities)) {
            foreach ($availabilities as $weekday => $availability) {
                if (!empty($availability['opening_hours']) && !empty($availability['closing_hours'])) {
                    Availability::create([
                        'weekday' => $weekday,
                        'opening_hours' => $availability['opening_hours'],
                        'closing_hours' => $availability['closing_hours'],
                        'room_id' => $room->id
                    ]);
                }
            }
        }

        return back();
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
    public function update(Request $request, Room $room)
    {
        $request->validateWithBag('updateRoom', [
            'name' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:255'],
            'floor' => ['required', 'integer'],
            'building' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'availabilities.Monday.opening_hours' => 'nullable|required_with:availabilities.Monday.closing_hours|before:availabilities.Monday.closing_hours',
            'availabilities.Monday.closing_hours' => 'nullable|required_with:availabilities.Monday.opening_hours|after:availabilities.Monday.opening_hours',
            'availabilities.Tuesday.opening_hours' => 'nullable|required_with:availabilities.Tuesday.closing_hours|before:availabilities.Tuesday.closing_hours',
            'availabilities.Tuesday.closing_hours' => 'nullable|required_with:availabilities.Tuesday.opening_hours|after:availabilities.Tuesday.opening_hours',
            'availabilities.Wednesday.opening_hours' => 'nullable|required_with:availabilities.Wednesday.closing_hours|before:availabilities.Wednesday.closing_hours',
            'availabilities.Wednesday.closing_hours' => 'nullable|required_with:availabilities.Wednesday.opening_hours|after:availabilities.Wednesday.opening_hours',
            'availabilities.Thursday.opening_hours' => 'nullable|required_with:availabilities.Thursday.closing_hours|before:availabilities.Thursday.closing_hours',
            'availabilities.Thursday.closing_hours' => 'nullable|required_with:availabilities.Thursday.opening_hours|after:availabilities.Thursday.opening_hours',
            'availabilities.Friday.opening_hours' => 'nullable|required_with:availabilities.Friday.closing_hours|before:availabilities.Friday.closing_hours',
            'availabilities.Friday.closing_hours' => 'nullable|required_with:availabilities.Friday.opening_hours|after:availabilities.Friday.opening_hours',
            'availabilities.Saturday.opening_hours' => 'nullable|required_with:availabilities.Saturday.closing_hours|before:availabilities.Saturday.closing_hours',
            'availabilities.Saturday.closing_hours' => 'nullable|required_with:availabilities.Saturday.opening_hours|after:availabilities.Saturday.opening_hours',
            'availabilities.Sunday.opening_hours' => 'nullable|required_with:availabilities.Sunday.closing_hours|before:availabilities.Sunday.closing_hours',
            'availabilities.Sunday.closing_hours' => 'nullable|required_with:availabilities.Sunday.opening_hours|after:availabilities.Sunday.opening_hours',
            'room_type' => ['required', 'string', 'max:255']
        ]);

        $room->fill($request->except('attributes'))->save();

        $room->attributes = [
            'capacity_standing' => $request->capacity_standing,
            'capacity_sitting' => $request->capacity_sitting,
            'food' => $request->food,
            'alcohol' => $request->alcohol,
            'a_v_permitted' => $request->a_v_permitted,
            'projector' => $request->projector,
            'television' => $request->television,
            'computer' => $request->computer,
            'whiteboard' => $request->whiteboard,
            'sofas' => $request->sofas,
            'coffee_tables' => $request->coffee_tables,
            'tables' => $request->tables,
            'chairs' => $request->chairs,
            'ambiant_music' => $request->ambiant_music,
            'sale_for_profit' => $request->sale_for_profit,
            'fundraiser' => $request->fundraiser,
        ];

        $room->save();

        if ($request->has('availabilities')) {
            foreach ($request->availabilities as $weekday => $availability) {
                if (!empty($availability['opening_hours']) && !empty($availability['closing_hours'])) {

                    if ($availabilityModel =
                        Availability::query()
                            ->where('room_id', '=', $room->id)
                            ->where('weekday', '=', $weekday)
                            ->first()
                    ) {
                        $availabilityModel->opening_hours = $availability['opening_hours'];
                        $availabilityModel->closing_hours = $availability['closing_hours'];
                        $availabilityModel->save();
                    } else {
                        Availability::create([
                            'weekday' => $weekday,
                            'opening_hours' => $availability['opening_hours'],
                            'closing_hours' => $availability['closing_hours'],
                            'room_id' => $room->id
                        ]);
                    }
                }
            }
        }

        return redirect(route('rooms.index'))->with('flash', ['updated' => $room]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return redirect(route('rooms.index'));
    }

    /**
     * Filter room by given json payload
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
      // None of the request fields are mandatory, only
      // filter the ones provided from request
      $numeric_filter = 'numeric|min:0|not_in:0';
      $request->validate([
        'capacity_standing' => $numeric_filter,
        'capacity_sitting' => $numeric_filter,
        'food' => ['boolean'],
        'alcohol' => ['boolean'],
        'a_v_permitted' => ['boolean'],
        'projector' => ['boolean'],
        'television' => ['boolean'],
        'computer' => ['boolean'],
        'whiteboard' => ['boolean'],
        'sofas' => $numeric_filter,
        'coffee_tables' => $numeric_filter,
        'tables' => $numeric_filter,
        'chairs' => $numeric_filter,
        'ambiant_music' => ['boolean'],
        'sale_for_profit' => ['boolean'],
        'fundraiser' => ['boolean'],
      ]);

      // If param value boolean, filter for boolean and if
      // numeric filter greater than integer in that column
      $query = Room::query();
      foreach ($request->toArray() as $key => $value){
        if(is_numeric($value)){
          $query->where('attributes->' . $key, '>=', $value);
        }else{
          if(is_bool($value)) {
            $query->where('attributes->' . $key, $value);
          }
        }

      }
      return response()->json($query->get());
    }
}
