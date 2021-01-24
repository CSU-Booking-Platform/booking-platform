import { afterEach, beforeEach, expect, jest, test } from "@jest/globals";

jest.mock('laravel-jetstream')
import { createLocalVue, shallowMount } from '@vue/test-utils'
import { InertiaApp } from "@inertiajs/inertia-vue";
import { InertiaForm } from "laravel-jetstream";
import { InertiaFormMock } from "@test/__mocks__/laravel-jetstream";
import UpdateBlackoutForm from "@src/Pages/Admin/Blackouts/BlackoutList";
import BlackoutList from "@src/Pages/Admin/Blackouts/BlackoutList";

let localVue;
let wrapper;
    

beforeEach(() => {
    localVue = createLocalVue();
    localVue.use(InertiaApp);
    localVue.use(InertiaForm);

    wrapper = shallowMount(UpdateBlackoutForm, { localVue });
});

afterEach(() => {
    localVue = null;
    wrapper = null;

    InertiaFormMock.put.mockClear();
});

test('should mount without crashing', () => {
    expect(wrapper.text()).toBeDefined();
})

test('Update Blackout when no form errors', () => {
    InertiaFormMock.put.mockReturnValueOnce({
        then(callback) {
            callback({});
        }
    }
    );
    const wrapper = shallowMount(BlackoutList, {
        localVue,
        data() {
            return {
                blackoutBeingUpdated: {id: 10}
            }
        }
    })

    InertiaFormMock.hasErrors.mockReturnValueOnce(false);
    InertiaFormMock.successful.mockReturnValueOnce(true);

    wrapper.vm.updateBlackout();

    expect(InertiaFormMock.put).toBeCalledTimes(1);
});

test('Update Blackout when form errors', () => {
    InertiaFormMock.put.mockReturnValueOnce({
        then(callback) {
            callback({});
        }
    })

    const wrapper = shallowMount(BlackoutList, {
        localVue,
        data() {
            return {
                blackoutBeingUpdated: {id: 10}
            }
        }
    })

    InertiaFormMock.hasErrors.mockReturnValueOnce(true);
    InertiaFormMock.successful.mockReturnValueOnce(false);
    InertiaFormMock.error.mockReturnValueOnce("Some error");

    wrapper.vm.updateBlackout();

    expect(InertiaFormMock.put).toBeCalledTimes(1);
});

test('Blackout prop watcher updates form', () => {
    const Blackout = { 
        start_time: '2021-01-04T04:10',
        end_time:'2021-01-04T06:10'

     };

    wrapper.setProps({ Blackout });

    wrapper.vm.$nextTick(() => {
        expect(wrapper.vm.form.start_time).toBe(Blackout.start_time);
        expect(wrapper.vm.form.end_time).toBe(Blackout.end_time);
    })
});