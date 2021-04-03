<?php

namespace Tests\Feature;

use App\Models\BookingRequest;
use App\Models\Comment;
use Database\Factories\CommentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function can_retrieve_user_from_comment()
    {
        $user = $this->createUserWithPermissions(['bookings.create']);
        $booking = BookingRequest::factory()->create(['status'=>BookingRequest::REVIEW]);
        $body = '<p>test 123</p>';
        $comment = Comment::factory()->make(['body'=> $body]);
        $comment->booking_id = $booking->id;
        $comment->user_id = $user->id;
        $comment->system = false;

        $this->assertDatabaseCount('comments', 0);

        $comment->save();

        $this->assertDatabaseCount('comments', 1);
        $this->assertDatabaseHas('comments', [
            'system' => false,
            'body' => $body,
        ]);

        self::assertEquals($user->id, $comment->user()->get()[0]->id);
    }
}
