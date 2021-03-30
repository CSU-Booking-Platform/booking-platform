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
        $comment->body = $request->comment;
        $booking->comments()->save($comment);

        return back()->with('flash', ['banner' => 'Your comment was stored!']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  BookingRequest  $booking
     * @param  Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookingRequest $booking, Comment $comment)
    {
        $request->validateWithBag('updateComment', [
            'comment' => 'required|string:255'
        ]);

        $comment->body = $request->comment;
        $comment->save();

        return back()->with('flash', ['banner' => 'Your comment was updated!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  BookingRequest  $booking
     * @param  Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingRequest $booking, Comment $comment)
    {
        $comment->delete();

        return back()->with('flash', ['banner' => 'Your comment was deleted!']);
    }
}
