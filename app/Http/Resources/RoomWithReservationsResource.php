<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomWithReservationsResource extends JsonResource
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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'floor' => $this->floor,
            'building' => $this->building,
            'room_type' => $this->room_type,
            'reservations' => CalendarEntryResource::collection($this->whenLoaded('reservations')),
            'blackouts' => CalendarEntryResource::collection($this->whenLoaded('blackouts')),
        ];
    }
}
