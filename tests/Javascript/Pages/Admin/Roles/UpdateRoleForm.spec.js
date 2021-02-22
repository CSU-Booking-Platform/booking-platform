import {afterEach, beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')
import {createLocalVue, shallowMount} from '@vue/test-utils'
import {InertiaApp} from "@inertiajs/inertia-vue";
import {InertiaForm} from "laravel-jetstream";
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import UpdateRoleForm from "@src/Pages/Admin/Roles/UpdateRoleForm";

let localVue;
let wrapper;

beforeEach(() => {

    localVue = createLocalVue()
    localVue.use(InertiaApp)
    localVue.use(InertiaForm)

    wrapper = shallowMount(UpdateRoleForm, {localVue})

});
afterEach(() => {
    localVue = null
    wrapper = null

    InertiaFormMock.put.mockClear()
})

//This is an example, please do more than just test if it crashes :p
test('should mount without crashing', () => {

    expect(wrapper.text()).toBeDefined()
})

//This is an example, please do more than just test if it crashes :p
test('Update Role when no form errors', () => {

    InertiaFormMock.put.mockReturnValueOnce({
        then(callback) {
            callback({})
        }
    })

    InertiaFormMock.hasErrors.mockReturnValueOnce(false)

    wrapper.vm.updateRole()

    expect(InertiaFormMock.put).toBeCalledTimes(1)

})

//This is an example, please do more than just test if it crashes :p
test('Update Role when form errors', () => {

    InertiaFormMock.put.mockReturnValueOnce({
        then(callback) {
            callback({})
        }
    })

    InertiaFormMock.hasErrors.mockReturnValueOnce(true)
    InertiaFormMock.error.mockReturnValueOnce("Some error")

    wrapper.vm.updateRole()

    expect(InertiaFormMock.put).toBeCalledTimes(1)

})


test('Role prop watcher updates form', () => {

    const role = {name: 'role', permissions: [{name: 'a'}, {name: 'b'}]};

    wrapper.setProps({role: role})

    wrapper.vm.$nextTick(() => {
        expect(wrapper.vm.form.name).toBe(role.name)
        expect(wrapper.vm.form.permissions).toHaveLength(role.permissions.length)
        expect(wrapper.vm.form.permissions).toContain(role.permissions[0].name)
        expect(wrapper.vm.form.permissions).toContain(role.permissions[1].name)
    })
})

