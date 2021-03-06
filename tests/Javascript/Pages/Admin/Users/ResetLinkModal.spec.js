import {afterEach, beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')
jest.mock('axios')
import {createLocalVue, shallowMount} from '@vue/test-utils'
import {InertiaApp} from "@inertiajs/inertia-vue";
import ResetLinkModal from "@src/Pages/Admin/Users/ResetLinkModal";
import axios from "axios";

let localVue;


beforeEach(() => {
  localVue = createLocalVue()

  localVue.use(InertiaApp)

});
afterEach(() => {
  localVue = null
})

test('should mount without crashing', () => {

  const wrapper = shallowMount(ResetLinkModal, {localVue})

  expect(wrapper.text()).toBeDefined()
})

test('closeModal should unset all properties and emit a close event', () => {
  const wrapper = shallowMount(ResetLinkModal, {localVue})

  wrapper.vm.closeModal();

  expect(wrapper.vm.resetLink).toBeNull()
  expect(wrapper.emitted().close.length).toBe(1)
})

test('getNewLink should get the new link', async () => {
  axios.post.mockImplementationOnce(() => Promise.resolve({
      status: 200,
      data: {
        link: "https://test.com/reset-password/abcdefgh123456789",
        token: "abcdefgh123456789",
        expires: "2020-05-10 02:00:00"
      }
    }
  ))

  const wrapper = shallowMount(ResetLinkModal, {
    localVue,
    propsData:
      {
        user: {
          id: 69
        }
      },
    mocks: {
      $calendar($time) {
        expect($time).toBe("2020-05-10 02:00:00")
      }
    }
  })

  wrapper.vm.getNewLink();

  //.then()
  await wrapper.vm.$nextTick();
  expect(wrapper.vm.$data.resetLink).toBe("https://test.com/reset-password/abcdefgh123456789")

  //.finally()
  await wrapper.vm.$nextTick();
  expect(wrapper.vm.$data.isProcessing).toBeFalsy()
})

test('getNewLink should display nothing if the link isn\'t returned', async () => {
  const wrapper = shallowMount(ResetLinkModal, {
    localVue, propsData:
      {
        user: {
          id: 69
        }
      }
  })

  axios.post.mockImplementationOnce(() => Promise.resolve({
      status: 200,
      data: {
        token: "abcdefgh123456789"
      }
    }
  ))

  wrapper.vm.getNewLink();

  await wrapper.vm.$nextTick();
  expect(wrapper.vm.resetLink).toBe(null)

  await wrapper.vm.$nextTick();
  expect(wrapper.vm.isProcessing).toBeFalsy()
})
