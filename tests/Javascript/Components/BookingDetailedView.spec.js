import {beforeEach, expect, jest, test} from "@jest/globals";
import {createLocalVue, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import { InertiaForm } from "laravel-jetstream";
import BookingDetailedView from "@src/Components/BookingDetailedView";
import moxios from 'moxios';
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";

jest.mock('laravel-jetstream')

let localVue, wrapper;

beforeEach(() => {
  localVue = createLocalVue();
  localVue.use(InertiaApp);
  localVue.use(InertiaForm);
  moxios.install();

  wrapper = shallowMount(BookingDetailedView, {localVue,
    propsData: {
      booking: {
        id: 1,
        room: {
          id: 1,
          name: 'a',
          building: 'a',
          floor: 'a',
          attributes: {
            food: true,
          }
        },
        reference: [
          {
            name: "test.pdf",
          }
        ],
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
        ],
        requester: {
          name: "John Doe",
          organization: "org",
        },
        onsite_contact: {
          name: "John",
          email: "test@test.com",
          phone: "123.123.1234",
        },
        comments: [
          {
            user: {
              id: 1,
            }
          }
        ]
      },
    },
  });
});

afterEach(() => {
  wrapper = null;
  moxios.uninstall();
  InertiaFormMock.post.mockClear();
});

test('should mount component without crashing', () => {
  expect(wrapper.text()).toBeDefined();
})

test('should submit comment', () => {
  InertiaFormMock.post.mockReturnValueOnce({
    then(callback) {
      callback({})
    }
  })
  InertiaFormMock.hasErrors.mockReturnValueOnce(false)

  wrapper.vm.submitComment();

  expect(InertiaFormMock.post).toBeCalledTimes(1);

})

test('should save comment', () => {
  InertiaFormMock.post.mockReturnValueOnce({
    then(callback) {
      callback({})
    }
  })
  InertiaFormMock.hasErrors.mockReturnValueOnce(false)

  wrapper.vm.saveComment("<p>test</p>");

  expect(InertiaFormMock.post).toBeCalledTimes(1);

})
