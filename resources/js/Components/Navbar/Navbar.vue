<template>
    <header dusk="navbar">
      <div class="px-15 grid flex-wrap grid-cols-3 py-2">
        <div class="flex flex-1 py-2">
          <a href="/dashboard">
            <jet-application-mark class="block h-15 w-auto"/>
          </a>
          <h1 class="ml-3 pl-3 border-l-2 border-gray-600 text-2xl"> {{$page.app_name}} </h1>
        </div>
      

    <nav class="col-start-3 bg-white border-b border-gray-100 top-nav" aria-label="Main navBar">
      <!-- Primary Navigation Menu -->
      <div class="flex-fill">
        <div class="flex justify-between py-2">
          <div class="flex">
            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:flex">
              <jet-nav-link
                href="/dashboard"
                :active="$page.currentRouteName === 'dashboard'"
              >
                Home
              </jet-nav-link>
              <jet-nav-link
                v-if="$page.user.can.includes('bookings')"
                href="/bookings"
                :active="$page.currentRouteName === 'rooms'"
              >
                Bookings
              </jet-nav-link>
              <!--                TODO: Adjust this permission-->
              <jet-nav-link
                v-if="$page.user.can.includes('users')"
                href="/admin/rooms"
                :active="showAdminSubnav"
              >
                System Administration
              </jet-nav-link>
            </div>
          </div>

          <!-- Settings Dropdown -->
          <div class="hidden sm:flex sm:items-center sm:ml-6">
            <div class="ml-3 relative">
              <jet-dropdown align="right" width="48">
                <template #trigger>
                  <button
                    dusk="nav-profile"
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
                    dusk="nav-profile"
                    v-else
                    class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
                  >
                    <span class="block">{{ $page.user.name }}</span>

                    <span class="block ml-1">
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
                    </span>
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
                    <jet-dropdown-link as="button" dusk="nav-logout"> Logout</jet-dropdown-link>
                  </form>
                </template>
              </jet-dropdown>
            </div>
          </div>
        </div>
      </div>
    </nav>
       <nav class="sub-nav col-start-3" aria-label="SubnavBar">
      <Subnavbar>
        <div
          v-if="$page.currentRouteName === 'dashboard'"
          class="space-x-8 sm:flex justify-start">
          <jet-nav-sub
            href="/dashboard"
            :active="$page.currentRouteName === 'dashboard'"
          >
            Dashboard
          </jet-nav-sub>
        </div>
        <div
          v-else-if="showBookingSubnav()"
          class="space-x-8 sm:flex"
        >
          <jet-nav-sub
            href="/bookings/list"
            :active="$page.currentRouteName === 'bookings.list'"
          >
            My Bookings
          </jet-nav-sub>
          <jet-nav-sub
            href="/bookings"
            :active="$page.currentRouteName === 'bookings.index'"
          >
            Search
          </jet-nav-sub>
          <jet-nav-sub
            href="/bookings/create"
            :active="$page.currentRouteName === 'bookings.create'"
          >
            Create
          </jet-nav-sub>
        </div>
        <div
          v-else-if="showAdminSubnav()"
          class="space-x-8 flex"
        >
          <jet-nav-sub
            href="/admin/rooms"
            :active="$page.currentRouteName === 'admin.rooms.index'"
          >
            Rooms
          </jet-nav-sub>
          <jet-nav-sub
            href="/admin/users"
            :active="$page.currentRouteName === 'admin.users.index'"
          >
            Users
          </jet-nav-sub>
          <jet-nav-sub
            href="/admin/roles"
            :active="$page.currentRouteName === 'admin.roles.index'"
          >
            Roles
          </jet-nav-sub>
          <jet-nav-sub
            href="/admin/settings"
            :active="$page.currentRouteName === 'admin.settings.index'"
          >
            Settings
          </jet-nav-sub>
        </div>
      </Subnavbar>
    </nav>
  </div>
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
      axios.post('/logout').then(() => {
        window.location = '/';
      })
    },
    showAdminSubnav() {
      switch (this.$page.currentRouteName) {
        case 'admin.users.index':
        case 'admin.rooms.index':
        case 'admin.roles.index':
        case 'admin.settings.index':
          return true;
        default:
          return false;
      }
    },
    showBookingSubnav() {
      switch (this.$page.currentRouteName) {
        case 'bookings.index':
        case 'bookings.create':
        case 'bookings.list':
          return true;
        default:
          return false;
      }
    }
  },
};
</script>
