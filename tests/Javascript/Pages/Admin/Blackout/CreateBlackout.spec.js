import {afterEach, beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')
import {createLocalVue, mount} from '@vue/test-utils'
import {InertiaApp} from "@inertiajs/inertia-vue";
import {InertiaForm} from "laravel-jetstream";
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import CreateBlackoutForm from "@src/Pages/Admin/Blackouts/CreateBlackoutForm";

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

  const wrapper = mount(CreateBlackoutForm, {
    localVue,
    propsData: {
      room: {id: 1}
    }
  })

  expect(wrapper.text()).toBeDefined()
})


test('createBlackout when no form errors', () => {

  InertiaFormMock.post.mockReturnValueOnce({
    then(callback) {
      callback({})
    }
  })

  InertiaFormMock.hasErrors.mockReturnValueOnce(false)

  const wrapper = mount(CreateBlackoutForm, {
    localVue, propsData: {
      room: {id: 1}
    }
  })

  wrapper.vm.createBlackout()

  expect(InertiaFormMock.post).toBeCalledTimes(1)

})

test('createBlackout when form errors', () => {

  InertiaFormMock.post.mockReturnValueOnce({
    then(callback) {
      callback({})
    }
  })

  InertiaFormMock.hasErrors.mockReturnValueOnce(true)
  InertiaFormMock.error.mockReturnValueOnce("Some name error")

  const wrapper = mount(CreateBlackoutForm, {
    localVue,
    propsData: {
      room: {id: 1}
    }
  })

  wrapper.vm.createBlackout()

  expect(InertiaFormMock.post).toBeCalledTimes(1)

})
