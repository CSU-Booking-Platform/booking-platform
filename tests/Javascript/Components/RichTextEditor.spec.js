import {beforeEach, expect, jest, test} from "@jest/globals";
import {createLocalVue, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import RichTextEditor from "@src/Components/RichTextEditor";

jest.mock('laravel-jetstream')

let localVue
let wrapper

beforeEach(() => {
  localVue = createLocalVue()
  localVue.use(InertiaApp)
  localVue.use(InertiaForm)
  wrapper = shallowMount(RichTextEditor, {
    localVue,
    propsData: {
      editable: true, incomingText: 'hello my old friend', onSave: () => {
        console.log('dummy-function');
      }
    }
  });
});

afterEach(() => {
  wrapper = null;
});

test('can save text', () => {
  wrapper.vm.saveText();
})

test('prop watcher works as expected ', () => {

  wrapper.setProps({
    editable: false, incomingText: 'hello my new friend', onSave: () => {
      console.log('dummy-function');
    }
  });

  wrapper.vm.$nextTick(() => {
    expect(wrapper.vm.incomingText).toBe('hello my new friend');
    expect(wrapper.vm.editable).toBe(false);
  })
})
