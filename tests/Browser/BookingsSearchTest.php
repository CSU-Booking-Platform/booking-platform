<?php

namespace Tests\Browser;

use App\Models\Availability;
use App\Models\Room;
use Tests\Browser\Components\DateTimePicker;
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

    public function testUserCanViewRoomsAvailabilitiesCalender()
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
                ->press('VIEW DETAILS')
                ->waitForText('Detailed View of')
                ->assertSee('07:00')
                ->assertSee('23:00');
        });
    }


    /**
     * #580 -> AT-482
     * @test
     */
    public function testUserSearchForRoomsWithACalendarView()
    {
        $this->browse(function (Browser $browser) {
            $room = Room::factory()->create();

            $a1 = Availability::create([
                'weekday' => today()->englishDayOfWeek,
                'opening_hours' => '07:00',
                'closing_hours' => '08:00',
                'room_id' => $room->id
            ]);

            $browser->loginAs($this->createUserWithPermissions(['bookings.create']));
            $browser->visit('/bookings/search');
            $browser->clickLink('Calendar View');

            // verify that the loaded content is for today
            $browser->waitUntilVue('calendarRooms[0].name', $room->name, '@calendar-view-table');
            $browser->waitUntilVue('calendarRooms[0].availabilities[0].id', $a1->id, '@calendar-view-table');
            $browser->assertVue('dateSelected', today()->toDateString(), '@calendar-view-table');
            $browser->assertVue('calendarRooms[0].name', $room->name, '@calendar-view-table');

            // verify calendar hours navigation is functional
            $leftDelimiter = 8;
            $rightDelimiter = 21;
            $browser->assertVue('leftHourDelimiter', $leftDelimiter, '@calendar-view-table');
            $browser->assertVue('rightHourDelimiter', $rightDelimiter, '@calendar-view-table');
            $browser->click('@hours-left');
            $browser->waitUntilVue('leftHourDelimiter', $leftDelimiter - 1,'@calendar-view-table');
            $browser->click('@hours-left');
            $browser->waitUntilVue('leftHourDelimiter', $leftDelimiter - 2,'@calendar-view-table');
            $browser->assertVue('rightHourDelimiter', $rightDelimiter - 2,'@calendar-view-table');

            // verify that today's breakdown matches the expected availability
            // closed = outside availability hours or during a blackout period
            $browser->assertVue('calendarRooms[0].day_breakdown[7][0].closed', false, '@calendar-view-table');
            $browser->assertVue('calendarRooms[0].day_breakdown[6][0].closed', true, '@calendar-view-table');
            $browser->assertVue('calendarRooms[0].day_breakdown[8][0].closed', true, '@calendar-view-table');

            // setup different availabilities for tomorrow
            $tomorrow = today()->addDay();
            $a2 = Availability::create([
                'weekday' => $tomorrow->englishDayOfWeek,
                'opening_hours' => '09:00',
                'closing_hours' => '10:00',
                'room_id' => $room->id
            ]);

            $browser->within(new DateTimePicker('date_selected'), function($browser) use ($tomorrow) {
                $browser->selectDate($tomorrow);
            });
            // date should change to reflect tomorrow's availabilities
            $browser->waitUntilVue('dateSelected', $tomorrow->toDateString(), '@calendar-view-table');
            $browser->waitUntilVue('calendarRooms[0].availabilities[0].id', $a2->id, '@calendar-view-table');
            // verify that tomorrow's breakdown matches the expected availability
            // closed = outside availability hours or during a blackout period
            $browser->assertVue('calendarRooms[0].day_breakdown[7][0].closed', true, '@calendar-view-table');
            $browser->assertVue('calendarRooms[0].day_breakdown[8][0].closed', true, '@calendar-view-table');
            $browser->assertVue('calendarRooms[0].day_breakdown[7][0].closed', true, '@calendar-view-table');
            $browser->assertVue('calendarRooms[0].day_breakdown[8][0].closed', true, '@calendar-view-table');
            $browser->assertVue('calendarRooms[0].day_breakdown[9][0].closed', false, '@calendar-view-table');
            $browser->assertVue('calendarRooms[0].day_breakdown[10][0].closed', true, '@calendar-view-table');
        });
    }
}
