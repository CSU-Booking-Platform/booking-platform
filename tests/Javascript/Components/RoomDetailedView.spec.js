import {afterEach, beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream');

import {createLocalVue, mount, shallowMount} from '@vue/test-utils';
import {InertiaApp} from '@inertiajs/inertia-vue';
import {InertiaForm} from 'laravel-jetstream';
import RoomDetailedView from "@src/Components/RoomDetailedView";
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import 'regenerator-runtime/runtime';

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

  const wrapper = shallowMount(RoomDetailedView, {
    localVue,
    propsData: {
      rooms: {
        id: 1,
        name: "name",
        building: "building",
        number: "1",
        floor: 1,
        status: "available",
        room_type: "Mezzanine"
      }
    }
  })

  expect(wrapper.text()).toBeDefined()
});


test('should convert var name to readable text', () => {

  const wrapper = mount(RoomDetailedView, {
    localVue,
    propsData: {
      rooms: {
        id: 1,
        name: "name",
        building: "building",
        number: "1",
        floor: 1,
        status: "available",
        room_type: "Mezzanine"
      }
    }
  })

  const var_to_text = wrapper.vm.convertVarToText('test_var');
  expect(var_to_text).toBe('Test Var');
});


test('emit modal closed', async () => {
  const wrapper = mount(RoomDetailedView)

  await wrapper.find('button').trigger('click')

  expect(wrapper.emitted().close).toBeTruthy()

})
