import {beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import RoomTable from '@src/Components/Tables/RoomTable'
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import moment from "moment";


test('Table should populate', () => {

    const wrapper = mount(RoomTable, {
        propsData: {
            rooms: [{
                id: 1,
                name: "name",
                building: "building",
                number: "1",
                floor: 1,
                status: "available"
            }]
        }
      });

    wrapper.setData({ filter: '' })
    expect(wrapper.html()).toContain('<td class="text-center lt-grey">1</td>')
    
    wrapper.setData({ filter: 'building' })
    expect(wrapper.vm.filter).toBe('building')
    expect(wrapper.html()).toContain('<td class="text-center lt-grey">1</td>')

    wrapper.setData({ filter: 'thisfiltershouldnotwork' })
    expect(wrapper.vm.filter).toBe('thisfiltershouldnotwork')
    expect(wrapper.vm.filteredRooms.length).toBe(0)
  })