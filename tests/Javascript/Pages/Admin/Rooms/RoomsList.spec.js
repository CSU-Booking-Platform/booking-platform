import {beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import RoomsList from '@src/Pages/Admin/Rooms/RoomsList'
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";

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
    const wrapper = shallowMount(RoomsList, {localVue})
})

test('deleteRoom()', () => {

    let mockRoomBeingDeleted = {
        id: 10
    }

    InertiaFormMock.delete.mockReturnValueOnce({
        then(callback) {
            callback({})
        }
    })

    const wrapper = shallowMount(RoomsList, {
        localVue,
        data() {
            return {
                roomBeingDeleted: mockRoomBeingDeleted
            }
        }
    })

    wrapper.vm.deleteRoom()

    expect(InertiaFormMock.delete).toBeCalledWith('/rooms/' + mockRoomBeingDeleted.id, {
        preserveScroll: true,
        preserveState: true,
    })

    expect(wrapper.vm.$data.roomBeingDeleted).toBe(null)
})


test('openUpdateRestrictionsModal', () => {
    let room = {
        restrictions: ["role1", "role2"]
    }
    const wrapper = shallowMount(RoomsList, {localVue})
    wrapper.vm.openEditModal(room)
    expect(wrapper.vm.$data.roomRestBeingUpdated).toBe(room);
})

test('updateRoomRestrictions()', () => {

    let mockRoomResBeingUpdated = {
        id: 10,
        roles:['role1', 'roles2']
    }

    InertiaFormMock.put.mockReturnValueOnce({
        then(callback) {
            callback({})
        }
    })

    const wrapper = shallowMount(RoomsList, {
        localVue,
        data() {
            return {
                roomRestBeingUpdated: mockRoomResBeingUpdated
            }
        }
    })

    wrapper.vm.updateRestrictions()

    expect(InertiaFormMock.put).toBeCalledWith('/room/restrictions/'  + mockRoomResBeingUpdated.id, {
        preserveScroll: true,
        preserveState: true,
    })

    expect(wrapper.vm.$data.roomRestBeingUpdated).toBe(null)
})
