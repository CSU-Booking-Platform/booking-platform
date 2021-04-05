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
use Tests\Browser\Pages\Bookings\Search;
use Tests\DuskTestCase;

class FilterRoomsDuskTest extends DuskTestCase
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
     * Click on room additional filters,
     * set number and checkbox filters and assert rooms
     * go missing or stay in the search results
     */
    public function testRoomFilterByEventCriteria()
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

            $browser->press("@toggle-advanced-filters");
            $browser->assertSourceHas('<h2>Capacity Standing</h2>');

            $browser->assertSee('Art Nook');
            $browser->type('#sofas', 1);
            $browser->press("#filter-rooms");
            $browser->pause(250);
            $browser->assertSee('CSU Lounge');
            $browser->script('location.reload();');

            $browser->assertSee('Art Nook');
            $browser->press("@toggle-advanced-filters");
            $browser->check('@alcohol');
            $browser->press("#filter-rooms");
            $browser->pause(250);
            $browser->assertSee('CSU Lounge');

        });
    }

    /**
     * Click on room additional filters,
     * set number and checkbox filters and assert rooms
     * go missing or stay in the search results
     */
    public function testRoomFilterByResourceType()
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

            $browser->type("#search-rooms", "Mezzanine");
            $browser->assertSee('Art Nook');
            $browser->clear("#search-rooms");
            $browser->type("#search-rooms", "Conference");
            $browser->assertSourceMissing('Art Nook');


        });
    }

    public function testRoomFilterByResourceAvailability()
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

            $browser->press("@toggle-advanced-filters");
            $browser->assertSourceHas('<h2>Capacity Standing</h2>');

            $browser->press('#filter-add-date');

            $browser->within( new DateTimePicker('start_time-0'), function($browser) {
                $browser->setDatetime(10,13);
            })->pause(500);

            $browser->within( new DateTimePicker('end_time-0'), function($browser) {
                $browser->setDatetime(10,14);
            })->pause(250);

            $browser->press("#filter-rooms");

            $browser->assertSee('Art Nook');


        });
    }
}
