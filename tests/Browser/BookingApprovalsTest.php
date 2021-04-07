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

/**
 * ATs: AT-24, AT-464
 * Issues: #188, #578
 */
class BookingApprovalsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * #188 -> AT-24-1
     * @test
     */
    public function testReviewersCanReviewBookingRequests()
    {
        $this->browse(function (Browser $browser) {
            $user = $this->createUserWithPermissions(['bookings.approve']);
            $booking = BookingRequest::factory()->hasReservations(1)->hasReviewers(1)->make(["status" => BookingRequest::PENDING]);

            $browser->loginAs($user)->visitRoute('bookings.reviews.index');
            $browser->assertSee('There are no booking requests to be displayed');

            $booking->save();

            $browser->loginAs($user)->visitRoute('bookings.reviews.index');
            $browser->assertVue('bookings[0].id', $booking->id, '@booking-requests-table');
            $browser->assertVue('bookings[0].status', ucfirst($booking->status), '@booking-requests-table');

            $browser->clickLink('Open Details');
            $browser->waitForRoute('bookings.reviews.show', $booking);
            $browser->assertSee('REFUSE');
            $browser->assertSee('APPROVE');
        });
    }

    /**
     * #188 -> AT-24-2
     * @test
     */
    public function testReviewersCanSeeNotificationBadge()
    {
        $this->browse(function (Browser $browser) {
            $user = $this->createUserWithPermissions(['bookings.approve']);
            $booking = BookingRequest::factory()->hasReservations(1)->create(["status" => BookingRequest::PENDING]);

            $browser->loginAs($user)->visitRoute('bookings.reviews.index');
            $browser->assertSourceMissing('<span class="px-2 py-1 ml-2 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
              1
            </span>');

            $booking->reviewers()->attach($user);
            $booking->save();

            $browser->loginAs($user)->visitRoute('bookings.reviews.index');
            $browser->assertSourceHas('<span class="px-2 py-1 ml-2 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
              1
            </span>');

        });
    }

    /**
     * #578 -> AT-464-1
     * @test
     */
    public function testReviewersCanReassignBookingWhileItsInReview()
    {
        $this->browse(function (Browser $browser) {
            $user = $this->createUserWithPermissions(['bookings.approve']);
            $booking = BookingRequest::factory()
                ->hasReservations(1)
                ->hasReviewers(1)->create(["status" => BookingRequest::REVIEW]);

            $browser->loginAs($user)->visitRoute('bookings.reviews.show', $booking);

            $reviewer = $booking->reviewers->first();
            $browser->assertSee($reviewer->name);
            $browser->assertVue('editing', true, '@booking-reviewers-field');

            $new = User::factory()->make();
            $new->assignRole(['super-admin'])->save();

            $this->assertEquals(2, User::permission(['bookings.approve'])->count());

            $browser->click("#remove-$reviewer->id")->waitUntilMissingText($reviewer->name);
            $browser->waitUntilVueIsNot('form.processing', true, '@booking-reviewers-field');

            $browser->waitForText($new->name)->click("#select-$new->id");
            $browser->waitUntilVueIsNot('form.processing', true, '@booking-reviewers-field');
            $browser->clickAtPoint(1,1); // dismiss overlay


        });
    }

    /**
     * #578 -> AT-464-2
     * @test
     */
    public function testReviewersCantChangeAfterBookingIsReviewed()
    {
        $this->browse(function (Browser $browser) {
            $user = $this->createUserWithPermissions(['bookings.approve']);
            $booking = BookingRequest::factory()
                ->hasReservations(1)
                ->hasReviewers(1)->create(["status" => BookingRequest::APPROVED]);

            $browser->loginAs($user)->visitRoute('bookings.reviews.show', $booking);

            $browser->assertVue('editing', false, '@booking-reviewers-field');
            $browser->assertSee($booking->reviewers->first()->name);
        });
    }

    /**
     * #578 -> AT-464-3
     * @test
     */
    public function testUsersCantChangeBookingReviewers()
    {
        $this->browse(function (Browser $browser) {
            $booking = BookingRequest::factory()
                ->hasReservations(1)
                ->hasReviewers(1)
                ->create(["status" => BookingRequest::REVIEW]);

            $browser->loginAs($booking->requester)->visitRoute('bookings.view', $booking);
            $browser->assertSee('This request is currently under review and cannot be modified');

            $browser->assertVue('editing', false, '@booking-reviewers-field');
            $browser->assertSee($booking->reviewers->first()->name);
        });
    }

}
