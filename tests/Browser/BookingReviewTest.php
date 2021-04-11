<?php

namespace Tests\Browser;

use App\Models\BookingRequest;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use NoelDeMartin\LaravelDusk\Browser;
use Tests\DuskTestCase;

class BookingReviewTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        (new RolesAndPermissionsSeeder())->run();
        (new RoomSeeder())->run();
        User::factory(1)->create()->first();
        User::first()->assignRole('super-admin');
    }

    public function testUserCanViewBookingReviewPage()
    {
        $booking = BookingRequest::factory()
            ->count(1)
            ->hasReservations(random_int(1, 3))
            ->create(["status" => BookingRequest::REVIEW])->first();

        $this->browse(function (Browser $browser) use ($booking)
        {
            $browser->loginAs(User::first());
            $browser->visit('/bookings/review')
                ->clickLink('Open Details')
                ->waitForText('Booking Overview')
                ->assertPathIs('/bookings/' . $booking->id . '/review')
                ->assertSee('Booking History');
        });
    }

    public function testUserPostComments()
    {
        $booking = BookingRequest::factory()
            ->count(1)
            ->hasReservations(random_int(1, 3))
            ->create(["status" => BookingRequest::REVIEW])->first();

        $user = User::first();

        $this->browse(function (Browser $browser) use ($user, $booking)
        {
            $browser->loginAs($user);
            $browser->visit('/bookings/review')
                ->clickLink('Open Details')
                ->waitForText('Booking Overview')
                ->assertPathIs('/bookings/' . $booking->id . '/review')
                ->assertSee('Booking History');

            $browser->scrollTo('.ProseMirror')
                ->click('.ProseMirror')
                ->type('.ProseMirror', 'This is a test comment.')
                ->with('#comment-textbox', function ($b) {
                    $b->click('@submit');
                    $b->waitUntilMissingText('This is a test comment.');
                })
                ->waitForText('This is a test comment.');
        });
    }
}
