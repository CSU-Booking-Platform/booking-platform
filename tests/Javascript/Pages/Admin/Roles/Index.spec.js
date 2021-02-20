import {beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, shallowMount} from '@vue/test-utils'
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
    shallowMount(Index, {localVue})
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

    expect(InertiaFormMock.delete).toBeCalledWith('/admin/roles/' + mockRoleBeingDeleted.id, {
        preserveScroll: true,
        preserveState: true,
    })

    expect(wrapper.vm.$data.roleBeingDeleted).toBe(null)
})
