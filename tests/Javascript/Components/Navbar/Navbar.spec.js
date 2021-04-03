import {beforeEach, afterEach, jest, test} from "@jest/globals";

jest.mock('@inertiajs/inertia-vue')

import {createLocalVue, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import Navbar from "@src/Components/Navbar/Navbar";

let localVue

const localStorageMock = (function() {
  let store = {};
  
  return {
    getItem(key) {
      return store[key];
    },
 
    setItem(key, value) {
      store[key] = value;
    },
  
    clear() {
      store = {};
    },

    removeItem(key) {
      delete store[key];
    },
  };
})();

beforeEach(() => {
  localVue = createLocalVue()
  localVue.use(InertiaApp)
  Object.defineProperty(window, 'localStorage', { value: localStorageMock });
});

afterEach(() => {
  window.localStorage.clear();
});

test('should mount without crashing', () => {
  shallowMount(Navbar, {localVue,
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

test('showAdminSubnav should be true for admin pages', () => {
  const wrapper = shallowMount(Navbar, {localVue,
    mocks: {
      $page: {
        currentRouteName: "admin.settings.index",
        user: {
          can: [
            'users'
          ]
        }
      }
    }
  })

  expect(wrapper.vm.showAdminSubnav()).toBe(true);
  expect(wrapper.vm.showBookingSubnav()).toBe(false);
})

test('showBookingNav should be true for booking pages', () => {
  const wrapper = shallowMount(Navbar, {localVue,
    mocks: {
      $page: {
        currentRouteName: "bookings.index",
        user: {
          can: [
            'bookings.create'
          ]
        }
      }
    }
  })

  expect(wrapper.vm.showAdminSubnav()).toBe(false);
  expect(wrapper.vm.showBookingSubnav()).toBe(true);
})

test('isCreatingBooking should be true when creating a booking', () => {

  const wrapper = shallowMount(Navbar, {localVue,
    mocks: {
      $page: {
        currentRouteName: "bookings.index",
        user: {
          can: [
            'bookings.create'
          ]
        }
      }
    },
  })
  
  localStorage.isCreatingBooking = "true";
  wrapper.vm.setIsCreatingBooking();
  expect(wrapper.vm.isCreatingBooking).toBe(true); 
})
