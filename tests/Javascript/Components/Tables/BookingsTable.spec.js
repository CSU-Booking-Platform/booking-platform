import {beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import BookingsTable from '@src/Components/Tables/BookingsTable'
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";

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

  const wrapper = shallowMount(BookingsTable, {
    localVue,
    propsData: {
        bookings: [{
            name: "name",
            number: "3",
            start_time: "15:00",
            end_time: "15:05",
            status: "available",
            reservations:  [{"id":1,"room_id":8,"booking_request_id":1,"start_time":"2021-01-26T05:29:15.000000Z","end_time":"2021-01-26T05:31:15.000000Z","created_at":"2021-01-26T05:29:28.000000Z","updated_at":"2021-01-26T05:29:28.000000Z","room":{"id":8,"name":"event3","number":"3","floor":3,"building":"3","created_at":"2021-01-26T05:25:15.000000Z","updated_at":"2021-01-26T05:25:15.000000Z","status":"available","min_days_advance":null,"max_days_advance":null,"attributes":{"food":false,"sofas":null,"chairs":null,"tables":null,"alcohol":false,"computer":false,"projector":false,"fundraiser":false,"television":false,"whiteboard":false,"a_v_permitted":false,"ambiant_music":false,"coffee_tables":null,"sale_for_profit":false,"capacity_sitting":null,"capacity_standing":3},"room_type":"Mezzanine"}}],
            reference:{"path":"9_1611689494_reference"}
        }]
    }
  })

  expect(wrapper.text()).toBeDefined()
})
