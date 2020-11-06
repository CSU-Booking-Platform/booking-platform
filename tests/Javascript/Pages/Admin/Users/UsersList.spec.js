import {beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
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
    const wrapper = shallowMount(UsersList, {localVue})
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

    expect(InertiaFormMock.delete).toBeCalledWith('/users/' + mockUserBeingDeleted.id, {
        preserveScroll: true,
        preserveState: true,
    })

    expect(wrapper.vm.$data.userBeingDeleted).toBe(null)
})

test('fromNow(timestamp)', () => {
    //TODO::add function to base vue component and only test once
    const input = '2020-05-10T05:05:00.00Z'
    const wrapper = shallowMount(UsersList, {localVue})
    const result = wrapper.vm.fromNow(input)
    expect(result).toBe(moment(input).local().fromNow())
})
