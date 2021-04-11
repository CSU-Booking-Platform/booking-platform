<?php

namespace App\Mail;

use App\Models\BookingRequest;
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\HtmlString;

class CommentMailable extends Mailable
{
    use Queueable, SerializesModels;

    public Comment $comment;
    public BookingRequest $booking;
    public MailMessage $message;

    /**
     * Create a new notification instance.
     *
     * @param Comment $comment
     * @param BookingRequest $booking
     */
    public function __construct(Comment $comment, BookingRequest $booking)
    {
        $this->comment = $comment;
        $this->booking = $booking;

        $commenter = $this->comment->user()->first()->name;

        $requester = $this->booking->requester()->first();
        $requesterName = $requester->name;
        $event = $this->booking->event;
        $room = $this->booking->rooms()->first();
        $url = route('bookings.reviews.show', $this->comment->booking_id);

        $this->message = (new MailMessage)
            ->subject('New review comments from ' . $commenter . ' on for Event: ' . $event['title'])
            ->greeting('New review comments from ' . $commenter . ':')
            ->line(new HtmlString($this->comment->body))
            ->line(new HtmlString(
                '<p><b>Booking Officer:</b> ' . $requesterName . '<br>' .
                '<b>Email:</b> ' . $requester->email . '<br>' .
                '<b>Event Title:</b> ' . $event['title'] . '<br>' .
                '<b>Room name:</b> ' . $room['name'] . '<br>' .
                '<b>Room number:</b> ' . $room['number'] . '<br></p>'
            ))
            ->action('View Review', $url);

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('notifications::email', $this->message->data());
    }
}
