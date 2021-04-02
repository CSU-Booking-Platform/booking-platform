<template>
  <header dusk="navbar">
    <div class="px-15 grid flex-wrap grid-cols-3 py-2">
      <div class="flex flex-1 py-2">
        <a href="/dashboard">
          <jet-application-mark class="block h-15 w-auto"/>
        </a>
        <h1 class="ml-3 pl-3 border-l-2 border-gray-600 text-2xl"> {{ $page.app_name }} </h1>
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
                  v-if="showBookingSubnav()"
                  href="/bookings"
                  :active="bookingSubnavIsActive()"
                >
                  Bookings
                </jet-nav-link>
                <jet-nav-link
                  v-if="showAdminSubnav()"
                  href="/admin/rooms"
                  :active="adminSubnavIsActive()"
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
            v-else-if="bookingSubnavIsActive()"
            class="space-x-8 sm:flex"
          >
            <jet-nav-sub
              v-if="userHasOneOf(['bookings.create'])"
              href="/bookings"
              :active="$page.currentRouteName === 'bookings.index'"
            >
              My Bookings
            </jet-nav-sub>
            <jet-nav-sub
              href="/bookings/search"
              :active="$page.currentRouteName === 'bookings.search'"
            >
              Search
            </jet-nav-sub>
            <jet-nav-sub
              v-if="userHasOneOf(['bookings.create'])"
              href="/bookings/create"
              :active="$page.currentRouteName === 'bookings.create'"
            >
              Create
            </jet-nav-sub>
            <jet-nav-sub
              v-if="userHasOneOf(['bookings.approve'])"
              href="/bookings/review"
              :active="$page.currentRouteName === 'bookings.review'"
            >
              Review
              <span v-if="$page.user.bookings_to_review_count > 0"
                class="px-2 py-1 ml-2 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full"
              >
                {{ $page.user.bookings_to_review_count }}
              </span>
            </jet-nav-sub>
          </div>
          <div
            v-else-if="adminSubnavIsActive()"
            class="space-x-8 flex"
          >
            <jet-nav-sub
              v-if="userHasOneOf([
                'rooms.create',
                'rooms.update',
                'rooms.delete'
              ])"
              href="/admin/rooms"
              :active="$page.currentRouteName === 'admin.rooms.index'"
            >
              Rooms
            </jet-nav-sub>
            <jet-nav-sub
              v-if="userHasOneOf([
                'users.create',
                'users.update',
                'users.delete'
              ])"
              href="/admin/users"
              :active="$page.currentRouteName === 'admin.users.index'"
            >
              Users
            </jet-nav-sub>
            <jet-nav-sub
              v-if="userHasOneOf([
                'roles.assign',
                'roles.create',
                'roles.update',
                'roles.delete'
              ])"
              href="/admin/roles"
              :active="$page.currentRouteName === 'admin.roles.index'"
            >
              Roles
            </jet-nav-sub>
            <jet-nav-sub
              v-if="userHasOneOf(['settings.edit'])"
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
    userHasOneOf(permissions) {
      for (let i = 0; i < permissions.length; ++i)
        if (this.$page.user.can.includes(permissions[i]))
          return true;

      return false;
    },
    userHasPermissionWithPrefix(prefix) {
      return this.$page.user.can.some(function (e) {
        return (new RegExp("^" + prefix + ".*", "i")).test(e);
      })
    },
    showAdminSubnav() {
      return this.userHasPermissionWithPrefix("users")
        || this.userHasPermissionWithPrefix("rooms")
        || this.userHasPermissionWithPrefix('roles')
        || this.userHasPermissionWithPrefix('settings');
    },
    adminSubnavIsActive() {
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
    bookingSubnavIsActive() {
      switch (this.$page.currentRouteName) {
        case 'bookings.index':
        case 'bookings.create':
        case 'bookings.search':
          return true;
        default:
          return false
      }
    },
    showBookingSubnav() {
      return this.userHasPermissionWithPrefix("bookings");
    }
  },
};
</script>
