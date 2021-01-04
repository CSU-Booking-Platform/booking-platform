import {afterEach, beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')
import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
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

test('Testing file upload', () => {

    let event = {
        target: {
            files: [
                {
                    name: 'image.png',
                    size: 50000,
                    type: 'image/png',
                },
            ],
        },
    }

    // Mount the component
    const wrapper = shallowMount(CreateBookingRequestForm, {
        localVue,
        data() {
            return {
                createBookingRequestForm: {
                    reference: [],
                }
            }
        }
    })

    // Manually trigger the component’s onChange() method
    wrapper.vm.fieldChange(event)

    expect(wrapper.vm.createBookingRequestForm.reference).toEqual(event.target.files)

})

test('Testing empty file upload', () => {

    let event = {
        target: {
            files: [
            ],
        },
    }

    // Mount the component
    const wrapper = shallowMount(CreateBookingRequestForm, {
        localVue,
        data() {
            return {
                createBookingRequestForm: {
                    reference: [],
                }
            }
        }
    })

    // Manually trigger the component’s onChange() method
    wrapper.vm.fieldChange(event)

    expect(wrapper.vm.createBookingRequestForm.reference).toEqual(event.target.files)

})
