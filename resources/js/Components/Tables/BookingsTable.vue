<template>
  <div class="table-container">
    <div class="table-filter-container mb-12 flex flex-row">
      <div class="ml-3 mr-6">
        <h3 class="font-black">My Bookings</h3>
      </div>
      <div class="border shadow-md">
        <input type="text" v-model="filter"/>
      </div>
      <div class="bg-yellow-300 shadow-md">
        <em class="fas fa-search m-2"></em>
      </div>
    </div>


    <table class="table-auto responsive-spaced">
      <caption></caption>
      <thead>
        <tr>
          <th id="id_room_name">Room Name</th>
          <th id="id_room_number">Room Number</th>
          <th id="id_booking_date">Date/Time</th>
          <th id="id_booking_status">Status</th>
          <th id="id_booking_action">Action</th>
          <th id="id_booking_download">Download</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="booking in filteredBookings" :key="booking.id">
            <td class="text-center">{{booking.reservations[0].room.name}}</td>
            <td class="text-center">{{booking.reservations[0].room.number}}</td>
            <td class="text-center">{{formatDateMonth(booking.reservations[0].start_time)}} From:  {{formatDateTime(booking.reservations[0].start_time)}} To: {{formatDateTime(booking.reservations[0].end_time)}}</td>
            <td class="text-center">
              <div class="text-md mx-3">
                <button
                  class="h-10 px-5 m-2 text-gray-100 transition-colors duration-150 bg-gray-700 rounded-lg focus:shadow-outline hover:bg-gray-800"
                  @click="bookingRequestToTrack = booking"
                >View</button>
              </div>
            </td>
            <td class="text-center">
            <a v-if="booking.status == 'pending'" :href="'/bookings/'+booking.id+'/edit'">
              <button class="h-10 px-5 m-2 text-gray-100 transition-colors duration-150 bg-gray-700 rounded-lg focus:shadow-outline hover:bg-gray-800">
                Edit
              </button>
            </a>
              <a v-else-if="booking.status == 'review'" style="cursor:pointer;">
                <button aria-disabled="true" class="h-10 px-5 m-2 text-gray-100 transition-colors duration-150 bg-gray-500 rounded-lg focus:shadow-outline ">
                  In Review
                </button>
              </a>
              <a v-else-if="booking.status == 'refused'" >
                <button aria-disabled="true" class="h-10 px-5 m-2 text-gray-100 transition-colors duration-150 bg-red-900 rounded-lg focus:shadow-outline">
                  Refused
                </button>
              </a>
              <a v-else-if="booking.status == 'approved'" >
                <button aria-disabled="true" class="h-10 px-5 m-2 text-gray-100 transition-colors duration-150 bg-green-500 rounded-lg focus:shadow-outline">
                  Approved
                </button>
              </a>
            </td>
            <td class="text-center">
            <div class="text-md mx-3">
                  <a
                    v-if="booking.reference && booking.reference.path"
                    @click="setReference(booking);"
                    class="cursor-pointer text-sm text-blue-800 focus:outline-none"
                    :href="href"
                  >Download</a>
                  <a
                    v-else
                    class="text-sm focus:outline-none"
                  >No Files Submitted</a>
                </div>
            </td>
        </tr>
      </tbody>
    </table>
    <ViewBookingRequestStatusModal
    :booking="bookingRequestToTrack"
    @close="bookingRequestToTrack = null"
    ></ViewBookingRequestStatusModal>
  </div>
</template>

<script>
import moment from "moment";
import ViewBookingRequestStatusModal from "@src/Pages/Admin/BookingRequests/ViewBookingRequestStatusModal";
import Button from "@src/Jetstream/Button";

export default {
  props: {
    bookings: {
      type: Array,
      default: [],
      required: true
    },
  },

  components: {
    Button,
    ViewBookingRequestStatusModal,
  },

  data() {
      return {
          filter: '',
          bookingRequestToTrack: null,
          bookingReference: '',
      }
  },
      methods: {
            formatDateTime(date) {
                return moment(date).format('h:mm a')
            },
            formatDateMonth(date) {
                return moment(date).format('MMM Do YYYY')
            },
            formatDateMonthRobust(date) {
                return moment(date).format('LLLL')
            },
            formatDateMonthRobust2(date) {
                return moment(date).format('llll')
            },
              setReference(e) {
                this.bookingReference = e.reference.path;
              }

        },
        computed: {
        href () {
          return '/bookings/download/' + this.bookingReference;
        },
          filteredBookings() {
            return this.bookings.filter(booking => {
              const number = booking.room.number.toLowerCase();
              const status = booking.status.toLowerCase();
              const name = booking.room.name.toLowerCase();
              const date1 = this.formatDateMonthRobust(booking.reservations[0].start_time).toLowerCase()
              const date2 = this.formatDateMonthRobust2(booking.reservations[0].start_time).toLowerCase()
              const searchTerm = this.filter.toLowerCase();

              return number.includes(searchTerm) ||
                name.includes(searchTerm) ||
                status.includes(searchTerm)||
                date1.includes(searchTerm)||
                date2.includes(searchTerm)
            });
          },
    }



};
</script>
