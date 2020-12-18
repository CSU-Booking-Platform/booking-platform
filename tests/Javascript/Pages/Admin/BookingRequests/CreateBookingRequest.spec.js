import {afterEach, beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')
import {createLocalVue, mount} from '@vue/test-utils'
import {InertiaApp} from "@inertiajs/inertia-vue";
import {InertiaForm} from "laravel-jetstream";
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import CreateBookingRequestForm from "@src/Pages/Admin/BookingRequests/CreateBookingRequestForm";
import Index from "@src/Pages/Admin/Rooms/Index";

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

    const wrapper = mount(CreateBookingRequestForm, {localVue})

    expect(wrapper.text()).toBeDefined()
})


test('createBookingRequest when no form errors', () => {

    InertiaFormMock.post.mockReturnValueOnce({
        then(callback) {
            callback({})
        }
    })

    InertiaFormMock.hasErrors.mockReturnValueOnce(false)

    const wrapper = mount(CreateBookingRequestForm, {localVue})

    wrapper.vm.createBookingRequest()

    expect(InertiaFormMock.post).toBeCalledTimes(1)

})

test('createBookingRequest when form errors', () => {

    InertiaFormMock.post.mockReturnValueOnce({
        then(callback) {
            callback({})
        }
    })

    InertiaFormMock.hasErrors.mockReturnValueOnce(true)
    InertiaFormMock.error.mockReturnValueOnce("Some name error")

    const wrapper = mount(CreateBookingRequestForm, {localVue})

    wrapper.vm.createBookingRequest()

    expect(InertiaFormMock.post).toBeCalledTimes(1)

})
