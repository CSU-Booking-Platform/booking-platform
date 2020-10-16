import {afterEach, beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')
import {createLocalVue, mount} from '@vue/test-utils'
import {InertiaApp} from "@inertiajs/inertia-vue";
import {InertiaForm} from "laravel-jetstream";
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import CreateRoleForm from "@src/Pages/Admin/Roles/CreateRoleForm";

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

//This is an example, please do more than just test if it crashes :p
test('should mount without crashing', () => {

    const wrapper = mount(CreateRoleForm, {localVue})

    expect(wrapper.text()).toBeDefined()
})

//This is an example, please do more than just test if it crashes :p
test('createPermission when no form errors', () => {

    InertiaFormMock.post.mockReturnValueOnce({
        then(callback) {
            callback({})
        }
    })

    InertiaFormMock.hasErrors.mockReturnValueOnce(false)

    const wrapper = mount(CreateRoleForm, {localVue})

    wrapper.vm.createRole()

    expect(InertiaFormMock.post).toBeCalledTimes(1)

})

//This is an example, please do more than just test if it crashes :p
test('createPermission when form errors', () => {

    InertiaFormMock.post.mockReturnValueOnce({
        then(callback) {
            callback({})
        }
    })

    InertiaFormMock.hasErrors.mockReturnValueOnce(true)
    InertiaFormMock.error.mockReturnValueOnce("Some name error")

    const wrapper = mount(CreateRoleForm, {localVue})

    wrapper.vm.createRole()

    expect(InertiaFormMock.post).toBeCalledTimes(1)

})
