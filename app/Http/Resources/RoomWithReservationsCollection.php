<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RoomWithReservationsCollection extends RoomCollection
{

    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = RoomWithReservationsResource::class;

}
