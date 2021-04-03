<?php

namespace Tests\Browser;

use App\Models\Role;
use App\Models\Room;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\RoomSearch;
use Tests\DuskTestCase;

class SearchPageTest extends DuskTestCase
{

  use DatabaseMigrations;


    public function testWhenUpdateRoomRestrictions()
    {
        (new RolesAndPermissionsSeeder())->run();

        $room = Room::factory()->create();
        $room2 = Room::factory()->create();
        $admin = User::factory()->create();
        $admin->assignRole('super-admin');
        $role = Role::where('name', 'super-admin')->first();
        $room->restrictions()->sync($role);

        $this->browse(function (Browser $browser) use ($room, $admin, $room2) {
            $browser->loginAs($admin);
            $browser->visit(new RoomSearch)
                ->assertSee($room2->name)
                ->assertDontSee($room->name);
        });
    }

}
