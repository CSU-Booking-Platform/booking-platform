<?php

namespace Tests\Feature;

use App\Models\BookingRequest;
use App\Models\Comment;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function users_can_create_comments()
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->make();
        $user->givePermissionTo('bookings.approve')->save();

        $booking = BookingRequest::factory()->create(['status'=>BookingRequest::REVIEW]);
        $this->assertDatabaseCount('comments', 0);
        $comment = '<p>test</p>';
        $response = $this->actingAs($user)->post("/bookings/{$booking->id}/comment/",
            ['comment' => $comment]
        );
        $response->assertStatus(302);
        $this->assertDatabaseCount('comments', 1);
        $this->assertDatabaseHas('comments', [
            'system' => false,
            'body' => $comment,
        ]);

        $response = $this->actingAs($user)->get(route('bookings.view', $booking));
        $response->assertSessionHasNoErrors();
    }
}
