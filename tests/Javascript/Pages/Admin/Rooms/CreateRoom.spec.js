import {afterEach, beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')
import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
import {InertiaApp} from "@inertiajs/inertia-vue";
import {InertiaForm} from "laravel-jetstream";
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import CreateRoomForm from "@src/Pages/Admin/Rooms/CreateRoomForm";
import Index from "@src/Pages/Admin/Rooms/Index";

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

    const wrapper = mount(CreateRoomForm, {
        localVue,
        propsData: {
            rooms: [{
                id: 1,
                name: "name",
                building: "building",
                number: "1",
                floor: 1,
                status: "available"
            }],

            availableRoomTypes: ['test']
        }
    })

    expect(wrapper.text()).toBeDefined()
})


test('createRoom when no form errors', () => {

    InertiaFormMock.post.mockReturnValueOnce({
        then(callback) {
            callback({})
        }
    })

    InertiaFormMock.hasErrors.mockReturnValueOnce(false)

    const wrapper = mount(CreateRoomForm, {
        localVue,
        propsData: {
            rooms: [{
                id: 1,
                name: "name",
                building: "building",
                number: "1",
                floor: 1,
                status: "available"
            }],

            availableRoomTypes: ['test']
        }
    })

    wrapper.vm.createRoom()

    expect(InertiaFormMock.post).toBeCalledTimes(1)

})

test('createRoom when form errors', () => {

    InertiaFormMock.post.mockReturnValueOnce({
        then(callback) {
            callback({})
        }
    })

    InertiaFormMock.hasErrors.mockReturnValueOnce(true)
    InertiaFormMock.error.mockReturnValueOnce("Some name error")

    const wrapper = mount(CreateRoomForm, {
        localVue,
        propsData: {
            rooms: [{
                id: 1,
                name: "name",
                building: "building",
                number: "1",
                floor: 1,
                status: "available"
            }],

            availableRoomTypes: ['test']
        }
    })

    wrapper.vm.createRoom()

    expect(InertiaFormMock.post).toBeCalledTimes(1)

})

test('toggle correctly toggles the boolean', () => {

    const wrapper = mount(CreateRoomForm, {
        localVue,
        propsData: {
            rooms: [{
                id: 1,
                name: "name",
                building: "building",
                number: "1",
                floor: 1,
                status: "available"
            }],

            availableRoomTypes: ['test']
        }
    })

    let preToggleBool = wrapper.vm.showAvailabilities

    wrapper.vm.toggle()

    expect(wrapper.vm.showAvailabilities).toBe(!preToggleBool)

})
