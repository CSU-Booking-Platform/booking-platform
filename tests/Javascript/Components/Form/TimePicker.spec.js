import {beforeEach, expect, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import TimePicker from '@src/Components/Form/TimePicker'
import regeneratorRuntime from "regenerator-runtime/runtime"

let localVue

beforeEach(() => {
    localVue = createLocalVue()
    localVue.use(InertiaApp)
    localVue.use(InertiaForm)

});

test('should mount without crashing', () => {
    const wrapper = shallowMount(TimePicker, {localVue});

    expect(wrapper.text()).toBeDefined();
})

test('verify computed proxy field', async () => {
    const wrapper = shallowMount(TimePicker, {localVue});

    wrapper.vm.proxyValue = '22:30';

    await wrapper.vm.$nextTick();
    expect(wrapper.emitted().change).toBeTruthy();

})
