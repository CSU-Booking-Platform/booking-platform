import {beforeEach, expect, jest, test} from "@jest/globals";
import {createLocalVue, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import AcademicDate from "@src/Pages/Admin/Settings/AcademicDate";

jest.mock('laravel-jetstream')

let localVue
let wrapper

beforeEach(() => {
  localVue = createLocalVue()
  localVue.use(InertiaApp)
  localVue.use(InertiaForm)
  wrapper = shallowMount(AcademicDate, {
    localVue,
    propsData: {
      academicDate: {
        id: 1,
        semester: 'Fall',
        start_date: '2020-11-02',
        end_date: '2020-11-03'
      }
    }
  });
});

afterEach(() => {
  wrapper = null;
});

test('can send form without crashing', () => {
  wrapper.vm.updateDateTime();
})

test('watcher works as expected', () => {
  const academicDate = {
    id: 2,
    semester: 'Winter',
    start_date: '2020-11-08',
    end_date: '2020-11-09'
  }

  wrapper.setProps({academicDate});

  wrapper.vm.$nextTick(() => {
    expect(wrapper.vm.updateDateTimeForm.start_date.toBe(academicDate.start_date));
    expect(wrapper.vm.updateDateTimeForm.end_date.toBe(academicDate.end_date));
  });
})
