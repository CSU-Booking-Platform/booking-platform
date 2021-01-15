import {afterEach, beforeEach, jest, test} from "@jest/globals";
jest.mock('laravel-jetstream')
import {createLocalVue, shallowMount} from '@vue/test-utils'
import {InertiaApp} from "@inertiajs/inertia-vue";
import {InertiaForm} from "laravel-jetstream";
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import Subnavlink from "@src/Jetstream/SubNavLink";


let localVue;


beforeEach(() => {
    InertiaFormMock.error.mockClear()
    InertiaFormMock.post.mockClear()

    localVue = createLocalVue()
    localVue.use(InertiaApp)
    localVue.use(InertiaForm)

});
afterEach(() => {
    localVue = null
})

test('should mount without crashing', () => {

    const wrapper = shallowMount(Subnavlink, {
        localVue,
        propsData: {
                href: "link"
        }
    })

    expect(wrapper.text()).toBeDefined()
})


test('should mount without crashing and active true', () => {

    const wrapper = shallowMount(Subnavlink, {
        localVue,
        propsData: {
                href: "link",
                active: true

        }
    })

    expect(wrapper.text()).toBeDefined()
})



