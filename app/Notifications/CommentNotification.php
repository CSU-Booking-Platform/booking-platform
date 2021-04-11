<?php

namespace App\Notifications;

use App\Mail\CommentMailable;
use App\Models\BookingRequest;
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Nette\Utils\Html;

class CommentNotification extends Notification
{
    use Queueable;

    public Comment $comment;
    public BookingRequest $booking;

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
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return CommentMailable
     */
    public function toMail($notifiable)
    {
//        $commenter = $this->comment->user()->first()->name;
//
//        $requester = $this->booking->requester()->first();
//        $requesterName = $requester->name;
//        $event = $this->booking->event;
//        $room = $this->booking->rooms()->first();
//        $url = route('bookings.reviews.show', $this->comment->booking_id);
//        return (new MailMessage)
//            ->subject('New review comments from ' . $commenter . ' on for Event: ' . $event['title'])
//            ->greeting('New review comments from ' . $commenter . ':')
//            ->line(new HtmlString($this->comment->body))
//            ->line(new HtmlString(
//                '<p><b>Booking Officer:</b> ' . $requesterName . '<br>' .
//                '<b>Email:</b> ' . $requester->email . '<br>' .
//                '<b>Event Title:</b> ' . $event['title'] . '<br>' .
//                '<b>Room name:</b> ' . $room['name'] . '<br>' .
//                '<b>Room number:</b> ' . $room['number'] . '<br></p>'
//            ))
//            ->action('View Review', $url);
        return (new CommentMailable(
            $this->comment,
            $this->booking
        ))->to($notifiable->email);
    }
}
