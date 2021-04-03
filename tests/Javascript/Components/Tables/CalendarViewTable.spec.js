import { beforeEach, expect, jest, test } from "@jest/globals";

jest.mock('laravel-jetstream')

import { createLocalVue, mount, shallowMount } from '@vue/test-utils'
import { InertiaApp } from '@inertiajs/inertia-vue'
import { InertiaForm } from 'laravel-jetstream'
import CalendarViewTable from "@src/Components/Tables/CalendarViewTable";

let localVue
let wrapper

beforeEach(() => {
    localVue = createLocalVue()
    localVue.use(InertiaApp)
    localVue.use(InertiaForm)

    wrapper = shallowMount(CalendarViewTable, {
        localVue, propsData: {
        }, data() {
            return {
                dateSelected: '',
                leftHourDelimiter: 8,
                rightHourDelimiter: 21,
                calendarRooms: [
                    {
                        name: 'Art Nook',
                        id: 1,
                        color_breakdown: [
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "booked"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "booked"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "booked"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "blackout"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "blackout"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "blackout"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "available"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "available"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ],
                            [
                                {
                                    start_time: "00:00",
                                    end_time: "00:15",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:15",
                                    end_time: "00:30",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:30",
                                    end_time: "00:45",
                                    status: "booked"
                                },
                                {
                                    start_time: "00:45",
                                    end_time: "01:00",
                                    status: "blackout"
                                }
                            ]
                        ],
                    }
                ],
                dailyHours:
                    ['00:00', '1:00', '2:00', '3:00', '4:00', '5:00', '6:00', '7:00', '8:00', '9:00', '10:00', '11:00',
                        '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00', '24:00']
            }
        }
    })
});

test('can send axios request without crashing', () => {
    wrapper.vm.getCalendarRooms();
});

test('Select date should update attributes', () => {
    let currentLeft = wrapper.vm.leftHourDelimiter;
    let currentRight = wrapper.vm.rightHourDelimiter;

    wrapper.vm.bumpCalendar('right');
    expect(wrapper.vm.leftHourDelimiter).toBe(currentLeft + 1);
    expect(wrapper.vm.rightHourDelimiter).toBe(currentRight + 1);

    wrapper.vm.bumpCalendar('left');
    expect(wrapper.vm.leftHourDelimiter).toBe(currentLeft);
    expect(wrapper.vm.rightHourDelimiter).toBe(currentRight);
});