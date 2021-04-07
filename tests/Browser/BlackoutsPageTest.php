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

            $browser->type('#name', 'alex')
                ->press('#submit')
                ->pause(8000);

                $browser->assertSee('alex');
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
