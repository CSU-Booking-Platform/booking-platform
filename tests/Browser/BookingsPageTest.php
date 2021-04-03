<?php

namespace Tests\Browser;

use App\Models\BookingRequest;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Bookings;
use Tests\DuskTestCase;

class BookingsPageTest extends DuskTestCase
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


    /**
     *
     * @return void
     */
    public function testClickOnPendingBooking()
    {
        $bookings = BookingRequest::factory()
            ->count(1)
            ->hasReservations(random_int(1, 3))
            ->create(["status" => BookingRequest::PENDING])->first();

        $this->browse(function (Browser $browser) use ($bookings) {
            $browser->loginAs(User::first());
            $browser->visit('/bookings')
                ->clickLink("Edit", 'button')
                ->pause(5000)
                ->assertPathIs('/bookings/' . $bookings->id . '/edit');
            $browser->assertSee('Submit A Booking Request');
        });
    }

    public function testClickOnApprovedBooking()
    {
        $bookings = BookingRequest::factory()
            ->count(1)
            ->hasReservations(random_int(1, 3))
            ->create(["status" => BookingRequest::APPROVED])->first();

        $this->browse(function (Browser $browser) use ($bookings) {
            $browser->loginAs(User::first());
            $browser->visit('/bookings')
                ->clickLink("Approved", 'button')
                ->pause(5000)
                ->assertPathIs('/bookings/' . $bookings->id . '/view');
            $browser->assertSee(ucfirst(BookingRequest::APPROVED));
        });
    }

    public function testClickOnDeniedBooking()
    {
        $bookings = BookingRequest::factory()
            ->count(1)
            ->hasReservations(random_int(1, 3))
            ->create(["status" => BookingRequest::REFUSED])->first();

        $this->browse(function (Browser $browser) use ($bookings) {
            $browser->loginAs(User::first());
            $browser->visit('/bookings')
                ->clickLink("Refused", 'button')
                ->pause(5000)
                ->assertPathIs('/bookings/' . $bookings->id . '/view');
            $browser->assertSee(ucfirst(BookingRequest::REFUSED));
        });
    }

    public function testClickOnReviewBooking()
    {
        $bookings = BookingRequest::factory()
            ->count(1)
            ->hasReservations(random_int(1, 3))
            ->create(["status" => BookingRequest::REVIEW])->first();

        $this->browse(function (Browser $browser) use ($bookings) {
            $browser->loginAs(User::first());
            $browser->visit('/bookings')
                ->clickLink("In Review", 'button')
                ->pause(5000)
                ->assertPathIs('/bookings/' . $bookings->id . '/view');
            $browser->assertSee(ucfirst(BookingRequest::REVIEW));
        });
    }

}
