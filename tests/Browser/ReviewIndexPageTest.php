<?php

namespace Tests\Browser;

use App\Models\Availability;
use App\Models\BookingRequest;
use App\Models\Role;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\DateTimePicker;
use Tests\DuskTestCase;

class ReviewIndexPageTest extends DuskTestCase
{

    use DatabaseMigrations;

    public function testWhenFilterBookingRequestReviewListText()
    {
        (new RolesAndPermissionsSeeder())->run();

        $admin = User::factory()->create();
        $admin->assignRole('super-admin');
        $booking = BookingRequest::factory()
            ->hasReservations(1)
            ->hasReviewers(1)
            ->hasComments(0)
            ->create(["status"=>BookingRequest::PENDING, 'user_id'=>$admin->id]);
        $booking2 = BookingRequest::factory()
            ->hasReservations(1)
            ->hasReviewers(1)
            ->hasComments(0)
            ->create(["status"=>BookingRequest::REVIEW, 'user_id'=>$admin->id]);
        $booking3 = BookingRequest::factory()
            ->hasReservations(1)
            ->hasReviewers(1)
            ->hasComments(0)
            ->create(["status"=>BookingRequest::APPROVED, 'user_id'=>$admin->id]);
        $booking4 = BookingRequest::factory()
            ->hasReservations(1)
            ->hasReviewers(1)
            ->hasComments(0)
            ->create(["status"=>BookingRequest::REFUSED, 'user_id'=>$admin->id]);
        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin);
            $browser->visit('/bookings/review');
                $browser->assertSee($admin->name);
                $browser->assertSee(ucfirst(BookingRequest::REVIEW));
                $browser->assertSee(ucfirst(BookingRequest::PENDING));
                $browser->assertDontSee(ucfirst(BookingRequest::APPROVED));
                $browser->assertDontSee(ucfirst(BookingRequest::REFUSED));
            $browser->type('#text-search-input', strtolower(BookingRequest::REVIEW))->pause(500);
            $browser->assertSee(ucfirst(BookingRequest::REVIEW));
            $browser->assertDontSee(ucfirst(BookingRequest::PENDING));
            $browser->clear('#text-search-input');
            $browser->type('#text-search-input', strtolower(BookingRequest::PENDING))->pause(500);
            $browser->assertSee(ucfirst(BookingRequest::PENDING));
        });
    }

    public function testWhenFilterBookingRequestReviewListApiStatus()
    {
        (new RolesAndPermissionsSeeder())->run();

        $admin = User::factory()->create();
        $admin->assignRole('super-admin');
        $booking = BookingRequest::factory()
            ->hasReservations(1)
            ->hasReviewers(1)
            ->hasComments(0)
            ->create(["status"=>BookingRequest::PENDING, 'user_id'=>$admin->id]);
        $booking2 = BookingRequest::factory()
            ->hasReservations(1)
            ->hasReviewers(1)
            ->hasComments(0)
            ->create(["status"=>BookingRequest::REVIEW, 'user_id'=>$admin->id]);
        $booking3 = BookingRequest::factory()
            ->hasReservations(1)
            ->hasReviewers(1)
            ->hasComments(0)
            ->create(["status"=>BookingRequest::APPROVED, 'user_id'=>$admin->id]);
        $booking4 = BookingRequest::factory()
            ->hasReservations(1)
            ->hasReviewers(1)
            ->hasComments(0)
            ->create(["status"=>BookingRequest::REFUSED, 'user_id'=>$admin->id]);
        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin);
            $browser->visit('/bookings/review');
                $browser->assertSee($admin->name);
                $browser->assertSee(ucfirst(BookingRequest::REVIEW));
                $browser->assertSee(ucfirst(BookingRequest::PENDING));
                $browser->assertDontSee(ucfirst(BookingRequest::APPROVED));
                $browser->assertDontSee(ucfirst(BookingRequest::REFUSED));
            $browser->click("#open-filter-button");
            $browser->check('#status-'.BookingRequest::APPROVED);
            $browser->click('#submit-advanced-filter')->pause(2000);
            $browser->assertDontSee(ucfirst(BookingRequest::PENDING));
            $browser->assertSee(ucfirst(BookingRequest::APPROVED))->pause(1000);

            $browser->click("#open-filter-button");
            $browser->check('#status-'.BookingRequest::REFUSED);
            $browser->check('#status-'.BookingRequest::PENDING);
            $browser->check('#status-'.BookingRequest::REVIEW);
            $browser->click('#submit-advanced-filter')->pause(1000);
            $browser->assertSee(ucfirst(BookingRequest::APPROVED));
            $browser->assertSee(ucfirst(BookingRequest::REVIEW));
            $browser->assertSee(ucfirst(BookingRequest::REFUSED));
            $browser->assertSee(ucfirst(BookingRequest::PENDING));

        });
    }

    public function testWhenFilterBookingRequestReviewListApiDate()
    {
        (new RolesAndPermissionsSeeder())->run();

        $admin = User::factory()->create();
        $admin->assignRole('super-admin');
        $booking = BookingRequest::factory()
            ->hasReservations(1)
            ->hasReviewers(1)
            ->hasComments(0)
            ->create(["status"=>BookingRequest::PENDING, 'user_id'=>$admin->id]);
        $booking2 = BookingRequest::factory()
            ->hasReservations(1)
            ->hasReviewers(1)
            ->hasComments(0)
            ->create(["status"=>BookingRequest::REVIEW, 'user_id'=>$admin->id]);
        $booking3 = BookingRequest::factory()
            ->hasReservations(1)
            ->hasReviewers(1)
            ->hasComments(0)
            ->create(["status"=>BookingRequest::APPROVED, 'user_id'=>$admin->id]);
        $booking4 = BookingRequest::factory()
            ->hasReservations(1)
            ->hasReviewers(1)
            ->hasComments(0)
            ->create(["status"=>BookingRequest::REFUSED, 'user_id'=>$admin->id]);
        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin);
            $browser->visit('/bookings/review');
                $browser->assertSee($admin->name);
                $browser->assertSee(ucfirst(BookingRequest::REVIEW));
                $browser->assertSee(ucfirst(BookingRequest::PENDING));
                $browser->assertDontSee(ucfirst(BookingRequest::APPROVED));
                $browser->assertDontSee(ucfirst(BookingRequest::REFUSED));
            $browser->click("#open-filter-button")
                ->pause(500);

            $browser->within( new DateTimePicker('start_time'), function($browser) {
                $browser->setDatetime(10,13);
            })->pause(250);

            $browser->click('#submit-advanced-filter')->pause(2000);
            $browser->assertDontSee(ucfirst(BookingRequest::PENDING));
            $browser->assertDontSee(ucfirst(BookingRequest::APPROVED));
            $browser->assertDontSee(ucfirst(BookingRequest::REFUSED));
        });
    }

}
