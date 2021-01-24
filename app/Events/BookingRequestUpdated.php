<?php

namespace App\Events;

use App\Models\BookingRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class BookingRequestUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking_request;
    public $log;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(BookingRequest $booking_request, string $log)
    {
        $this->booking_request = $booking_request;
        $this->log = $log;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
