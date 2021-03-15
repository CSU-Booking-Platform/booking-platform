import {beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import ViewBooking from '@src/Pages/Requestee/ViewBooking'

let localVue
let wrapper

beforeEach(() => {
  InertiaFormMock.error.mockClear()
  InertiaFormMock.post.mockClear()
  InertiaFormMock.delete.mockClear()

  localVue = createLocalVue()
  localVue.use(InertiaApp)
  localVue.use(InertiaForm)

  wrapper = shallowMount(ViewBooking, {localVue,
    propsData: {
      booking: {
        room: {
          id: 1,
          name: 'a',
          building: 'a',
          floor: 'a',
        },
        event: {
          name: 'a',
          type: 'a',
          description: 'a',
          start_time: "2020-01-20T18:02",
          end_time: "2020-01-20T18:05"
        },
        reservations: [
          { start_time: "2020-01-20T18:02", end_time: "2020-01-20T18:05" },
          { start_time: "2020-01-21T18:02", end_time: "2020-01-21T18:05" },
        ]
      }
    }
  });

});

afterEach(() => {
  wrapper = null
});

test('should mount without crashing', () => {
  expect(wrapper.text()).toBeDefined()
  expect(wrapper.html()).toBeDefined()
})

