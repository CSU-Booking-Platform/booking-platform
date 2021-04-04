<?php

namespace Tests\Browser\Pages;

use App\Models\Room;
use Laravel\Dusk\Browser;

class Rooms extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/admin/rooms';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param Browser $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
//            '@element' => '#selector',
        ];
    }

    public function createRoom(Browser $browser, Room $room)
    {

        $browser->type('#name', $room->name)
            ->type('#number', $room->number)
            ->type('#floor', $room->floor)
            ->select('#building', $room->building)
            ->click('#attributes')
            ->type('#stand_capacity', $room->attributes['capacity_standing'])
            ->type('#sit_capacity', $room->attributes['capacity_sitting'])
            ->check('#checkboxFood', $room->attributes['food'])
            ->check('#checkboxAlcohol', $room->attributes['alcohol'])
            ->check('#checkboxAV', $room->attributes['a_v_permitted'])
            ->select('#status', $room->status)
            ->select('#room_type', $room->room_type)
            ->press('#availabilities')
            ->type('#min_days_advance', $room->min_days_advance)
            ->type('#max_days_advance', $room->max_days_advance)
            ->press('#create');

    }

    /** @noinspection PhpUnusedParameterInspection */
    public function updateRoom(Browser $browser, Room $room, string $name)
    {

        $browser
            ->press('ACTION MENU')
            ->press('UPDATE')
            ->type('.vue-portal-target #name', $name)
            ->press('#updateRoom');

    }

    public function deleteRoom(Browser $browser)
    {

        $browser
            ->press('ACTION MENU')
            ->press('DELETE')
            ->press('#deleteRoom');
    }

    public function restrictRoom(Browser $browser, Room $room, string $role)
    {
        $browser
            ->press('Action')
            ->press('Restricted Roles')
            ->check('@restrict-1')
            ->press('#updateRoomRestrictions');

    }
}
