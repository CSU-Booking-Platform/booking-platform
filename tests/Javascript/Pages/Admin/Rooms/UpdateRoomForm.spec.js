import { afterEach, beforeEach, expect, jest, test } from "@jest/globals";

jest.mock('laravel-jetstream')
import { createLocalVue, shallowMount } from '@vue/test-utils'
import { InertiaApp } from "@inertiajs/inertia-vue";
import { InertiaForm } from "laravel-jetstream";
import { InertiaFormMock } from "@test/__mocks__/laravel-jetstream";
import UpdateRoomForm from "@src/Pages/Admin/Rooms/UpdateRoomForm";

let localVue;
let wrapper;

beforeEach(() => {
    localVue = createLocalVue();
    localVue.use(InertiaApp);
    localVue.use(InertiaForm);

    wrapper = shallowMount(UpdateRoomForm, { localVue });
});

afterEach(() => {
    localVue = null;
    wrapper = null;

    InertiaFormMock.put.mockClear();
});

test('should mount without crashing', () => {
    expect(wrapper.text()).toBeDefined();
})

test('Update room when no form errors', () => {
    InertiaFormMock.put.mockReturnValueOnce({
        then(callback) {
            callback({});
        }
    });

    InertiaFormMock.hasErrors.mockReturnValueOnce(false);
    InertiaFormMock.successful.mockReturnValueOnce(true);

    wrapper.vm.updateRoom();

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

    wrapper.vm.updateRoom();

    expect(InertiaFormMock.put).toBeCalledTimes(1);
});

test('Room prop watcher updates form', () => {
    const room = { name: 'the room', number: '24', floor: '2009', building: 'wiseau' };

    wrapper.setProps({ room });

    wrapper.vm.$nextTick(() => {
        expect(wrapper.vm.form.name).toBe(room.name);
        expect(wrapper.vm.form.number).toBe(room.number);
        expect(wrapper.vm.form.floor).toBe(room.floor);
        expect(wrapper.vm.form.building).toBe(room.building);
    })

    expect(wrapper.vm.room).toBe(room);
});