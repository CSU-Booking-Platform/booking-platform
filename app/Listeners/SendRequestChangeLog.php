<?php

namespace App\Listeners;

use App\Events\BookingRequestUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRequestChangeLog
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BookingRequestUpdated  $event
     * @return void
     */
    public function handle(BookingRequestUpdated $event)
    {
        if(!$event->booking_request->log)
        {
            $event->booking_request->log = [];
        }
        $event->booking_request->log = array_merge([time() =>$event->log], $event->booking_request->log);
        $event->booking_request->save();
    }
}
