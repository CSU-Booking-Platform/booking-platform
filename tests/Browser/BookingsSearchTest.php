<?php

namespace Tests\Browser;

use App\Models\Availability;
use App\Models\Room;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;

class BookingsSearchTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testUserCanViewRooms()
    {
        Room::factory()->count(5)->create();
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->createUserWithPermissions(['bookings.create']));
            $browser->visit('/bookings/search');
            $browser->assertSee(Room::first()->name);
        });
    }

    public function testUserCanViewRoomsAvailabilities()
    {
        $room = Room::factory()->create();
        $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($weekdays as $weekday) {
            Availability::create([
                'weekday' => $weekday,
                'opening_hours' => '07:00',
                'closing_hours' => '23:00',
                'room_id' => $room->id
            ]);
        }

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->createUserWithPermissions(['bookings.create']))
                ->visit('/bookings/search')
                ->press('#create')
                ->waitForText('Availabilities')
                ->assertSee('07:00')
                ->assertSee('23:00')
                ->assertVisible('#availabilities-calendar');
        });
    }
}
