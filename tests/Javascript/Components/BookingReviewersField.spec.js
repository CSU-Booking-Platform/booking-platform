import {beforeEach, afterEach, expect, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')


import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import Component from '@src/Components/BookingReviewersField';

import axios from 'axios';
import moxios from 'moxios';

let localVue, wrapper;

beforeEach(() => {
  localVue = createLocalVue()
  localVue.use(InertiaApp)
  localVue.use(InertiaForm)
  moxios.install()

  wrapper = shallowMount(Component, {localVue, propsData: {
    booking_request_id: 43,
    reviewers: [{
      id: 41,
      name: "A",
      icon: "https://ui-avatars.com/api/?name=A&color=7F9CF5&background=EBF4FF"
    }],
  }})

})

afterEach(() => {
  wrapper = null;
  moxios.uninstall();
  InertiaFormMock.post.mockClear()
})

test('should mount without crashing', () => {
  expect(wrapper.text()).toBeDefined()
})

test('when editing, populate options and selected data', async () => {
  let reviewers = [
      {
        id: 41,
        name: "A",
        icon: "https://ui-avatars.com/api/?name=A&color=7F9CF5&background=EBF4FF"
      },
      {
        id: 64,
        name: "B",
        icon: "https://ui-avatars.com/api/?name=B&color=7F9CF5&background=EBF4FF"
      },
    ],

  wrapper = shallowMount(Component, {localVue, propsData: {
    booking_request_id: 43,
    reviewers: reviewers,
    editing: true
  }})

  moxios.wait(function () {
    let request = moxios.requests.mostRecent()
    request.respondWith({
      status: 200,
      response: reviewers
    })
  })

  await wrapper.vm.$nextTick();

  expect(wrapper.vm.$props.editing).toBe(true);
  expect(wrapper.vm.$data.options).toStrictEqual(wrapper.vm.reduceUsersForSelect(reviewers));
  expect(wrapper.vm.$data.selected).toStrictEqual(wrapper.vm.reduceUsersForSelect(reviewers));
})

test('can assign booking reviewers', () => {

  InertiaFormMock.post.mockReturnValueOnce({
    then(callback) {
      callback({})
    }
  })

  InertiaFormMock.hasErrors.mockReturnValueOnce(false)

  wrapper.vm.updateReviewers([5,4,2])

  expect(InertiaFormMock.post).toBeCalledTimes(1)

})
