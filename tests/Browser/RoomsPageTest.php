<?php

namespace Tests\Browser;

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
  public function testWhenCreateNewRoomWithValidInformation()
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
  public function testWhenUpdateNewRoomWithValidInformation()
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
  public function testWhenDeleteRoom()
  {
    (new RolesAndPermissionsSeeder())->run();

    $room = Room::factory()->create();
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
}
