import {beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import Index from '@src/Pages/Admin/Roles/Index'
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
    const wrapper = shallowMount(Index, {localVue})
})

test('deleteRole()', () => {

    let mockRoleBeingDeleted = {
        id: 69
    }

    InertiaFormMock.delete.mockReturnValueOnce({
        then(callback) {
            callback({})
        }
    })

    const wrapper = shallowMount(Index, {
        localVue,
        data() {
            return {
                roleBeingDeleted: mockRoleBeingDeleted
            }
        }
    })

    wrapper.vm.deleteRole()

    expect(InertiaFormMock.delete).toBeCalledWith('/roles/' + mockRoleBeingDeleted.id, {
        preserveScroll: true,
        preserveState: true,
    })

    expect(wrapper.vm.$data.roleBeingDeleted).toBe(null)
})

test('fromNow(timestamp)', () => {
    //TODO: Build moment mock and test this better

    const input = '2020-05-10T05:05:00.00Z'

    const wrapper = shallowMount(Index, {localVue})

    const result = wrapper.vm.fromNow(input)
    expect(result).toBe(moment(input).local().fromNow())

})
