<?php

namespace Tests\Browser;

use App\Models\Room;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;

class BookingsSearchTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testUserCanViewRoomsAndAvailabilities()
    {
        Room::factory()->count(5)->create();
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->createUserWithPermissions(['bookings.create']));
            $browser->visit('/bookings/search');
            $browser->assertSee(Room::first()->name);
        });
    }
}
