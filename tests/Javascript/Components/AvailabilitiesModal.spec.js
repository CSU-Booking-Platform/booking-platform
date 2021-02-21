import { beforeEach, jest, test } from "@jest/globals";

jest.mock('laravel-jetstream')

import { createLocalVue, mount, shallowMount } from '@vue/test-utils'
import { InertiaApp } from '@inertiajs/inertia-vue'
import { InertiaForm } from 'laravel-jetstream'
import AvailabilitiesModal from "@src/Components/AvailabilitiesModal";

let localVue
let wrapper

beforeEach(() => {
    localVue = createLocalVue()
    localVue.use(InertiaApp)
    localVue.use(InertiaForm)
});

test('should mount without crashing', () => {
    wrapper = shallowMount(AvailabilitiesModal, { localVue })
})