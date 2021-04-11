import {beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import Component from '@src/Pages/Requestee/BookingForm'
import moment from "moment";

let localVue
let wrapper

beforeEach(() => {
    localVue = createLocalVue()
    localVue.use(InertiaApp)
    localVue.use(InertiaForm)

    wrapper = shallowMount(Component, {localVue,
        propsData: {
            room: {
                id: 1,
                name: 'a',
                building: 'a',
                floor: 'a',
            },
            reservations: [
                { start_time: "2020-01-20T18:02", end_time: "2020-01-20T18:05" },
                { start_time: "2020-01-21T18:02", end_time: "2020-01-21T18:05" },
            ],
            bookings_general_information: {
                data: {
                    html: "okidoki"
                },
            },
            bookings_event_description: {
                data: {
                    html: "ok"
                },
            }
        },
        data() {
            return {
                accept_terms: false,
                form: {
                    onsite_contact: {},
                    event: {
                        title: '',
                        type: '',
                        description: '',
                        speakers: '',
                        attendees: '',
                        food: {
                            low_risk: false,
                            high_risk: false,
                        },
                        alcohol: false,
                        show: {},
                    },
                }
            }
        },
    });

});

afterEach(() => {
    wrapper = null;

    InertiaFormMock.error.mockClear()
    InertiaFormMock.post.mockClear()
});

test('should mount without crashing', () => {
    expect(wrapper.text()).toBeDefined();
    expect(wrapper.html()).toBeDefined();
})

test('computed reservation should match res[0]', () => {
    expect(wrapper.vm.reservation).toStrictEqual({ start_time: "2020-01-20T18:02", end_time: "2020-01-20T18:05" });
})

test('computed isRecurring should be true based on given data', () => {
    expect(wrapper.vm.isRecurring).toBe(true);
})

test('computed minStart and maxEnd should be true based on given data', () => {
    expect(wrapper.vm.minStart).toBe(moment("2020-01-20T18:02").format("HH:mm"));
    expect(wrapper.vm.maxEnd).toBe(moment("2020-01-20T18:05").format("HH:mm"));
})

test('uploads can be set via a method', () => {
    wrapper.vm.uploadedFiles([]);

    expect(wrapper.vm.form.files).toStrictEqual([]);
})

test('disabling question forms will save their state in the form', () => {
    wrapper.vm.form.event.show = {contact:false, fee: false, music: false}

    wrapper.vm.toggleNullableForms();

    expect(wrapper.vm.form.event.show).toStrictEqual({contact:false, fee: false, music: false});
})

test('setLocalIsCreating correctly sets value', () => {
    wrapper.vm.setLocalIsCreating(true);
    expect(localStorage.isCreatingBooking).toBe("true");

    wrapper.vm.setLocalIsCreating(false);
    expect(localStorage.isCreatingBooking).toBe("false");
})

test('should not submit with form errors', () => {
    InertiaFormMock.post.mockReturnValueOnce({
        then(callback) {
            callback({})
        }
    })

    InertiaFormMock.hasErrors.mockReturnValueOnce(true)
    InertiaFormMock.error.mockReturnValueOnce("Some name error")

    wrapper.vm.submitBooking()

    expect(InertiaFormMock.post).toBeCalledTimes(1)
})
