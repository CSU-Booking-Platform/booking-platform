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
    const room = { 
        name: 'the room', 
        number: '24', 
        floor: '2009', 
        building: 'wiseau',
        status: 'Available',
        attributes: {
            capacity_standing:'100',
            capacity_sitting: '80',
            food: 'true',
            alcohol: 'true',
            a_v_permitted: 'false',
            projector: 'true',
            television: 'true',
            computer: 'true',
            whiteboard: 'true',
            sofas: '1',
            coffee_tables: '1',
            tables: '1',
            chairs: '1',
            ambiant_music: 'true',
            sale_for_profit: 'false',
            fundraiser: 'false'
        }

     };

    wrapper.setProps({ room });

    wrapper.vm.$nextTick(() => {
        expect(wrapper.vm.form.name).toBe(room.name);
        expect(wrapper.vm.form.number).toBe(room.number);
        expect(wrapper.vm.form.floor).toBe(room.floor);
        expect(wrapper.vm.form.building).toBe(room.building);
        expect(wrapper.vm.form.status).toBe(room.status);
        expect(wrapper.vm.form.capacity_standing).toBe(room.attributes.capacity_standing);
        expect(wrapper.vm.form.capacity_sitting).toBe(room.attributes.capacity_sitting);
        expect(wrapper.vm.form.food).toBe(room.attributes.food);
        expect(wrapper.vm.form.alcohol).toBe(room.attributes.alcohol);
        expect(wrapper.vm.form.a_v_permitted).toBe(room.attributes.a_v_permitted);
        expect(wrapper.vm.form.projector).toBe(room.attributes.projector);
        expect(wrapper.vm.form.television).toBe(room.attributes.television);
        expect(wrapper.vm.form.computer).toBe(room.attributes.computer);
        expect(wrapper.vm.form.whiteboard).toBe(room.attributes.whiteboard);
        expect(wrapper.vm.form.sofas).toBe(room.attributes.sofas);
        expect(wrapper.vm.form.coffee_tables).toBe(room.attributes.coffee_tables);
        expect(wrapper.vm.form.tables).toBe(room.attributes.tables);
        expect(wrapper.vm.form.chairs).toBe(room.attributes.chairs);
        expect(wrapper.vm.form.ambiant_music).toBe(room.attributes.ambiant_music);
        expect(wrapper.vm.form.sale_for_profit).toBe(room.attributes.sale_for_profit);
        expect(wrapper.vm.form.fundraiser).toBe(room.attributes.fundraiser);
    })

    expect(wrapper.vm.room).toBe(room);
});