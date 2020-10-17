import {afterEach, beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')
import {createLocalVue, mount} from '@vue/test-utils'
import {InertiaApp} from "@inertiajs/inertia-vue";
import {InertiaForm} from "laravel-jetstream";
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import CreateUserForm from "@src/Pages/Admin/Users/CreateUserForm";
import AddUserPage from "@src/Pages/AddUser";

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

    const wrapper = mount(CreateUserForm, {localVue})

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

    const wrapper = mount(CreateUserForm, {localVue})

    wrapper.vm.createUser()

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

    const wrapper = mount(CreateUserForm, {localVue})

    wrapper.vm.createUser()

    expect(InertiaFormMock.post).toBeCalledTimes(1)

})
