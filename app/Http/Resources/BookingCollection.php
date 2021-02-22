<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BookingCollection extends ResourceCollection
{

    /**
     * The "data" wrapper that should be applied.
     * Remove the useless data wrapper, bookings aren't paginated
     *
     * @var string
     */
    public static $wrap = null;

}
