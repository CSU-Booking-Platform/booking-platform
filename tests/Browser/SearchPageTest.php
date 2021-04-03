<?php

namespace Tests\Browser;

use App\Models\Availability;
use App\Models\Role;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SearchPageTest extends DuskTestCase
{

  use DatabaseMigrations;
    private array $weekdays =['Monday', 'Tuesday', 'Wednesday','Thursday','Friday','Saturday','Sunday'];


    public function testWhenUpdateRoomDateRestrictions()
    {
        (new RolesAndPermissionsSeeder())->run();
        $room = Room::factory()->create();
        $admin = User::factory()->create();
        $admin->assignRole('super-admin');
        $role = Role::where('name', 'super-admin')->first();
        $room->dateRestrictions()->sync([
            $role->id => [
                'min_days_advance' => 30,
                'max_days_advance' => 31
            ]
        ]);

        foreach ($this->weekdays as $weekday) {
            Availability::create([
                'weekday' => $weekday,
                'opening_hours' => '07:00',
                'closing_hours' => '23:00',
                'room_id' => $room->id
            ]);
        }

        $this->browse(function (Browser $browser) use ($room, $admin) {
            $browser->loginAs($admin);
            $browser->visit('/bookings/search')
                ->assertSee($room->name)
                ->click('@room-select-'.$room->id);
            $this->typeDateTime($browser, '#start_time', Carbon::now()->addDays(10)->setTime(13,0));
            $this->typeDateTime($browser, '#end_time', Carbon::now()->addDays(10)->setTime(14,0));
            $browser->click("#createBookingRequest")
            ->pause(5000)
                ->assertSee("You can not book this room later than 30 days in advance");
            $this->typeDateTime($browser, '#start_time', Carbon::now()->addDays(40)->setTime(13,0));
            $this->typeDateTime($browser, '#end_time', Carbon::now()->addDays(40)->setTime(14,0));
            $browser->click("#createBookingRequest")
                ->pause(5000)
                ->assertSee("You can not book this room sooner than 31 days in advance");
        });
    }

    public function typeDateTime(Browser $browser, $selector = 'input', $date = null)
    {
        $date = Carbon::parse($date);
        $browser->keys($selector,
            $date->format('Y'),
            ['{right}', $date->format('m')],
            $date->format('d'),
            $date->format('h'),
            $date->format('i'),
            $date->format('A'),
        );
    }



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
            $browser->visit('/bookings/search')
                ->assertSee($room2->name)
                ->assertDontSee($room->name);
        });
    }

}
