<?php

namespace Tests\Browser\Components;

use Carbon\Carbon;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class DateTimePicker extends BaseComponent
{

    public $id;

    public function __construct($_id)
    {
        $this->id = $_id;
    }

    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '#' . $this->id . '-wrapper';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param Browser $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertVisible($this->selector());
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '#selector',
        ];
    }

    public function setDatetime(Browser $browser, int $daysFromNow, int $hour)
    {
        $temp = Carbon::now()->addDays($daysFromNow)->setTime($hour, 0);
        $day2 = $temp->day;
        $hour = $temp->hour + 2;
        $day1 = $temp->firstOfMonth()->dayOfWeek;
        $day = $day1 + $day2;
        $newMonth = (int)Carbon::now()->format('m') != (int)$temp->format('m');

        $browser->press('#' . $this->id . '-input');
        if ($newMonth) {
            $browser->click('#' . $this->id . '-picker-container-DatePicker > div > div.datepicker-controls.flex.align-center.justify-content-center > div.arrow-month.h-100.text-right > button')->pause(500);
        }
        $browser->click('#' . $this->id . '-picker-container-DatePicker > div > div.month-container > span > div > button:nth-child(' . $day . ')');
        $browser
            ->click('> div.datetimepicker.flex.visible > div > div.pickers-container.flex > div.time-picker.flex.flex-fixed.flex-1.with-border > div.time-picker-column.flex-1.flex.flex-direction-column.text-center.time-picker-column-hours > div > button:nth-child(' . $hour . ')')
            ->click('> div.datetimepicker.flex.visible > div > div.pickers-container.flex > div.time-picker.flex.flex-fixed.flex-1.with-border > div.time-picker-column.flex-1.flex.flex-direction-column.text-center.time-picker-column-minutes > div > button:nth-child(2)')
            ->click('> div.datetimepicker.flex.visible > div > div.datepicker-buttons-container.flex.justify-content-right.button-validate.flex-fixed > button.datepicker-button.validate.flex.align-center.justify-content-center');
    }
}
