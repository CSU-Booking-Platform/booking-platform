import { beforeEach, expect, jest, test } from "@jest/globals";
import { createLocalVue, shallowMount } from '@vue/test-utils'
import { InertiaApp } from '@inertiajs/inertia-vue'
import { InertiaForm } from 'laravel-jetstream'
import BookingInformations from "@src/Pages/Admin/Settings/BookingInformations";

jest.mock('laravel-jetstream')

let localVue
let wrapper

beforeEach(() => {
    localVue = createLocalVue()
    localVue.use(InertiaApp)
    localVue.use(InertiaForm)
    wrapper = shallowMount(BookingInformations, {
        localVue,
        propsData: {
            general_information: {
                data: {
                    html: "okidoki"
                },
            },
            event_description: {
                data: {
                    html: "ok"
                },
            }
        }
    });
});

afterEach(() => {
    wrapper = null;
});

test('can send axios request without crashing', () => {
    wrapper.vm.onBookingsGeneralInformationSave('1');
    wrapper.vm.onBookingsEventDescriptionSave('2');
})