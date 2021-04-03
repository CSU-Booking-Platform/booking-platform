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

      <!--vertical line-->
      <div class="border mx-16">
      </div>
      <div class="mx-6">
        <h3 class="font-black">Advanced filter</h3>
      </div>
      <div class="mx-2 border shadow-md bg-yellow-300 min-w-24">
        <button @click="toggleAdvancedFilters()">
          <em class="fas fa-filter mx-2 pt-2 max-w"></em>
        </button>
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
            <a v-if="booking.status == 'pending'" :href="'/bookings/'+booking.id+'/edit'" >
              <button class="h-10 px-5 m-2 text-gray-100 transition-colors duration-150 bg-gray-700 rounded-lg focus:shadow-outline hover:bg-gray-800">
                Edit
              </button>
            </a>
              <a v-else  :href="'/bookings/'+booking.id+'/view'" style="cursor:pointer;">
                <button v-if="booking.status == 'review'" aria-disabled="true" class="h-10 px-5 m-2 text-gray-100 transition-colors duration-150 bg-gray-500 rounded-lg focus:shadow-outline ">
                  In Review
                </button>
                <button v-else-if="booking.status == 'refused'" aria-disabled="true" class="h-10 px-5 m-2 text-gray-100 transition-colors duration-150 bg-red-900 rounded-lg focus:shadow-outline">
                  Refused
                </button>
                <button v-else-if="booking.status == 'approved'" aria-disabled="true" class="h-10 px-5 m-2 text-gray-100 transition-colors duration-150 bg-green-500 rounded-lg focus:shadow-outline">
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

    <jet-dialog-modal :show="showFilterModal">
      <template #title>
        Advanced Booking Request Filters
      </template>

      <template #content>
        <div class="overflow-y-auto h-60">
          <div class="flex flex-row">
            <div class="flex flex-col flex-1 py-2 px-3">
              <div class="flex flex-row">
                <div class="m-2">
                  <h2>
                    Status
                  </h2>
                </div>
                <div class="m-2">

                  <select v-model="jsonForm.selectStatus">
                    <option value="">None Selected</option>
                    <option v-for="(item) in uniqueStatuses" :value="item">
                      {{item}}
                    </option>
                  </select>
                </div>
              </div>
              <div class="flex flex-row">
                <div class="m-2">
                  <h2>
                    Check Date
                  </h2>
                </div>
                <div class="m-2">
                  <input id="date-check" type="date" class="mt-1 block" v-model="jsonForm.dateCheck"/>
                  <jet-secondary-button
                    class="ml-2"
                    @click.native="clearDate()"
                  >Clear Selected Date</jet-secondary-button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>

      <template #footer>
        <button
          class="ml-2"
          @click.native="advancedFilters()"
        >
          Filter
        </button>
        <jet-secondary-button @click.native="toggleAdvancedFilters">
          Close
        </jet-secondary-button>
      </template>
    </jet-dialog-modal>


  </div>
</template>

<script>
import moment from "moment";
import ViewBookingRequestStatusModal from "@src/Pages/Admin/BookingRequests/ViewBookingRequestStatusModal";
import Button from "@src/Jetstream/Button";
import JetDialogModal from "@src/Jetstream/DialogModal";
import JetSecondaryButton from "@src/Jetstream/SecondaryButton";
import Input from "@src/Jetstream/Input";

export default {
  props: {
    bookings: {
      type: Array,
      default: [],
      required: true
    },
  },

  components: {
    Input,
    Button,
    ViewBookingRequestStatusModal,
    JetDialogModal,
    JetSecondaryButton
  },

  data() {
      return {
          filter: '',
          bookingRequestToTrack: null,
          bookingReference: '',
          showFilterModal: false,
        jsonForm:{
          selectStatus: "",
          dateCheck: null
        }
      }
  },
      methods: {
            clearDate(){
              this.jsonForm.dateCheck = null
            },
            advancedFilters(){
              this.$emit('filterBookingsJson', this.jsonForm);
              this.toggleAdvancedFilters();
            },
            toggleAdvancedFilters(){
              this.showFilterModal = !this.showFilterModal;
            },
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
              },
      },
        computed: {
    uniqueStatuses: function (){
      var output = [];
      this.filteredBookings.forEach(function (booking) {
        var key = booking.status;
        if (output.indexOf(key) === -1) {
          output.push(key);
        }
      });
      return output;
    },
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
                date2.includes(searchTerm)||
                date1.includes(searchTerm)
            });
          },
    }

};
</script>
