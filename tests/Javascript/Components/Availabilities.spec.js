import { beforeEach, jest, test } from "@jest/globals";

jest.mock('laravel-jetstream')

import { createLocalVue, mount, shallowMount } from '@vue/test-utils'
import { InertiaApp } from '@inertiajs/inertia-vue'
import { InertiaForm } from 'laravel-jetstream'
import Availabilities from "@src/Components/Availabilities";

let localVue
let wrapper

beforeEach(() => {
    localVue = createLocalVue()
    localVue.use(InertiaApp)
    localVue.use(InertiaForm)

    wrapper = shallowMount(Availabilities, {
        localVue, propsData: {
            rooms: [{
                id: 1,
                name: "name",
                building: "building",
                number: "1",
                floor: 1,
                status: "available",
                room_type: "Mezzanine",
                blackouts: [
                    {
                        "create_at": "2021-01-29T12:20:00.000000Z",
                        "end_time": "2021-01-31T04:20:00.000000Z"
                    }

                ],
                availabilities: [
                    {
                        "opening_hours": "09:18:00",
                        "closing_hours": "21:19:00",
                        "weekday": "Monday"
                    },
                    {
                        "opening_hours": "09:18:00",
                        "closing_hours": "21:19:00",
                        "weekday": "Tuesday"
                    },
                    {
                        "opening_hours": "09:18:00",
                        "closing_hours": "21:19:00",
                        "weekday": "Wednesday"
                    },
                    {
                        "opening_hours": "09:18:00",
                        "closing_hours": "21:19:00",
                        "weekday": "Thursday"
                    },
                    {
                        "opening_hours": "09:18:00",
                        "closing_hours": "21:19:00",
                        "weekday": "Friday"
                    },
                    {
                        "opening_hours": "09:18:00",
                        "closing_hours": "21:19:00",
                        "weekday": "Saturday"
                    },
                    {
                        "opening_hours": "09:18:00",
                        "closing_hours": "21:19:00",
                        "weekday": "Sunday"
                    }
                ],
            }]
        }, data() {
            return {
                availabilities: [
                    {
                        "opening_hours": "09:18:00",
                        "closing_hours": "21:19:00",
                        "weekday": "Monday"
                    },
                    {
                        "opening_hours": "09:18:00",
                        "closing_hours": "21:19:00",
                        "weekday": "Tuesday"
                    },
                    {
                        "opening_hours": "09:18:00",
                        "closing_hours": "21:19:00",
                        "weekday": "Wednesday"
                    },
                    {
                        "opening_hours": "09:18:00",
                        "closing_hours": "21:19:00",
                        "weekday": "Thursday"
                    },
                    {
                        "opening_hours": "09:18:00",
                        "closing_hours": "21:19:00",
                        "weekday": "Friday"
                    },
                    {
                        "opening_hours": "09:18:00",
                        "closing_hours": "21:19:00",
                        "weekday": "Saturday"
                    },
                    {
                        "opening_hours": "09:18:00",
                        "closing_hours": "21:19:00",
                        "weekday": "Sunday"
                    }
                ],
                days: [{
                    id: "2021-02-01",
                    date: "Monday, February 1, 2021"
                }],
                blackouts: [{
                    "create_at": "2021-01-29T12:20:00.000000Z",
                    "end_time": "2021-01-31T04:20:00.000000Z"
                }],
                reservations: [{
                    "start_time": "2021-02-01T15:41:00.000000Z",
                    "end_time": "2021-02-01T15:46:00.000000Z"
                }]
            }
        }
    })
});

test('Select date should update attributes', () => {
    let day = {
        id: "2021-02-02",
        date: new Date("Monday, February 1, 2021")
    }

    wrapper.vm.onDayClick(day);

    wrapper.vm.$nextTick(() => {
        expect(wrapper.vm.days[0]).toBe(day);
    })
});

test('should format date correctly', () => {
    expect(wrapper.vm.formatAvailability('07:50:15')).toBe('07:50');
})
