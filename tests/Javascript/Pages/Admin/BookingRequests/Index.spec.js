import {beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import Index from '@src/Pages/Admin/BookingRequests/Index'
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

let props = {
  rooms: [{
    id: 1,
    name: "name",
    building: "building",
    number: "1",
    floor: 1,
    status: "unavailable"
  }],
  paginator: {
    data:  [{
      id: 1,
      name: "name",
      building: "building",
      number: "1",
      floor: 1,
      status: "unavailable"
    }]
  }
}

test('should mount without crashing', () => {
    const wrapper = shallowMount(Index,
      {
        localVue,
        propsData: props
      })
})


test('should set dataRooms from props', () => {
    const wrapper = shallowMount(Index, {
        localVue,
        propsData: props
    })

    expect(wrapper.vm.dataRooms).toStrictEqual(
        [{
        id: 1,
        name: "name",
        building: "building",
        number: "1",
        floor: 1,
        status: "unavailable"
    }]);
})


test('post sent to filterRooms route', () => {
    const wrapper = shallowMount(Index, {
        localVue,
        propsData: props
    });
    wrapper.vm.filterRoomsJson({"food": true});
    expect(wrapper.vm.dataRooms).toStrictEqual(
        [{
            id: 1,
            name: "name",
            building: "building",
            number: "1",
            floor: 1,
            status: "unavailable"
        }]);

})

