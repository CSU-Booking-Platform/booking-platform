import {beforeEach, expect, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')
jest.mock('create-file-list');

import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import SortableUpload from '@src/Components/Form/SortableUpload'

let localVue
let wrapper

beforeEach(() => {
    localVue = createLocalVue()
    localVue.use(InertiaApp)
    localVue.use(InertiaForm)

    wrapper = shallowMount(SortableUpload, {localVue,
        data() {
            return {
                fileDragging: null,
                fileDropping: null,
                files: [{
                    lastModified: 1610336108413,
                    name: "Sprint 5 Review.pdf",
                    size: 2267483,
                    type: "application/pdf",
                    webkitRelativePath: "",
                },{
                  lastModified: 1610336108413,
                  name: "Sprint 6 Review.pdf",
                  size: 2267483,
                  type: "application/pdf",
                  webkitRelativePath: "",
                }],
            }
        }
    });

});

afterEach(() => {
    wrapper = null
})

test('should mount without crashing', () => {

    expect(wrapper.text()).toBeDefined();
})

test('should add and remove styles', () => {

    expect(wrapper.vm.$refs.dnd.classList.contains('border-blue-400')).toBeFalsy()
    expect(wrapper.vm.$refs.dnd.classList.contains('ring-4')).toBeFalsy()
    expect(wrapper.vm.$refs.dnd.classList.contains('ring-inset')).toBeFalsy()

    wrapper.find('input').trigger('dragover');

    expect(wrapper.vm.$refs.dnd.classList.contains('border-blue-400')).toBeTruthy()
    expect(wrapper.vm.$refs.dnd.classList.contains('ring-4')).toBeTruthy()
    expect(wrapper.vm.$refs.dnd.classList.contains('ring-inset')).toBeTruthy()

    wrapper.find('input').trigger('drop');
    wrapper.find('input').trigger('dragleave');

    expect(wrapper.vm.$refs.dnd.classList.contains('border-blue-400')).toBeFalsy()
    expect(wrapper.vm.$refs.dnd.classList.contains('ring-4')).toBeFalsy()
    expect(wrapper.vm.$refs.dnd.classList.contains('ring-inset')).toBeFalsy()

})

test('should return human readable size', () => {

    wrapper.vm.humanFileSize(1);
    expect(wrapper.vm.humanFileSize(1024)).toBe("1 kB");
})

test('should cover drag', () => {

    wrapper.find('div.overflow-hidden').trigger('dragstart');
    wrapper.find('div.transition-colors').trigger('dragenter');

    expect(wrapper.text()).toBeDefined();
})

test('should change when remove', () => {

    wrapper.vm.remove(1);

    expect(wrapper.emitted().change).toBeTruthy();

    wrapper.vm.remove(1);
    expect(wrapper.emitted().change).toBeTruthy();

    wrapper.vm.remove(1);
    expect(wrapper.emitted().change).toBeTruthy();
})

test('should fire event on remove-existing-file', () => {

  wrapper.vm.removeStoredFile(1);

  expect(wrapper.emitted().remove).toBeTruthy();
})


test('should change when drop', () => {

    wrapper.vm.drop();

    expect(wrapper.emitted().change).toBeTruthy();
})

/*test('should change when load', () => {

    wrapper.vm.loadFile(wrapper.vm.files[0])

    expect(wrapper.emitted().change).toBeTruthy();
})*/

test('should change when add', () => {

    wrapper.vm.addFiles({target : {files: [{}]}});

    expect(wrapper.emitted().change).toBeTruthy();
})
