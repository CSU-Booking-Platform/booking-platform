<?php

namespace Tests\Browser;

use App\Models\BookingRequest;
use App\Models\Room;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\DateTimePicker;
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

    public function testClickOnApprovedBookingLockedBooking()
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

    public function testClickOnDeniedBookingLockedBooking()
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

    public function testClickOnReviewBookingLockedBooking()
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

    public function testUserCanTrackBookingrequest()
    {
        $bookings = BookingRequest::factory()
            ->count(1)
            ->hasReservations(random_int(1, 3))
            ->create(["status" => BookingRequest::PENDING])->first();

        $this->browse(function (Browser $browser) use ($bookings) {
            $browser->loginAs(User::first());
            $browser->visit('/bookings')
                ->clickLink("View", 'button')
                ->pause(250);
            $browser->assertSee('Booking Request Status');
        });
    }

    public function testUserCanEditABooking()
    {
        $bookings = BookingRequest::factory()
            ->count(1)
            ->hasReservations(1)
            ->create(["status" => BookingRequest::PENDING])->first();
        $this->browse(function (Browser $browser) use ($bookings) {
            $browser->loginAs(User::first());
            $browser->visit('/bookings')
                ->clickLink("Edit", 'button')
                ->pause(250);
            $browser->assertSee('Submit A Booking Request')
                ->type('@title', 'title')
                ->type('@type', 'type')
                ->type('#event_description', 'description')
                ->type('#guest_speakers', 'speakers')
                ->type('@numberAttending', '2');
            $browser->check('div > main > form > div:nth-child(4) > div > div > input');
            $browser->press('SUBMIT')
                ->pause(800)
                ->assertSee('This booking cannot be submitted');
        });
    }

    /**
     * assert page has unique elements for Lounge rooms
     * #313 -> AT-255-1
     * @test
     */
    public function testUserCanFillBookingAttributesForLoungeRooms()
    {
        $this->browse(function (Browser $browser) {
            $user = $this->createUserWithPermissions(['bookings.create']);
            $browser->loginAs($user)->visitRoute('bookings.search');

            $room = Room::where('room_type', 'Lounge')->firstOrFail();
            $room->min_days_advance = 0;
            $room->max_days_advance = 10000;
            $room->save();

            $browser->press("@room-select-".$room->id);
            $browser->mouseover('#add-recurences-button');

            $browser->within( new DateTimePicker('start_time_0'), function($browser) {
                $browser->setDatetime(10,13);
            })->pause(250);
            $browser->within( new DateTimePicker('end_time_0'), function($browser) {
                $browser->setDatetime(10,14);
            })->pause(250);
            $browser->press('#createBookingRequest')->waitForRoute('bookings.create');

            $browser->scrollTo('#files');
            $browser->check('#children-checkbox');
            $browser->waitForText('Please remember to attach a filled out "Parental Waiver" to the document section');
            $browser->check('#av-checkbox');
            $browser->check('#furniture-checkbox');

            $browser->check('#terms-and-conditions');
            $browser->press('SUBMIT');
            $browser->waitForText("The files field is required when event.children is true");

            $browser->uncheck('#children-checkbox');
            $browser->check('#appliances-checkbox');
            $browser->waitForText('If you are not a CSU club please fill out the "Fire Prevention Form" to the document section');

            $browser->check('#terms-and-conditions');
            $browser->press('SUBMIT');
            $browser->waitForText("The files field is required when event.appliances is true");
        });
    }

    /**
     * assert page has unique elements for Mezzanine rooms
     * #313 -> AT-255-2
     * @test
     */
    public function testUserCanFillBookingAttributesForMezzanineRooms()
    {
        $this->browse(function (Browser $browser) {
            $user = $this->createUserWithPermissions(['bookings.create']);
            $browser->loginAs($user)->visitRoute('bookings.search');

            $room = Room::where('room_type', 'Mezzanine')->firstOrFail();
            $room->min_days_advance = 0;
            $room->max_days_advance = 10000;
            $room->save();

            $browser->press("@room-select-".$room->id);
            $browser->mouseover('#add-recurences-button');

            $browser->within( new DateTimePicker('start_time_0'), function($browser) {
                $browser->setDatetime(10,13);
            })->pause(250);
            $browser->within( new DateTimePicker('end_time_0'), function($browser) {
                $browser->setDatetime(10,14);
            })->pause(250);
            $browser->press('#createBookingRequest')->waitForRoute('bookings.create');

            $browser->scrollTo('#files');
            $browser->check('#bake-sale-checkbox');
            $browser->waitForText('Please remember to attach a filled out "Bake Sale Form" to the document section');

            $browser->check('#terms-and-conditions');
            $browser->press('SUBMIT');
            $browser->waitForText('The files field is required when event.bake sale is true.');
        });
    }

    /**
     * assert page has unique elements for Conference rooms
     * #313 -> AT-255-3
     * @test
     */
    public function testUserCanFillBookingAttributesForConferenceRooms()
    {
        $this->browse(function (Browser $browser) {
            $user = $this->createUserWithPermissions(['bookings.create']);
            $browser->loginAs($user)->visitRoute('bookings.search');

            $room = Room::where('room_type', 'Conference')->firstOrFail();
            $room->min_days_advance = 0;
            $room->max_days_advance = 10000;
            $room->save();

            $browser->press("@room-select-".$room->id);
            $browser->mouseover('#add-recurences-button');

            $browser->within( new DateTimePicker('start_time_0'), function($browser) {
                $browser->setDatetime(10,13);
            })->pause(250);
            $browser->within( new DateTimePicker('end_time_0'), function($browser) {
                $browser->setDatetime(10,14);
            })->pause(250);
            $browser->press('#createBookingRequest')->waitForRoute('bookings.create');

            $browser->scrollTo('#files');
            $browser->check('#internal-meeting-checkbox');
            $browser->waitForText('Yes');
        });
    }

}
