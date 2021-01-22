import {beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import FormSectionImage from "@src/Components/FormSectionImage";

let localVue

beforeEach(() => {
    InertiaFormMock.error.mockClear()
    InertiaFormMock.post.mockClear()
    InertiaFormMock.delete.mockClear()

    localVue = createLocalVue()
    localVue.use(InertiaApp)
    localVue.use(InertiaForm)

});

test('should mount without crashing', () => {
    const wrapper = shallowMount(FormSectionImage, {localVue})
})
