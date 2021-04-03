<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @param  BookingRequest  $booking
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, BookingRequest $booking)
    {
        $request->validateWithBag('createComment', [
            'comment' => 'required|string:65535',
        ]);

        $comment = new Comment;
        $comment->user_id = $request->user()->id;
        $comment->body = $request->comment;
        $booking->comments()->save($comment);

        return back()->with('flash', ['banner' => 'Your comment was stored!']);
    }

}
