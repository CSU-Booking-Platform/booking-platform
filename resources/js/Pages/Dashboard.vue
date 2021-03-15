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
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8 items-center" v-else>
                  <h3>Welcome!</h3>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@src/Layouts/AppLayout";
import DashboardMetrics from "@src/Components/DashboardMetrics";

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
  },
  methods: {
    userHasPermissionWithPrefix(prefix) {
      return this.$page.user.can.some(function (e) {
        return new RegExp("^" + prefix + ".*", "i").test(e);
      });
    },
    showAdvancedDash() {
      return this.userHasPermissionWithPrefix("users")||
      this.userHasPermissionWithPrefix("bookings")
    },
    showSpecificDash(role)
    {
      return this.userHasPermissionWithPrefix(role);
    }
  },
};
</script>
