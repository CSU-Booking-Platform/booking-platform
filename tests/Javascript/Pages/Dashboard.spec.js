
import { InertiaApp } from '@inertiajs/inertia-vue'
import Dashboard from '@src/Pages/Dashboard'
import {beforeEach, jest, test} from "@jest/globals";
import {createLocalVue, shallowMount} from '@vue/test-utils'


jest.mock('@inertiajs/inertia-vue')
let localVue

beforeEach(() => {
  localVue = createLocalVue()
  localVue.use(InertiaApp)
});


test('should mount without crashing', () => {
    shallowMount(Dashboard, {localVue,
        mocks: {
          $page: {
            currentRouteName: "dashboard",
            user: {
              can: []
            }
          }
        }
      })
})
