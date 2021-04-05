import {beforeEach, expect, jest, test} from "@jest/globals";
import * as axios from "axios";
jest.mock('laravel-jetstream')

import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import BookingsTable from "@src/Components/Tables/BookingsTable";
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import Index from "@src/Pages/Requestee/BookingsList";
import {bookTest, mocked} from "@test/Components/Constants/UsersBookingRequestTableConstants.js";

let localVue;

jest.mock("axios");

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
      bookings: bookTest
    }
  })
})

test('should set uniqueStatus array from array prop', () => {
  const wrapper = shallowMount(BookingsTable, {
    localVue,
    propsData: {
      bookings: bookTest
    }
  })

  expect(wrapper.vm.uniqueStatuses).toEqual(
    [ 'approved', 'review', 'refused']
  )
})

test('should show advanced filters popup', () => {
  const wrapper = shallowMount(BookingsTable, {
    localVue,
    propsData: {
      bookings: bookTest
    }
  });
  wrapper.vm.toggleAdvancedFilters();
  expect(wrapper.vm.showFilterModal).toBe(true);
})

test('should clear json form date', () => {
  const wrapper = shallowMount(BookingsTable, {
    localVue,
    propsData: {
      bookings: bookTest
    }
  });
  wrapper.vm.jsonForm.dateCheck = "2021 07 07";

  wrapper.vm.clearDate();

  expect(wrapper.vm.jsonForm.dateCheck).toBe(null);
})

test('should emit filterBookingsJson', () => {
  const wrapper = shallowMount(BookingsTable, {
    localVue,
    propsData: {
      bookings: bookTest
    }
  });

  wrapper.vm.advancedFilters();

  expect(wrapper.emitted().filterBookingsJson).toBeTruthy()
})

test('post sent to filterRooms route', () => {

  const wrapper = shallowMount(Index, {
    localVue,
    propsData: {
      bookings: bookTest
    }
  });
  axios.post.mockResolvedValue([
    mocked
  ]);

  wrapper.vm.filterBookingsJson({"selectStatus": 'approved'});
  expect(wrapper.vm.dataBookings).toStrictEqual(
    bookTest
  );

})

test('should set dataBookings from props', () => {
  const wrapper = shallowMount(Index, {
    localVue,
    propsData: {
      bookings: bookTest
    }
  })

  expect(wrapper.vm.dataBookings).toStrictEqual(
    bookTest);
})

