import {beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import UsersList from '@src/Pages/Admin/Users/UsersList'
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import moment from "moment";

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
    shallowMount(UsersList, {localVue})
})

test('deleteUser()', () => {

    let mockUserBeingDeleted = {
        id: 69
    }

    InertiaFormMock.delete.mockReturnValueOnce({
        then(callback) {
            callback({})
        }
    })

    const wrapper = shallowMount(UsersList, {
        localVue,
        data() {
            return {
                userBeingDeleted: mockUserBeingDeleted
            }
        }
    })

    wrapper.vm.deleteUser()

    expect(InertiaFormMock.delete).toBeCalledWith('/admin/users/' + mockUserBeingDeleted.id, {
        preserveScroll: true,
        preserveState: true,
    })

    expect(wrapper.vm.$data.userBeingDeleted).toBe(null)
})

test('openResetModal', () => {
  let user = {
    id: 69
  }
  const wrapper = shallowMount(UsersList, {localVue})
  wrapper.vm.openResetModal(user)
  expect(wrapper.vm.$data.resetModalUser).toBe(user);
})

test('openUpdateModal', () => {
    let user = {
        roles: ["role1", "role2"]
    }
    const wrapper = shallowMount(UsersList, {localVue})
    wrapper.vm.openUpdateModal(user)
    expect(wrapper.vm.$data.userBeingUpdated).toBe(user);
})

test('updateUser()', () => {

    let mockUserBeingUpdated = {
        id: 69
    }

    InertiaFormMock.put.mockReturnValueOnce({
        then(callback) {
            callback({})
        }
    })

    const wrapper = shallowMount(UsersList, {
        localVue,
        data() {
            return {
                userBeingUpdated: mockUserBeingUpdated
            }
        }
    })

    wrapper.vm.updateUser()

    expect(InertiaFormMock.put).toBeCalledWith('/admin/users/' + mockUserBeingUpdated.id, {
        preserveScroll: true,
        preserveState: true,
    })

    expect(wrapper.vm.$data.userBeingUpdated).toBe(null)
})


test('userBeingUpdated should not be null with the request fails', () => {

    let mockUserBeingUpdated = {
        id: 69
    }

    InertiaFormMock.put.mockImplementationOnce(function () {
        InertiaFormMock.successful = false;
        return {
            then(callback) {
                callback({})
            }
        }
    })

    const wrapper = shallowMount(UsersList, {
        localVue,
        data() {
            return {
                userBeingUpdated: mockUserBeingUpdated
            }
        }
    })

    wrapper.vm.updateUser()

    expect(InertiaFormMock.put).toBeCalledWith('/admin/users/' + mockUserBeingUpdated.id, {
        preserveScroll: true,
        preserveState: true,
    })

    expect(wrapper.vm.$data.userBeingUpdated).toBe(mockUserBeingUpdated);
})

