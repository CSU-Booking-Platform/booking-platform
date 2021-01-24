import {beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import BookingRequestsList from '@src/Pages/Admin/BookingRequests/BookingRequestsList'
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

test('should mount without crashing', () => {
    const wrapper = shallowMount(BookingRequestsList, {localVue})
})

test('deleteBookingRequest()', () => {

    let mockBookingRequestBeingDeleted = {
        id: 10
    }

    InertiaFormMock.delete.mockReturnValueOnce({
        then(callback) {
            callback({})
        }
    })

    const wrapper = shallowMount(BookingRequestsList, {
        localVue,
        data() {
            return {
                bookingRequestBeingDeleted: mockBookingRequestBeingDeleted
            }
        }
    })

    wrapper.vm.deleteBookingRequest()

    expect(InertiaFormMock.delete).toBeCalledWith('/reservation/' + mockBookingRequestBeingDeleted.id, {
        preserveScroll: true,
        preserveState: true,
    })

    expect(wrapper.vm.$data.mockBookingRequestBeingDeleted).toBe(undefined)
})

test('setReference(e)', () => {
    let e = {
        reference: {
            path: 'testPath',
        },
    }

    // Mount the component
    const wrapper = shallowMount(BookingRequestsList, {
        localVue,
        data() {
            return {
                bookingReference: '',
            }
        }
    })

    // Trigger the method
    wrapper.vm.setReference(e)

    expect(wrapper.vm.bookingReference).toEqual(e.reference.path)

})

test('test computed href', () => {

    // Mount the component
    const wrapper = shallowMount(BookingRequestsList, {
        localVue,
        data() {
            return {
                bookingReference: "test",
            }
        },

    })

    expect(wrapper.vm.href).toBe("bookings/download/test")

})


