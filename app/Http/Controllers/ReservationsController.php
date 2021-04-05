<?php

namespace App\Http\Controllers;

use App\Events\BookingRequestUpdated;
use App\Http\Resources\CalendarRoomCollection;
use App\Models\BookingRequest;
use App\Models\Reservation;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
//  public function index()
//  {
//    //
//  }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
//  public function create()
//  {
//    //
//  }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->reservationValidate($request, 'store');

//    //lazy for now
        $booking = BookingRequest::factory()->create([
            'user_id' => $request->user()->id,
            'status' => "review",
        ]);

        foreach ($request->reservations as $pair) {
            $reservation = new Reservation();
            $reservation->room_id = $request->room_id;
            $reservation->booking_request_id = $booking->id;
            $reservation->start_time = $pair['start_time'];
            $reservation->end_time = $pair['end_time'];
            $reservation->save();
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
//  public function show(Reservation $reservation)
//  {
//    //
//  }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
//  public function edit(Reservation $reservation)
//  {
//    //
//  }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Reservation  $reservation
     * @return RedirectResponse
     */
    public function update(Request $request, Reservation $reservation)
    {
        $this->reservationValidate($request, 'update', $reservation);

        $date_format = "F j, Y, g:i a";

        //for now only one
//    $reservation = $booking->reservations()->first();
        $reservation->room_id = $request->room_id;
        $reservation->start_time = $request->reservations[0]['start_time'];
        $reservation->end_time = $request->reservations[0]['end_time'];
        $reservation->save();

        $booking = $reservation->bookingRequest()->first();

        if ($reservation->wasChanged()) {
            $log = '['.date($date_format).']'.' - Updated booking request location and/or date';
            BookingRequestUpdated::dispatch($booking, $log);
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Reservation  $reservation
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(Reservation $reservation): RedirectResponse
    {
        $booking = $reservation->bookingRequest()->first();
        $reservation->delete();
        if (!$booking->reservations()->exists()) {
            $booking->delete();
        }
        return back();
    }

    /**
     *
     * @param  Request  $request
     * @param  Room  $room
     * @return \Illuminate\Http\Response
     */
    public function byRoom(Request $request, Room $room)
    {
        $date = $request->get('date');

        if (empty($date)) {
            return response()->json([]);
        }

        $roomReservations = Reservation::query()->where('room_id', '=', $room->id)
            ->whereDate('start_time', '=', Carbon::parse($date)->toDateString())
            ->whereHas('bookingRequest', function (Builder $subQuery) {
                $subQuery->where('status', 'like', '%approved%');
            })
            ->get();

        return response()->json($roomReservations);
    }

    /**
     *
     * @param  Request  $request
     * @param  Room  $room
     * @return CalendarRoomCollection
     */
    public function byDate(Request $request)
    {
        $request->validate(['date' => ['date']]);

        $date = $request->get('date') ?? today();

        // callback that filters reservations by date
        $callback = function ($query) use ($date) {
            if ($date !== null) {
                $query->whereDate('start_time', $date)->orWhereDate('end_time', $date);
            }
        };

        $rooms = Room::with([
            'reservations' => $callback,
            'blackouts' => $callback,
            'availabilities' => function($query) use ($date) {
                if ($date !== null) {
                    $query->where('weekday', Carbon::parse($date)->englishDayOfWeek);
                }
            }
        ])->get();

        // technically can be paginates but we're only fetching for 1 day
        return new CalendarRoomCollection($rooms);

    }

    private function reservationValidate(Request $request, $function, $reservation = null)
    {
        $request->validateWithBag($function.'ReservationsRequest', array(
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'reservations' => ['required'],
            'reservations.*.start_time' => ['required', 'date'],
            'reservations.*.end_time' => ['required', 'date'],
        ));

        $request->validateWithBag($function.'ReservationsRequest', array(
            'reservations.*' => [
                'array', 'size:2',
                function ($attribute, $value, $fail) use ($request, $reservation) {
                    $user = $request->user();
                    $room = Room::query()->findOrFail($request->room_id);
                    $room->verifyDatesAreWithinRoomRestrictionsValidation($value['start_time'], $fail, $user);
                    $room->verifyDatetimesAreWithinAvailabilitiesValidation($value['start_time'], $value['end_time'],
                        $fail);
                    $room->verifyRoomIsFreeValidation($value['start_time'], $value['end_time'], $fail, $reservation);

                    if (!$user->canMakeAnotherBookingRequest($value['start_time'])) {
                        $fail($attribute.' Cannot make more than '.
                            $user->getUserNumberOfBookingRequestPerPeriod().
                            ' bookings in the next '.
                            $user->getUserNumberOfDaysPerPeriod().
                            ' days.');
                    }
                }
            ]
        ));
    }
}
