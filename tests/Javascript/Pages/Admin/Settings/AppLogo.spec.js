import {beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import AppLogo from '@src/Pages/Admin/Settings/AppLogo'
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import moment from "moment";

let localVue

beforeEach(() => {
    InertiaFormMock.error.mockClear()
    InertiaFormMock.post.mockClear()
    // InertiaFormMock.delete.mockClear()

    localVue = createLocalVue()
    localVue.use(InertiaApp)
    localVue.use(InertiaForm)

});


test('Testing file upload', () => {

    const event = {
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
    const wrapper = shallowMount(AppLogo, {localVue})

    // Manually trigger the component’s onChange() method
    wrapper.vm.selectFile(event)

    expect(wrapper.vm.updateLogoSettingform.app_logo).toEqual(event.target.files[0])

})

test('should mount without crashing', () => {
    const wrapper = shallowMount(AppLogo, {localVue})
})


test('updateLogo()', () => {
    const event = {
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

    InertiaFormMock.post.mockReturnValueOnce({
        then(callback) {
            callback({})
        }
    })

    const wrapper = shallowMount(AppLogo, {
        localVue,
        data() {
            return {
                updateLogoSettingform: {
                    label: 'app_logo',
                    app_logo: event.target.files[0]
                }
            }
        }
    })

    // Manually trigger the component’s onChange() method
    wrapper.vm.updateLogoSetting()
    const data = new FormData()
    data.append('label', 'app_logo')
    data.append('app_logo', event.target.files[0])
    expect(InertiaFormMock.post).toBeCalledWith('/settings/app_logo', data)
})
