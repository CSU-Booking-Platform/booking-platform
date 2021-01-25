<template>
    <header>
      <div class="px-15 flex-shrink-0 flex">
        <div class="flex flex-row items-center py-4">
          <a href="/dashboard">
            <jet-application-mark class="block h-15 w-auto"/>
          </a>
          <h1 class="ml-3 pl-3 border-l-2 border-gray-600 text-2xl"> CSU booking platform </h1>
        </div>
      </div>

      <nav class="px-15 flex flex-col bg-white border-b border-gray-100 top-nav" aria-label="Main navBar">
        <!-- Primary Navigation Menu -->
        <div class="flex-fill">
          <div class="flex justify-between py-2">
            <div class="flex">
              <!-- Navigation Links -->
              <div class="hidden space-x-8 sm:flex ">
                <jet-nav-link
                  href="/dashboard"
                  :active="$page.currentRouteName == 'dashboard'"
                >
                  Home
                </jet-nav-link>
                <jet-nav-link
                  v-if="$page.user.can.includes('bookings')"
                  href="rooms"
                  :active="$page.currentRouteName == 'rooms'"
                >
                  Booking Management
                </jet-nav-link>
                <jet-nav-link
                  v-if="$page.user.can.includes('users')"
                  href="users"
                  :active="$page.currentRouteName == 'users'"
                >
                  User Management
                </jet-nav-link>
              </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
              <div class="ml-3 relative">
                <jet-dropdown align="right" width="48">
                  <template #trigger>
                    <button
                      v-if="$page.jetstream.managesProfilePhotos"
                      class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out"
                    >
                      <img
                        class="h-8 w-8 rounded-full object-cover"
                        :src="$page.user.profile_photo_url"
                        :alt="$page.user.name"
                      />
                    </button>

                    <button
                      v-else
                      class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
                    >
                      <div>{{ $page.user.name }}</div>

                      <div class="ml-1">
                        <svg
                          class="fill-current h-4 w-4"
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 20 20"
                        >
                          <path
                            fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                          />
                        </svg>
                      </div>
                    </button>
                  </template>

                  <template #content>
                    <!-- Account Management -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                      Manage Account
                    </div>

                    <jet-dropdown-link href="/user/profile">
                      Profile
                    </jet-dropdown-link>

                    <div class="border-t border-gray-100"></div>

                    <!-- Authentication -->
                    <form @submit.prevent="logout">
                      <jet-dropdown-link as="button"> Logout</jet-dropdown-link>
                    </form>
                  </template>
                </jet-dropdown>
              </div>
            </div>
          </div>
        </div>
      </nav>

      <nav class="px-15 sub-nav" aria-label="SubnavBar">
        <Subnavbar>
          <div
            v-if="$page.currentRouteName == 'dashboard'"
            class="space-x-8 sm:flex">
            <jet-nav-sub
              href="/dashboard"
              :active="$page.currentRouteName == 'dashboard'"
            >
              Dashboard
            </jet-nav-sub>
          </div>
          <div
            v-else-if="$page.currentRouteName == 'rooms.index'"
            class="space-x-8 flex"
          >
            <jet-nav-sub
              href="/rooms"
              :active="$page.currentRouteName == 'rooms.index'"
            >
              Rooms
            </jet-nav-sub>
          </div>
          <div
            v-else-if="$page.currentRouteName == 'users.index'"
            class="space-x-8 flex"
          >
            <jet-nav-sub
              href="/users"
              :active="$page.currentRouteName == 'users.index'"
            >
              Users
            </jet-nav-sub>
            <jet-nav-sub
              href="/roles"
              :active="$page.currentRouteName == 'roles.index'"
            >
              Roles
            </jet-nav-sub>
          </div>
          <div
            v-else-if="$page.currentRouteName == 'roles.index'"
            class="space-x-8 flex"
          >
            <jet-nav-sub
              href="/users"
              :active="$page.currentRouteName == 'users.index'"
            >
              Users
            </jet-nav-sub>
            <jet-nav-sub
              href="/roles"
              :active="$page.currentRouteName == 'roles.index'"
            >
              Roles
            </jet-nav-sub>
          </div>
        </Subnavbar>

      </nav>
    </header>
</template>

<script>
import JetApplicationLogo from "@src/Jetstream/ApplicationLogo";
import JetApplicationMark from "@src/Components/ApplicationMark";
import JetDropdown from "@src/Jetstream/Dropdown";
import JetDropdownLink from "@src/Jetstream/DropdownLink";
import JetNavLink from "@src/Components/Navbar/NavLink";
import JetResponsiveNavLink from "@src/Jetstream/ResponsiveNavLink";
import Subnavbar from "@src/Components/Navbar/Subnavbar";
import JetNavSub from "@src/Components/Navbar/SubNavLink";
import axios from "axios";


export default {
  components: {
    JetApplicationLogo,
    JetApplicationMark,
    JetDropdown,
    JetDropdownLink,
    JetNavLink,
    JetResponsiveNavLink,
    Subnavbar,
    JetNavSub,
  },

  data() {
    return {
      showingNavigationDropdown: false,
    };
  },

  methods: {
    logout() {
      axios.post('/logout').then(response => {
        window.location = '/';
      })
    },
  },

  computed: {
    path() {
      return window.location.pathname;
    },
  },
};
</script>
