import {beforeEach, jest, test} from "@jest/globals";
import {createLocalVue, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import CivicHoliday from "@src/Pages/Admin/Settings/CivicHoliday";

jest.mock('laravel-jetstream')

let localVue
let wrapper

beforeEach(() => {
  localVue = createLocalVue()
  localVue.use(InertiaApp)
  localVue.use(InertiaForm)
  wrapper = shallowMount(CivicHoliday, {
    localVue,
    propsData: {}
  });
});

afterEach(() => {
  wrapper = null;
});

test('can send form without crashing', () => {
  wrapper.vm.createBlackoutForEveryRoom();
})
