<?php

namespace Tests\Browser;

use App\Models\AcademicDate;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\EasyUserSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Facebook\WebDriver\WebDriverKeys;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\DateTimePicker;
use Tests\DuskTestCase;

class BlackoutsPageTest extends DuskTestCase
{

    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $seeder = new EasyUserSeeder();
        $seeder->run();
        $seeder = new RolesAndPermissionsSeeder();
        $seeder->run();
        User::first()->assignRole('super-admin');
    }
    public function testCanCreateBlackouts()
    {
        $room = Room::factory()->create();

        AcademicDate::create([
            'semester' => 'Fall',
            'start_date' => Carbon::now()->subDays(2),
            'end_date' => Carbon::now()->addDays(5)
        ]);

        $this->browse(function (Browser $browser) use ($room) {
            $browser->loginAs(User::first())->visitRoute('admin.rooms.blackouts.index', $room);

            $name = Str::random();
            $start = today()->addDays(15)->setHour(7);
            $end = today()->addDays(15)->setHour(8);

            $browser->within(new DateTimePicker("start"), function ($browser) use ($start) {
                $browser->setDatetime(15, $start->hour);
            })->pause(1000);

            $browser->within(new DateTimePicker("end"), function ($browser) use ($end) {
                $browser->setDatetime(15, $end->hour);
            })->pause(1000);

            $browser->type('#name', $name)->press('#submit');

            $browser->waitUntilVue('blackouts[0].name', $name, '@blackout-list');
            $browser->assertVue('blackouts[0].start_time', $start->toJSON(), '@blackout-list');
            $browser->assertVue('blackouts[0].end_time', $end->toJSON(), '@blackout-list');

            $browser->assertSeeIn('@blackout-list', $start->isoFormat('LLL'));
            $browser->assertSeeIn('@blackout-list', $end->isoFormat('LLL'));

        });
    }

    public function testCanCreateRecuringBlackouts()
    {
        Room::factory()->create();

        AcademicDate::create([
            'semester' => 'Fall',
            'start_date' => Carbon::now()->subDays(2),
            'end_date' => Carbon::now()->addDays(5)
        ]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::first())->visit('/admin/rooms/1/blackouts');

            $browser->within(new DateTimePicker("start"), function ($browser) {
                $browser->setDatetime(15, 7);
            })->pause(1000);

            $browser->within(new DateTimePicker("end"), function ($browser) {
                $browser->setDatetime(15, 8);
            })->pause(1000);

            $browser->select('#recurring', 'daily')
                ->type('#name', 'apu')
                ->press('#submit')
                ->pause(8000);
        });

        $this->assertDatabaseCount('blackouts', 6);
    }
}
