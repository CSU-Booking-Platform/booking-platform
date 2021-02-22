<template>
  <div class="table-container">
    <div class="table-filter-container">
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
        <tr v-for="booking in bookings" :key="booking.id">
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
            <a :href="'/bookings/'+booking.id+'/edit'">
              <button class="h-10 px-5 m-2 text-gray-100 transition-colors duration-150 bg-gray-700 rounded-lg focus:shadow-outline hover:bg-gray-800">
                Edit
              </button>
            </a>
            </td>
            <td class="text-center">
            <div class="text-md mx-3">
                  <a
                    v-if="booking.reference.path"
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

export default {
  props: {
    bookings: {
      type: Array,
      default: [],
      required: true
    },
  },

  components: {
    ViewBookingRequestStatusModal,
  },

  data() {
      return {
          filter: '',
          roomBeingBooked: null,
          bookingRequestToTrack: null,
          bookingReference: '',
      }
  },
      methods: {
            formatDateTime(date) {
                return moment(date).format('h:mm:ss a')
            },
            formatDateMonth(date) {
                return moment(date).format('MMM Do YYYY')
            },
              setReference(e) {
                this.bookingReference = e.reference.path;
              }

        },
        computed: {
        href () {
          return '/bookings/download/' + this.bookingReference;
        },
    }



};
</script>
