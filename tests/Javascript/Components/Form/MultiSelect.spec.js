import {beforeEach, afterEach, expect, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import Component from '@src/Components/Form/MultiSelect'

let localVue, wrapper;

beforeEach(() => {
    localVue = createLocalVue()
    localVue.use(InertiaApp)
    localVue.use(InertiaForm)

  wrapper = shallowMount(Component, {localVue, propsData: {
    options: {
      1: 'a',
      9: 'b',
      6: 'c',
    },
    selectedOnInit: {
      1: 'a',
    }
  }})

});

afterEach(() => {
  wrapper = null;
})

test('should mount without crashing', () => {
  expect(wrapper.text()).toBeDefined();
})

test('verify selected is set when given a list', () => {
  expect(wrapper.vm.selected).toStrictEqual({1: 'a'});
})

test('can update available options', () => {
  let options = {1: 'z'};
  const wrapper = shallowMount(Component, {localVue, propsData: { options: options }})

  expect(wrapper.vm.options).toStrictEqual(options);

  options = {1: 'z', 21: 't', 44: 'h', 35: 's'};
  wrapper.setProps({options: options});

  expect(wrapper.vm.options).toStrictEqual(options);

})

test('can select items', () => {
  let options = {1: 'a', 9: 'b', 6: 'c', 5: 'g'}
  const wrapper = shallowMount(Component, {localVue, propsData: { options: options }})

  expect(wrapper.vm.selected).toStrictEqual({});

  wrapper.vm.select(9);
  expect(wrapper.vm.selected).toStrictEqual({9: 'b'});

  wrapper.vm.select(1);
  wrapper.vm.select(6);
  wrapper.vm.select(5);
  expect(wrapper.vm.selected).toStrictEqual(options);

})

test('selecting the same item will remove it', () => {
  let options = {1: 'a', 9: 'b', 6: 'c', 5: 'g'}
  const wrapper = shallowMount(Component, {localVue, propsData: { options: options, selectedOnInit: options }})

  expect(wrapper.vm.selected).toStrictEqual(options);

  wrapper.vm.select(9);
  wrapper.vm.select(1);
  wrapper.vm.select(6);
  wrapper.vm.select(5);
  expect(wrapper.vm.selected).toStrictEqual({});

})
