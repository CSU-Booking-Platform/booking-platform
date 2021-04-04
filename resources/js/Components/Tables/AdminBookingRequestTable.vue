<template>
  <div class="table-container">
    <div class="table-filter-container mb-12 flex flex-row">
      <div class="ml-3 mr-6">
        <h3 class="font-black">All Booking Requests</h3>
      </div>
      <div class="border shadow-md">
        <input type="text" v-model="filter" placeholder="Search requests..."/>
      </div>
      <div class="bg-yellow-300 shadow-md">
        <em class="fas fa-search m-2"></em>
      </div>


      <!--vertical line-->
      <div class="border mx-16">
      </div>
      <div class="mx-6">
        <h3 class="font-black">FILTERS</h3>
      </div>
      <div class="mx-2 border shadow-md bg-yellow-300 min-w-24">
        <button @click="toggleAdvancedFilters">
          <em class="fas fa-filter mx-2 pt-2 max-w"></em>
        </button>
      </div>
    </div>

    <table class="table-auto responsive-spaced">
      <caption></caption>
      <thead>
      <tr>
        <th  id="id_booking_id">Booking ID</th>
        <th  id="id_created_by">Created By</th>
        <th  id="id_assigned_to">Assigned To</th>
        <th  id="id_status">Status</th>
        <th  id="id_date_created">Created</th>
        <th  id="id_last_updated">Last Updated</th>
        <th  id="id_action">Action</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="booking in filteredBookingRequests" :key="booking.id">
        <td class="text-center">{{booking.id}}</td>
        <td class="text-center">{{booking.requester.name}}</td>
        <td class="text-center">
          <div class="-space-x-4">
            <img v-for="reviewer in booking.reviewers " class="relative z-30 inline object-cover w-12 h-12 border-2 border-white rounded-full" :src="reviewer.profile_photo_url" :alt="reviewer.name"/>
          </div>
        </td>
        <td class="text-center capitalize">
          <span v-if="booking.status == 'Review'" class="rounded-full py-2 px-6 text-white text-center font-bold bg-yellow-300">
            {{ booking.status }}
          </span>
          <span v-if="booking.status == 'Approved'" class="rounded-full py-2 px-6 text-white text-center font-bold bg-green-600">
            {{ booking.status }}
          </span>
          <span v-if="booking.status == 'Refused'" class="rounded-full py-2 px-6 text-white text-center font-bold bg-red-700">
            {{ booking.status }}
          </span>
        </td>
        <td class="text-center">Created {{ booking.created_diff }}</td>
        <td class="text-center">Last Updated {{ booking.updated_diff }}</td>
        <td>
          <jet-responsive-nav-link
            :href="'/bookings/'+booking.id+'/review'"
            class="cursor-pointer ml-3 text-sm text-blue-800 focus:outline-none"
          >
            Open Details
          </jet-responsive-nav-link>
        </td>
      </tr>
      </tbody>
    </table>

    <jet-dialog-modal :show="showFilterModal">
      <template #title>
        Additional Booking Request Filters
      </template>

      <template #content>
        <div class="overflow-y-auto h-96 w-400">
          <div class="flex flex-row">
            <div class="flex flex-col flex-1 py-2 px-3">
              <div><h2>Status</h2></div>
              <div v-for="status in statuses" class="flex flex-row">
                <div><input type="checkbox" v-model="jsonFilters.status_list[status]"></div>
                <div class="capitalize text-sm text-gray-400 px-2">
                  {{status}}
                </div>
              </div>
            </div>
            <div class="flex flex-col flex-1 py-2 px-3">
              <div><h2>Reviewers</h2></div>
              <div class="flex flex-row">
                <multi-select :options="options" :selected-on-init="selected" @change="updateReviewers"/>
              </div>
            </div>
          </div>

          <div class="flex flex-row">
            <div class="flex flex-col flex-1 py-2 px-3">
              <div><h2>Date Created</h2></div>
              <div class="flex flex-col">
                <jet-label for="start_time" value="Before" />
                <date-time-picker
                  id="start_time"
                  class="mt-1 block w-full"
                  v-model="jsonFilters.date_range_start"
                  autofocus
                />
              </div>
              <div class="flex flex-col">
                <jet-label for="end_time" value="After" />
                <date-time-picker
                  id="end_time"
                  class="mt-1 block w-full"
                  v-model="jsonFilters.date_range_end"
                  autofocus
                />
              </div>
            </div>
          </div>
        </div>

      </template>

      <template #footer>
        <jet-button
          class="ml-2"
          @click.native="advancedFilters()"
        >
          Filter
        </jet-button>
        <jet-secondary-button @click.native="toggleAdvancedFilters">
          Close
        </jet-secondary-button>
      </template>
    </jet-dialog-modal>
  </div>
</template>
<script>

import JetDialogModal from "@src/Jetstream/DialogModal";
import JetSecondaryButton from "@src/Jetstream/SecondaryButton";
import JetDropdown from "@src/Jetstream/Dropdown";
import JetDropdownLink from "@src/Jetstream/DropdownLink";
import JetButton from "@src/Jetstream/Button";
import JetLabel from "@src/Jetstream/Label";
import JetResponsiveNavLink from "@src/Jetstream/ResponsiveNavLink"
import JetInput from "@src/Jetstream/Input";
import MultiSelect from "@src/Components/Form/MultiSelect";
import DateTimePicker from "@src/Components/Form/DateTimePicker";

export default {
  name: "AdminBookingRequestTable",
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
  components: {
    JetResponsiveNavLink,
    JetDropdownLink,
    JetDropdown,
    JetButton,
    JetDialogModal,
    JetSecondaryButton,
    JetLabel,
    JetInput,
    MultiSelect,
    DateTimePicker,
  },
  data() {
    return {
      filter: '',
      showFilterModal: false,
      selected: {},
      options: {},
      jsonFilters: {
        status_list: [],
        date_range_start: null,
        date_range_end: null,
        data_reviewers: []
      }
    }
  },
  mounted(){
    const status_zip = {};
    for (var i = 0; i < this.statuses.length; i++) {
      status_zip[this.statuses[i]] = false;
    }
    this.jsonFilters.status_list = status_zip ?? {};

  },
  created() {
      this.options = this.reduceUsersForSelect(this.reviewers);
  },
  computed: {
    filteredBookingRequests() {
      return this.bookings.filter(booking => {
        const requesterName = booking.requester.name.toLowerCase();
        const id = booking.id.toString();
        const status = booking.status.toLowerCase();
        const searchTerm = this.filter.toLowerCase();

        return id.includes(searchTerm)||
          requesterName.includes(searchTerm) ||
          id.includes(searchTerm) ||
          status.includes(searchTerm);

      });
    },
    activeJsonFilters: function () {
      let activeJsonFilters = {};
      for (let key of Object.keys(this.jsonFilters)) {
        if(this.jsonFilters[key]){
          activeJsonFilters[key] = this.jsonFilters[key];
        }
      }
      return activeJsonFilters;
    }
  },
  methods: {
    advancedFilters(){
        this.$emit('filterBookingRequestsJson', this.activeJsonFilters);
        this.toggleAdvancedFilters();
    },
    toggleAdvancedFilters(){
      this.showFilterModal = !this.showFilterModal;
    },
    reduceUsersForSelect(users) {
      return users.reduce(function(groups, item) {
        groups[item.id] = { text: item.name, icon: item.profile_photo_url };
        return groups;
      }, {})
    },
    updateReviewers(selected) {
      this.jsonFilters.data_reviewers = Object.entries(selected).map((e) => ( e[0] ));
    }
  },

};
</script>
