import {beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import AdminBookingRequestTable from "@src/Components/Tables/AdminBookingRequestTable";
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import RoomTable from "@src/Components/Tables/RoomTable";

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
  const wrapper = shallowMount(AdminBookingRequestTable, {
    localVue,
    propsData: {
      statuses: ["test", "test2"],
      reviewers: []
    }
  })
})


test('should set status_list dict from array prop', () => {
  const wrapper = shallowMount(AdminBookingRequestTable, {
    localVue,
    propsData: {
      statuses: ["test", "test2"],
      reviewers: []
    }
  })

  expect(wrapper.props().statuses).toStrictEqual(["test", "test2"])
  expect(wrapper.vm.$data).toStrictEqual(
  {"filter": "", "jsonFilters": {"data_reviewers": [], "date_range_end": null, "date_range_start": null, "status_list": {"test": false, "test2": false}}, "showFilterModal": false, "options": {}, "selected": {}}
    )
})

test('should compute only active jsonfilter fields to send in post request', () => {
  const wrapper = shallowMount(AdminBookingRequestTable, {
    localVue,
    propsData: {
      statuses: ["test", "test2"],
      reviewers: []
    }
  });

  wrapper.vm.jsonFilters.status_list.test = true;
  expect(wrapper.vm.activeJsonFilters).toStrictEqual(
    {
      "status_list": {
        "test": true,
        "test2": false,
      },
      "data_reviewers": []
  });
})


test('should filter properly', () => {

  const wrapper = shallowMount(AdminBookingRequestTable, {
    localVue,
    propsData: {
      statuses: ["test", "test2"],
      reviewers: [],
      bookings: [{
        "id": 1,
        "status": "review",
        "requester": {
          "name": "Test"
        }
      }]
    }
  })

  wrapper.setData({ filter: '' })
  expect(wrapper.html()).toContain('<td class="text-center">Test</td>')

  wrapper.setData({ filter: 'thisfiltershouldnotwork' })
  expect(wrapper.vm.filter).toBe('thisfiltershouldnotwork')
  expect(wrapper.vm.filteredBookingRequests.length).toBe(0)
})


test('should show advanced filters popup', () => {
  const wrapper = shallowMount(AdminBookingRequestTable, {
    localVue,
    propsData: {
      statuses: ["test", "test2"],
      reviewers: [],
      bookings: [{
        "id": 1,
        "status": "review",
        "requester": {
          "name": "Test"
        }
      }]
    }
  });
  wrapper.vm.advancedFilters();
  expect(wrapper.vm.showFilterModal).toBe(true);
})
