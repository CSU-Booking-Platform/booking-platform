import {afterEach, beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')
import {createLocalVue, mount} from '@vue/test-utils'
import {InertiaApp} from "@inertiajs/inertia-vue";
import {InertiaForm} from "laravel-jetstream";
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import AppName from "@src/Pages/Admin/Settings/AppName";
import Settings from "@src/Pages/Admin/Settings/Index";

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

    const wrapper = mount(AppName, {localVue,
        propsData: {
            settings: {}
        }})

    expect(wrapper.text()).toBeDefined()
})


test('updateName when no form errors', () => {

    InertiaFormMock.post.mockReturnValueOnce({
        then(callback) {
            callback({})
        }
    })

    InertiaFormMock.hasErrors.mockReturnValueOnce(false)

    const wrapper = mount(AppName, {localVue,
        propsData: {
            settings: {}
        }})

    wrapper.vm.updateNameSetting()

    expect(InertiaFormMock.post).toBeCalledTimes(1)

})
