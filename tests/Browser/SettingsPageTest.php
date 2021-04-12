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

class SettingsPageTest extends DuskTestCase
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



    public function testSettingsAdminCanChangeSiteName()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::first())->visit('/admin/settings')
                ->assertSourceHas('<title>CSU Booking Platform</title>')
                ->assertSee('Settings')
                ->assertSee('Application Name')
                ->type('app_name', 'New Name')
                ->pressAndWaitFor('UPDATE APPLICATION NAME', 3)
                ->refresh()->pause(3000)
                ->assertSourceHas('<title>New Name</title>')
                ->assertSee('New Name');
        });
    }

    public function testSettingsAdminCanChangeSiteLogo()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::first())->visit('/admin/settings')
                ->assertSourceHas('<a href="/dashboard"><img src="' . config('app.logo'))
                ->assertSee('Settings')
                ->assertSee('Application Logo')
                ->attach('app_logo', __DIR__ . '/test.jpg')
                ->pressAndWaitFor('UPDATE APPLICATION LOGO', 3)
                ->refresh()->pause(3000)
                ->assertSourceHas('<a href="/dashboard"><img src="' . url('/storage/logos/'));
        });
    }

    public function testAdminCanEditYearlyAcademicDates()
    {
        AcademicDate::create([
            'semester' => 'Fall',
            'start_date' => '2020-01-01',
            'end_date' => '2020-02-02'
        ]);

        $this->browse(function (Browser $browser) {
            $startDate = Carbon::parse('2020-03-03');
            $endDate = Carbon::parse('2020-04-04');


            $browser->loginAs(User::first())->visit('/admin/settings');
            $browser->scrollIntoView('#Fall_submit_button');

                $browser->within(new DateTimePicker('Fall_start_date'), function($browser) use ($startDate) {
                    $browser->selectDate($startDate);


                })->pause(250);
                
                $browser->within(new DateTimePicker('Fall_end_date'), function($browser) use ($endDate) {
                    $browser->selectDate($endDate);
                })->pause(250);

                $browser->press('#Fall_submit_button')
                ->pause(1000);
        });

        $this->assertDatabaseMissing('academic_dates', [
            'semester' => 'Fall',
            'start_date' => '2020-01-01',
            'end_date' => '2020-02-02'
        ]);
        $this->assertDatabaseHas('academic_dates', [
            'semester' => 'Fall',
            'start_date' => '2020-03-03',
            'end_date' => '2020-04-04'
        ]);
    }

    public function testAdminCanCreateCivicHolidays()
    {
        $numberOfRoomToCreate = 5;
        Room::factory()->count($numberOfRoomToCreate)->create();

        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::first())->visit('/admin/settings');
            $browser->scrollTo('#civic_start_date-wrapper');
            $browser->within(new DateTimePicker("civic_start_date"), function ($browser) {
                $browser->setDatetime(10, 13);
            })->pause(1000);

            $browser->within(new DateTimePicker("civic_end_date"), function ($browser) {
                $browser->setDatetime(10, 14);
            })->pause(1000);

            $browser->press('#civic_submit_button')
                ->pause(3000);
        });

        $this->assertDatabaseCount('blackouts', $numberOfRoomToCreate);
    }
}
