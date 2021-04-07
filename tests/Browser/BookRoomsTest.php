<?php

namespace Tests\Browser;

use App\Models\Room;
use App\Models\User;
use Database\Seeders\EasyUserSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\DateTimePicker;
use Tests\DuskTestCase;
use App\Models\Blackout;

class BookRoomsTest extends DuskTestCase
{
    use DatabaseMigrations;


    public function setUp(): void
    {
        parent::setUp();
        $seeder = new EasyUserSeeder();
        $seeder->run();
        $seeder = new RolesAndPermissionsSeeder();
        $seeder->run();
        $seeder = new RoomSeeder();
        $seeder->run();
        User::first()->assignRole('super-admin');

    }


    /**
     * Create booking at available time,
     * assert that user redirected to creation page
     */
    public function testBookingRoomRequiresFillingOutAForm()
    {
        $this->browse(function (Browser $browser){

            $admin = User::first();

            if($admin === null) {

                $admin = User::factory()->create();
                $admin->assignRole('super-admin');
            }

            $browser->loginAs($admin);
            $browser->visit('/bookings/search');

            $browser
                ->assertSourceHas('<title>CSU Booking Platform</title>');

            $room = Room::inRandomOrder()->first();

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

            $browser->press('#createBookingRequest');

            $browser->waitForLocation('/bookings/create');

            // Fill out necessary booking request fields with fake data
            $browser->type('#event_title', 'My Event')
                ->pause(250)
                ->type('#event_type', 'Party')
                ->pause(250)
                ->type('#event_description', 'Description')
                ->pause(250)
                ->type('#guest_speakers', 'None')
                ->pause(250)
                ->type('#attendees', 3)
                ->pause(250);

            $browser->mouseover('#submit-booking-create');
            $browser->check('#terms-and-conditions');
            $browser->script("document.getElementById('submit-booking-create').scrollIntoView()");
            $browser->press('#submit-booking-create');

            $browser->waitForLocation('/bookings');
            $browser->assertSee($room->name);



        });
    }

    /**
     * Create booking at unavailable time,
     * assert that error message shows up
     */
    public function testCannotBookUnavailableTime()
    {
        $this->browse(function (Browser $browser){

            $admin = User::first();

            if($admin === null) {

                $admin = User::factory()->create();
                $admin->assignRole('super-admin');
            }

            $browser->loginAs($admin);
            $browser->visit('/bookings/search');

            $browser
                ->assertSourceHas('<title>CSU Booking Platform</title>');

            $room = Room::inRandomOrder()->first();

            $browser->press("@room-select-".$room->id);

            $browser->mouseover('#add-recurences-button');

            $browser->within( new DateTimePicker('start_time_0'), function($browser) {
                $browser->setDatetime(10,02);
            })->pause(250);

            $browser->within( new DateTimePicker('end_time_0'), function($browser) {
                $browser->setDatetime(10,03);
            })->pause(250);

            $browser->pressAndWaitFor('#createBookingRequest');

            $browser->assertSee('These dates and times are not within the room\'s availabilities!');
        });
    }

     /**
     * Create a blackout, and then attempts to book a room during that time
     * assert that the page doesn't change to the booking creation. 
     */
    public function testCannotBookDuringBlackout()
    {
        $this->browse(function (Browser $browser){

            $admin = User::first();
            $blackout = Blackout::factory()->create();

            if($admin === null) {

                $admin = User::factory()->create();
                $admin->assignRole('super-admin');
            }

            $browser->loginAs($admin);
            $browser->visit('/bookings/search');

            $browser
                ->assertSourceHas('<title>CSU Booking Platform</title>');

            $room = Room::inRandomOrder()->first();

            $browser->press("@room-select-".$room->id);

            $browser->mouseover('#add-recurences-button');

            $browser->within( new DateTimePicker('start_time_0'), function($browser) {
                $browser->setDatetime(10,02);
            })->pause(250);

            $browser->within( new DateTimePicker('end_time_0'), function($browser) {
                $browser->setDatetime(10,03);
            })->pause(250);

            $browser->pressAndWaitFor('#createBookingRequest');

            $browser->assertSee('Create a booking request');
        });
    }

    /**
     * Create booking and check off bake sale,
     * assert that bake sale form prompt pops up,
     * attach file and assert it is visible
     */
    public function testPromptedToDownloadFormAndAttach()
    {
        $this->browse(function (Browser $browser){

            $admin = User::first();

            if($admin === null) {

                $admin = User::factory()->create();
                $admin->assignRole('super-admin');
            }

            $browser->loginAs($admin);
            $browser->visit('/bookings/search');

            $browser
                ->assertSourceHas('<title>CSU Booking Platform</title>');

            $room = Room::where('name', 'Art Nook')->firstOrFail();

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

            $browser->press('#createBookingRequest')
                ->waitForLocation('/bookings/create');

            $browser->type('#event_title', 'My Event')
                ->pause(250)
                ->type('#event_type', 'Party')
                ->pause(250)
                ->type('#event_description', 'Description')
                ->pause(250)
                ->type('#guest_speakers', 'None')
                ->pause(250)
                ->type('#attendees', 3)
                ->pause(250);

            $browser->mouseover('#submit-booking-create')
                ->check("#bake-sale-checkbox")
                ->assertSee('Please remember to attach a filled out "Bake Sale Form" to the document section')
                ->attach('#files', __DIR__ . '/test.jpg')
                ->assertSee('test.jpg');

            $browser->mouseover('#submit-booking-create')
                ->check('#terms-and-conditions')
                ->script("document.getElementById('submit-booking-create').scrollIntoView()");

            $browser->press('#submit-booking-create');

            $browser->waitForLocation('/bookings');
            $browser->assertSee($room->name);
        });
    }

}
