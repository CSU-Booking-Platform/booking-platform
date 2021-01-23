import {beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import AppLayout from "@src/Layouts/AppLayout";
import moxios from 'moxios';

let localVue

beforeEach(() => {
    moxios.install()

    localVue = createLocalVue()
    localVue.use(InertiaApp)
    localVue.use(InertiaForm)

});

test('should mount without crashing', () => {
    const wrapper = shallowMount(AppLayout, {localVue})
})

test('should mount without crashing', () => {
    // Match against an exact URL value
    moxios.stubRequest('/logout', {
        status: 200,
        responseText: 'hello'
    })

    const wrapper = shallowMount(AppLayout, {localVue})
    wrapper.vm.logout()

})
