<?php

namespace Tests\Browser\Components;

use Illuminate\Support\Carbon;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class DateTimePicker extends BaseComponent
{

    public $id;

    public function __construct($id)
    {
        $this->id = $id;
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
            '@input' => "#$this->id-input",
            '@controls' => "#$this->id-picker-container-DatePicker > .calendar > .datepicker-controls",
            '@year-button' => "#$this->id-picker-container-DatePicker > .calendar > .datepicker-controls > .datepicker-container-label > span:nth-child(2) > button",
            '@month-button' => "#$this->id-picker-container-DatePicker > .calendar > .datepicker-controls > .datepicker-container-label > span:nth-child(1) > button",
            '@month-left' => "#$this->id-picker-container-DatePicker > .calendar > .datepicker-controls > div:nth-child(1) > button",
            '@month-right' => "#$this->id-picker-container-DatePicker > .calendar > .datepicker-controls > div:nth-child(3) > button",
            '@year-month-container' => "#$this->id-picker-container-DatePicker > .calendar > .year-month-selector > div:nth-child(2)",
            '@day-container' => "#$this->id-picker-container-DatePicker > .calendar > .month-container > span > .datepicker-days",
            '@hours-column' => ".time-picker-column-hours",
            '@minutes-column' => ".time-picker-column-minutes",
        ];
    }

    public function setDatetime(Browser $browser, int $daysFromNow, int $hour)
    {
        $temp = Carbon::now()->addDays($daysFromNow)->setTime($hour, 0);
        $day2 = $temp->day;
        $hour = $temp->hour + 2;
        $day1 = $temp->firstOfMonth()->dayOfWeek;
        $day = $day1 + $day2;

        $this->openPicker($browser);
        if ($temp->isNextMonth()) {
            $browser->click('@month-right')->pause(500);
        }
        $browser->click('#' . $this->id . '-picker-container-DatePicker > div > div.month-container > span > div > button:nth-child(' . $day . ')');
        $browser
            ->click('> div.datetimepicker.flex.visible > div > div.pickers-container.flex > div.time-picker.flex.flex-fixed.flex-1.with-border > div.time-picker-column.flex-1.flex.flex-direction-column.text-center.time-picker-column-hours > div > button:nth-child(' . $hour . ')')
            ->click('> div.datetimepicker.flex.visible > div > div.pickers-container.flex > div.time-picker.flex.flex-fixed.flex-1.with-border > div.time-picker-column.flex-1.flex.flex-direction-column.text-center.time-picker-column-minutes > div > button:nth-child(2)')
            ->click('> div.datetimepicker.flex.visible > div > div.datepicker-buttons-container.flex.justify-content-right.button-validate.flex-fixed > button.datepicker-button.validate.flex.align-center.justify-content-center');
    }

    /**
     * Select the given date.
     *
     * @param  \Laravel\Dusk\Browser  $browser
     * @param  mixed  $date
     * @return void
     */
    public function selectDate(Browser $browser, $date)
    {
        $date = \Illuminate\Support\Carbon::parse($date);

        $this->openPicker($browser);

        $this->selectYear($browser, $date);
        $browser->pause(250);
        $this->selectMonth($browser, $date);
        $browser->pause(250);
        $this->selectDay($browser, $date);
        $browser->pause(250);
    }

    /**
     * Opens the date picker
     *
     * @param  \Laravel\Dusk\Browser  $browser
     * @return void
     */
    public function openPicker(Browser $browser) {
        $browser->click('@input');
    }

    /**
     * Select the given year
     *
     * @param  \Laravel\Dusk\Browser  $browser
     * @param  mixed  $date
     * @return void
     */
    public function selectYear(Browser $browser, $date) {
        $year = ($date instanceof Carbon) ? $date->year : $date;

        $browser->click('@year-button');
        $browser->within('@year-month-container', function(Browser $browser) use ($year) {
            $browser->press($year);
        });
    }

    /**
     * Select the given month
     *
     * @param  \Laravel\Dusk\Browser  $browser
     * @param  mixed  $date
     * @return void
     */
    public function selectMonth(Browser $browser, $date) {
        $month = ($date instanceof Carbon) ? $date->shortEnglishMonth : $date;

        $browser->click('@month-button');
        $browser->within('@year-month-container', function(Browser $browser) use ($month) {
            $browser->press($month);
        });
    }

    /**
     * Select the given day
     *
     * @param  \Laravel\Dusk\Browser  $browser
     * @param  mixed  $date
     * @return void
     */
    public function selectDay(Browser $browser, $date) {
        $day = ($date instanceof Carbon) ? $date->day : $date;

        $browser->within('@day-container', function ($browser) use ($day) {
            $browser->press($day);
        });
    }
}
