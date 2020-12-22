import { afterEach, beforeEach, expect, jest, test } from "@jest/globals";

jest.mock('laravel-jetstream')
import { createLocalVue, shallowMount } from '@vue/test-utils'
import { InertiaApp } from "@inertiajs/inertia-vue";
import { InertiaForm } from "laravel-jetstream";
import { InertiaFormMock } from "@test/__mocks__/laravel-jetstream";
import UpdateBookingRequestForm from "@src/Pages/Admin/BookingRequests/UpdateBookingRequestForm";

let localVue;
let wrapper;

beforeEach(() => {
    localVue = createLocalVue();
    localVue.use(InertiaApp);
    localVue.use(InertiaForm);

    wrapper = shallowMount(UpdateBookingRequestForm, { localVue });
});

afterEach(() => {
    localVue = null;
    wrapper = null;

    InertiaFormMock.put.mockClear();
});

test('should mount without crashing', () => {
    expect(wrapper.text()).toBeDefined();
})

test('Update booking request when no form errors', () => {
    InertiaFormMock.put.mockReturnValueOnce({
        then(callback) {
            callback({});
        }
    });

    InertiaFormMock.hasErrors.mockReturnValueOnce(false);
    InertiaFormMock.successful.mockReturnValueOnce(true);

    wrapper.vm.updateBookingRequest();

    expect(InertiaFormMock.put).toBeCalledTimes(1);
});

test('Update room when form errors', () => {
    InertiaFormMock.put.mockReturnValueOnce({
        then(callback) {
            callback({});
        }
    })

    InertiaFormMock.hasErrors.mockReturnValueOnce(true);
    InertiaFormMock.successful.mockReturnValueOnce(false);
    InertiaFormMock.error.mockReturnValueOnce("Some error");

    wrapper.vm.updateBookingRequest();

    expect(InertiaFormMock.put).toBeCalledTimes(1);
});

test('Booking request prop watcher updates form', () => {

    const booking_request =  { room_id: 'the room', start_time: 'now', end_time: 'tomorrow' };

    wrapper.setProps({ booking_request })

    wrapper.vm.$nextTick(() => {
        expect(wrapper.vm.form.room_id).toBe(booking_request.room_id)
        expect(wrapper.vm.form.start_time).toBe(booking_request.start_time)
        expect(wrapper.vm.form.end_time).toBe(booking_request.end_time)
    })

    expect(wrapper.vm.booking_request).toBe(booking_request)

})