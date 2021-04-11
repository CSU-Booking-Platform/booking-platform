<template>
    <app-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8 items-center" v-if="showAdvancedDash()">
                  <h3 v-if='showSpecificDash("rooms")'>
                   Room Types
                  <DashboardMetrics :metrics="rooms" />
                  </h3>
                  <h3 v-if='showSpecificDash("bookings")'>
                   Bookings
                  <DashboardMetrics :metrics="bookings" />
                  </h3>
                  <h3 v-if='showSpecificDash("users")'>
                   Users
                  <DashboardMetrics :metrics="users" />
                  </h3>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8 items-center content-center" v-else>
                  <jet-application-mark class="block h-40 w-auto"/>
                  <h2>Welcome to the CSU Booking application!</h2>
                  <h3>If you are unable to see the pages you need in the Navbar above, please contact  <a class="text-yellow-300" href="mailto:it@csu.qc.ca">it@csu.qc.ca</a></h3>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@src/Layouts/AppLayout";
import DashboardMetrics from "@src/Components/DashboardMetrics";
import JetApplicationMark from "@src/Components/ApplicationMark";

export default {
  props: {
    rooms: {
      type: Array,
      default: [],
      required: true,
    },
     bookings: {
      type: Array,
      default: [],
      required: true,
    },
     users: {
      type: Array,
      default: [],
      required: true,
    },
  },

  components: {
    AppLayout,
    DashboardMetrics,
    JetApplicationMark
  },
  methods: {
    userHasPermissionWithPrefix(prefix) {
      return this.$page.user.can.some(function (e) {
        return new RegExp("^" + prefix + ".*", "i").test(e);
      });
    },
    showAdvancedDash() {
      return this.userHasPermissionWithPrefix("users")||
      this.userHasPermissionWithPrefix("roles")
    },
    showSpecificDash(role)
    {
      return this.userHasPermissionWithPrefix(role);
    }
  },
};
</script>
