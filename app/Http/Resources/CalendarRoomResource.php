<?php

namespace App\Http\Resources;

use Carbon\CarbonPeriod;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CalendarRoomResource extends JsonResource
{

    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $date = Carbon::parse($request->date)->startOfDay();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'floor' => $this->floor,
            'building' => $this->building,
            'room_type' => $this->room_type,
            'reservations' => CalendarEntryResource::collection($this->whenLoaded('reservations')),
            'blackouts' => CalendarEntryResource::collection($this->whenLoaded('blackouts')),
            'availabilities' => $this->whenLoaded('availabilities', function () {
                return $this->availabilities->map(function ($open) {
                    return [
                        'id' => $open->id,
                        'opening_hours' => $open->opening_hours,
                        'closing_hours' => $open->closing_hours
                    ];
                });
            }),
            'day_breakdown' => $this->prepareDateDetails($date),
        ];
    }

    public function prepareDateDetails(Carbon $date) {
        $availabilities = $this->whenLoaded('availabilities', function () use ($date) {
            return $this->availabilities->map(function ($open) use ($date) {
                $start = $date->clone()->setTimeFromTimeString($open->opening_hours);
                $end = $date->clone()->setTimeFromTimeString($open->closing_hours);
                return CarbonPeriod::create($start, $end);
            });
        });
        $blackouts = $this->whenLoaded('blackouts', function () {
            return $this->blackouts->map(function ($blackout) {
                return CarbonPeriod::create($blackout->start_time, $blackout->end_time);
            });
        });
        $reservations = $this->whenLoaded('reservations', function () {
            return $this->reservations->map(function ($reservation) {
                return CarbonPeriod::create($reservation->start_time, $reservation->end_time);
            });
        });

        // generate a day breakdown, split by 15m
        $day = Collection::times(24 * 4, function ($index) use ($date, $availabilities, $blackouts, $reservations) {

            $end = $date->clone()->addMinutes(15 * $index);
            $start = $end->clone()->subMinutes(15);
            $period = CarbonPeriod::create($start, $end);

            $closed = $availabilities->contains(function ($value) use ($period) {
                return !$period->overlaps($value); // open when there's an overlap
            }) || $blackouts->contains(function ($value) use ($period) {
                return $period->overlaps($value); // closed when there's an overlap
            });

            $booked = $reservations->contains(function ($value) use ($period) {
                return $period->overlaps($value);
            });

            return [
                'start' => $start,
                'end' => $end,
                'start_time' => $start->format('H:i'),
                'end_time' => $end->format('H:i'),
                'closed' => $closed,
                'booked' => $booked,
            ];
        });
        // split by hours and return
        return $day->chunk(4)->toArray();
    }
}
