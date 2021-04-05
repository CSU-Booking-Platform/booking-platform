<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CalendarRoomCollection extends RoomCollection
{

    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = CalendarRoomResource::class;

}
