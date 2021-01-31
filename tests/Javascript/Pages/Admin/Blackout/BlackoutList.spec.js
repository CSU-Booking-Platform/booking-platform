import {beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import BlackoutList from '@src/Pages/Admin/Blackouts/BlackoutList'
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
  const wrapper = shallowMount(BlackoutList, {localVue})
})

test('deleteblackout()', () => {

  let mockBlackoutBeingDeleted = {
    id: 10
  }

  InertiaFormMock.delete.mockReturnValueOnce({
    then(callback) {
      callback({})
    }
  })

  const wrapper = shallowMount(BlackoutList, {
    localVue,
    propsData: {
      room: {id: 1}
    },
    data() {
      return {
        blackoutBeingDeleted: mockBlackoutBeingDeleted
      }
    }
  })

  wrapper.vm.deleteBlackout()

  expect(InertiaFormMock.delete).toBeCalledWith('/admin/rooms/1/blackouts/' + mockBlackoutBeingDeleted.id, {
    preserveScroll: true,
    preserveState: true,
  })

  expect(wrapper.vm.$data.blackoutBeingDeleted).toBe(null)
})
