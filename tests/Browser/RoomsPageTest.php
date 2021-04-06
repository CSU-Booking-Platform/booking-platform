<?php

namespace Tests\Browser;

use App\Models\Role;
use App\Models\Room;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Rooms;
use Tests\DuskTestCase;

class RoomsPageTest extends DuskTestCase
{
  use DatabaseMigrations;

  /**
   *
   *
   * @return void
   */
  public function testCreateRoom()
  {
    (new RolesAndPermissionsSeeder())->run();

    $room = Room::factory()->make();
    $this->browse(function (Browser $browser) use ($room) {

      $admin = User::factory()->create();
      $admin->assignRole('super-admin');
      $browser->loginAs($admin);
      $browser->visit(new Rooms)
        ->createRoom($room);
      $browser->waitForText('Created', 10);

      $browser->assertSee($room->name)->assertSee($room->building);
    });

    $this->assertDatabaseCount('rooms', 1);
    $this->assertDatabaseHas('rooms', [
      'name' => $room->name, 'number' => $room->number,
      'floor' => $room->floor, 'building' => $room->building,
      'status' => $room->status, 'room_type' => $room->room_type
    ]);
  }

  public function testSetRoomAttributes()
  {
    (new RolesAndPermissionsSeeder())->run();

    $room = Room::factory()->create();
    $this->browse(function (Browser $browser) use ($room) {

      $admin = User::factory()->create();
      $admin->assignRole('super-admin');
      $browser->loginAs($admin);
      $browser->visit(new Rooms)
        ->assertSee($room->name)
        ->updateRoom($room, 'Not Ipsum');
      $browser->waitUntilMissingText('NEVERMIND');

      $browser->assertSee('Not Ipsum');
    });

    $this->assertDatabaseCount('rooms', 1);
    $this->assertDatabaseHas('rooms', [
      'name' => 'Not Ipsum',
    ]);
  }

  public function testDeleteRoom()
  {
    (new RolesAndPermissionsSeeder())->run();

    $room = Room::factory()->create(['name' => 'test-room-name-test']);
    $this->browse(function (Browser $browser) use ($room) {

      $admin = User::factory()->create();
      $admin->assignRole('super-admin');
      $browser->loginAs($admin);
      $browser->visit(new Rooms)
        ->assertSee($room->name)
        ->deleteRoom()
        ->waitUntilMissingText('NEVERMIND');

      $browser->assertDontSee($room->name);
    });

    $this->assertDatabaseCount('rooms', 0);
  }

  public function testSetRoomRestrictions()
  {
    (new RolesAndPermissionsSeeder())->run();

    $room = Room::factory()->create();
    $admin = User::factory()->create();
    $admin->assignRole('super-admin');
    $role = Role::where('name', 'super-admin')->first();
    $this->browse(function (Browser $browser) use ($room, $admin, $role) {


      $browser->loginAs($admin);
      $browser->visit(new Rooms)
        ->assertSee($room->name)
        ->restrictRoom($room, $role->id);
      $browser->waitUntilMissingText('NEVERMIND');
    });

    $this->assertDatabaseCount('room_restrictions', 1);
    $this->assertDatabaseHas('room_restrictions', [
      'role_id' => $role->id,
      'room_id' => $room->id
    ]);
  }

  public function testEditRoomAvailabilitiesWithValidInformation()
  {
    (new RolesAndPermissionsSeeder())->run();

    $room = Room::factory()->create();
    $this->browse(function (Browser $browser) use ($room) {

      $admin = User::factory()->create();
      $admin->assignRole('super-admin');
      $browser->loginAs($admin);
      $browser->visit(new Rooms)
        ->assertSee($room->name)
        ->press('ACTION MENU')
        ->press('Update')
        ->type('#Monday_opening_hours', '07')
        ->type('#Monday_opening_hours', '00')
        ->type('#Monday_opening_hours', 'AM')
        ->type('#Monday_closing_hours', '08')
        ->type('#Monday_closing_hours', '00')
        ->type('#Monday_closing_hours', 'AM')
        ->press('#updateRoom');
      $browser->waitUntilMissingText('NEVERMIND');
    });

    $this->assertDatabaseCount('availabilities', 1);
  }

  public function testSetDifferentTimeRestrictionPerRole()
    {
        (new RolesAndPermissionsSeeder())->run();

        $room = Room::factory()->create();
        $admin = User::factory()->create();
        $admin->assignRole('super-admin');
        $role = Role::where('name', 'super-admin')->first();
        $this->browse(function (Browser $browser) use ($room, $admin, $role) {


            $browser->loginAs($admin);
            $browser->visit(new Rooms)
                ->assertSee($room->name)
                ->restrictRoomDate($role, 100, 101);
            $browser->waitUntilMissingText('NEVERMIND');
        });

        $this->assertDatabaseCount('custom_date_restrictions', 1);
        $this->assertDatabaseHas('custom_date_restrictions', [
            'role_id' => $role->id,
            'room_id' => $room->id,
        ]);
    }


}
