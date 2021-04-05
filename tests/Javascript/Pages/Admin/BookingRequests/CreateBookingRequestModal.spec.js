import {beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import CreateBookingRequestModal from '@src/Pages/Admin/BookingRequests/CreateBookingRequestModal';
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

  const wrapper = shallowMount(CreateBookingRequestModal, {localVue})

  expect(wrapper.text()).toBeDefined()
})


test('createBookingRequest when no form errors', () => {

  InertiaFormMock.post.mockReturnValueOnce({
    then(callback) {
      callback({})
    }
  })

  InertiaFormMock.hasErrors.mockReturnValueOnce(false)

  const wrapper = shallowMount(CreateBookingRequestModal, {localVue})

  wrapper.vm.createBookingRequest()

  expect(InertiaFormMock.post).toBeCalledTimes(1)

})


test('createBookingRequestModal when form errors', () => {

  InertiaFormMock.post.mockReturnValueOnce({
    then(callback) {
      callback({})
    }
  })

  InertiaFormMock.hasErrors.mockReturnValueOnce(true)
  InertiaFormMock.error.mockReturnValueOnce("Some name error")

  const wrapper = shallowMount(CreateBookingRequestModal, {localVue})

  wrapper.vm.createBookingRequest()

  expect(InertiaFormMock.post).toBeCalledTimes(1)

})

test('Booking request Modal prop watcher updates form', () => {

  const wrapper = shallowMount(CreateBookingRequestModal, {localVue})
  const room = {id: 1, name: "name"};

  wrapper.setProps({room})

  wrapper.vm.$nextTick(() => {
    expect(wrapper.vm.createBookingRequestForm.room_id).toBe(room.id)
    expect(wrapper.vm.room_name).toBe(room.name)
  })

})

test('Testing date push', () => {

  // Mount the component
  const wrapper = shallowMount(CreateBookingRequestModal, {
    localVue,
    data() {
      return {
        createBookingRequestForm: {
          booking_request_id: null,
          room_id: null,
          reservations: [{
            start_time: null,
            end_time: null,
          }],
          reference: [],
        }
      }
    }
  })

  // Manually trigger the component’s onChange() method
  wrapper.vm.addDate()

  expect(wrapper.vm.createBookingRequestForm.reservations.length).toEqual(2)

})

test('Testing date pop', () => {

  // Mount the component
  const wrapper = shallowMount(CreateBookingRequestModal, {
    localVue,
    data() {
      return {
        createBookingRequestForm: {
          booking_request_id: null,
          room_id: null,
          reservations: [{
            start_time: 1,
            end_time: 1,
          }, {
            start_time: 2,
            end_time: 2,
          }, {
            start_time: 3,
            end_time: 3,
          }],
          reference: [],
        }
      }
    }
  })

  // Manually trigger the component’s onChange() method
  wrapper.vm.removeDate(1)

  expect(wrapper.vm.createBookingRequestForm.reservations.length).toEqual(2)
  expect(wrapper.vm.createBookingRequestForm.reservations[0].start_time).toEqual(1)
  expect(wrapper.vm.createBookingRequestForm.reservations[1].start_time).toEqual(3)
})

test('Testing setting booking duration', () => {

  // Mount the component
  const wrapper = shallowMount(CreateBookingRequestModal, {
    localVue,
    data() {
      return {
        createBookingRequestForm: {
          booking_request_id: null,
          room_id: null,
          reservations: [{
            start_time: "2021-04-09T09:00",
            end_time: "2021-04-09T10:00",
            duration: 0
          }],
          reference: [],
        }
      }
    }
  })

  // Manually trigger the component’s onChange() method
  wrapper.vm.setDuration()

  expect(wrapper.vm.createBookingRequestForm.reservations[0].duration).toEqual(60)

})

test('addDates() should add the expected dates', () => {
  // Mount the component
  const wrapper = shallowMount(CreateBookingRequestModal, {
    localVue,
    data() {
      return {
        recurrenceForm: {
          recurrences: 2,
          type: "days"
        },
        createBookingRequestForm: {
          reservations: [{
            start_time: "2021-04-09 09:00",
            end_time: "2021-04-09 09:30",
            duration: 0
          }],
        }
      }
    }
  })

  wrapper.vm.addDates()

  expect(wrapper.vm.$data.createBookingRequestForm.reservations).toStrictEqual([
    {
      start_time: "2021-04-09 09:00",
      end_time: "2021-04-09 09:30",
      duration: 0
    },
    {
      start_time: "2021-04-10 09:00",
      end_time: "2021-04-10 09:30",
      duration: 0
    },
    {
      start_time: "2021-04-11 09:00",
      end_time: "2021-04-11 09:30",
      duration: 0
    },
  ])
})
