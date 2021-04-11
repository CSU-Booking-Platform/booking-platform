<?php

namespace App\Observers;

use App\Models\Comment;
use App\Notifications\CommentNotification;
use Illuminate\Support\Facades\Auth;

class CommentObserver
{
    public function saved(Comment $comment)
    {
        if(Auth::check())
        {
            $booking = $comment->booking()->first();
            $reviewers = $booking->reviewers()->get();
            foreach ($reviewers as $reviewer) {
                $reviewer->notify(new CommentNotification($comment, $booking));
            }
            # Also notify the user
            $booking->requester()->first()->notify(new CommentNotification($comment, $booking));
        }
    }
}
