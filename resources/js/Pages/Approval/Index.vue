<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Review Bookings
      </h2>
    </template>
    <div v-if="bookings.length > 0" class="m-24">
      <AdminBookingRequestTable
        :bookings="dataBookings"
        :statuses="statuses"
        :reviewers="reviewers"
        @filterBookingRequestsJson="filterBookingRequestsJson($event)"
        dusk="booking-requests-table"
      />
    </div>

    <div v-else>
      <div class="mx-10 mt-5 md:mt-0 md:col-span-2">
        <div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg">
          <div class="mt-10 sm:mt-0">
            <div class="space-y-6">
                <div class="flex items-center">
                  <div>
                    There are no booking requests to be displayed
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    </app-layout>
</template>

<script>
import JetResponsiveNavLink from "@src/Jetstream/ResponsiveNavLink"
import JetSectionBorder from '@src/Jetstream/SectionBorder'
import XSection from "@src/Components/XSection"
import AppLayout from '@src/Layouts/AppLayout'
import AdminBookingRequestTable from "@src/Components/Tables/AdminBookingRequestTable";
import axios from "axios";

export default {
  components: {
    JetResponsiveNavLink,
    JetSectionBorder,
    AppLayout,
    XSection,
    AdminBookingRequestTable
  },
  props: {
    bookings: {
      type: Array,
      default: function () {
        return []
      },
    },
    statuses:{
      type: Array,
      default: function () {
        return []
      },
    },
    reviewers: {
      type: Array,
      required: true,
    },
  },
  mounted(){
    this.dataBookings = this.bookings ?? [];
  },
  data() {
    return {
      dataBookings: []
    }
  },
  methods:{
    filterBookingRequestsJson(e) {
      axios.post('/api/filterBookingRequests', e)
        .then((response)=>{
          console.log(response)
          this.dataBookings = response.data;
        })
    }
  }
}
</script>
