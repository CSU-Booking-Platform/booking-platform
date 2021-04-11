import {beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import Component from '@src/Components/InitialReservationDetails'
import moment from "moment";

let localVue
let wrapper

beforeEach(() => {
    localVue = createLocalVue()
    localVue.use(InertiaApp)
    localVue.use(InertiaForm)

    wrapper = shallowMount(Component, {localVue,
        propsData: {
            reservation: { start_time: "2020-01-20T18:02", end_time: "2020-01-20T18:05" },
        },
    });

});

afterEach(() => {
    wrapper = null;
});

test('should mount without crashing', () => {
    expect(wrapper.text()).toBeDefined();
    expect(wrapper.html()).toBeDefined();
})

test('filter returns date', () => {

    expect(wrapper.vm.only_date(moment())).toBe(moment().format("dddd, Do MMMM YYYY"));
})

test('filter returns time', () => {

    expect(wrapper.vm.only_time(moment())).toBe(moment().format("LT"));
})
