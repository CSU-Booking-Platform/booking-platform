import {afterEach, beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')
import {createLocalVue, mount} from '@vue/test-utils'
import {InertiaApp} from "@inertiajs/inertia-vue";
import {InertiaForm} from "laravel-jetstream";
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import AppConfig from "@src/Pages/Admin/Settings/AppConfig";
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

    const wrapper = mount(AppConfig, {localVue,
        propsData: {
            settings: {}
        }})

    expect(wrapper.text()).toBeDefined()
})


test('updateConfig when no form errors', () => {

    InertiaFormMock.post.mockReturnValueOnce({
        then(callback) {
            callback({})
        }
    })

    InertiaFormMock.hasErrors.mockReturnValueOnce(false)

    const wrapper = mount(AppConfig, {localVue,
        propsData: {
            settings: {}
        }})

    wrapper.vm.updateConfigSetting()

    expect(InertiaFormMock.post).toBeCalledTimes(1)

})
