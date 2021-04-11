<template>
  <div>
    <!-- View Rooms -->
    <div class="mt-10 sm:mt-0">
      <jet-action-section>
        <template #title>View Booking Requests</template>

        <template #description>Description of booking Requests.</template>

        <!-- Rooms List -->
        <template #content>
          <div class="space-y-6">
            <div class="grid grid-cols-8">
              <div class="text-md mx-3">User</div>
              <div class="text-md mx-3">Room</div>
              <div class="text-md mx-3">Start Time</div>
              <div class="text-md mx-3">End time</div>
              <div class="text-md mx-3">Status</div>
              <div class="text-md mx-3">Reference</div>
            </div>

            <div
              v-for="booking_request in booking_requests"
              :key="booking_request.id"
              class="grid flex items-center"
            >
<!--        Will list all reservations-->
              <div class="grid grid-cols-8" v-for="reservation in booking_request.reservations">
                <div class="text-md mx-2">{{ booking_request.requester.name }}</div>
                <div class="text-md mx-3">{{reservation.room.name}}</div>
                <div class="text-md mx-3">{{ $calendar(reservation.start_time) }}</div>
                <div class="text-md mx-3">{{ $calendar(reservation.end_time) }}</div>
                <div class="text-md mx-3">{{ booking_request.status }}</div>
                <div class="text-md mx-3">
                  <a v-if="booking_request.reference.path" :href="'/bookings/' + booking.id + '/download/'"
                     class="cursor-pointer text-sm text-blue-800 focus:outline-none"
                  >
                    Download
                  </a>
                  <a v-else class="text-sm focus:outline-none">No Files Submitted</a>
                </div>
                <div class="text-md mx-2">
                  <button
                    class="cursor-pointer text-sm text-blue-800 focus:outline-none"
                    @click="bookingRequestBeingUpdated = reservation"
                  >Update</button>
                </div>
                <div class="text-md mx-2">
                  <button @click="bookingRequestBeingDeleted = reservation"
                    class="cursor-pointer text-sm text-blue-800 focus:outline-none"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </div>

           <ViewBookingRequestStatusModal
            :booking_request="bookingRequestToTrack"
            @close="bookingRequestToTrack = null"
          ></ViewBookingRequestStatusModal>

          <UpdateBookingRequestForm
            :booking_request="bookingRequestBeingUpdated"
            :availableRooms="availableRooms"
            @close="bookingRequestBeingUpdated = null"
          ></UpdateBookingRequestForm>

          <jet-confirmation-modal
            :show="bookingRequestBeingDeleted"
            @close="bookingRequestBeingDeleted = null"
          >
            <template #title>Delete Booking Request</template>

            <template #content>Are you sure you would like to delete this booking request?</template>

            <template #footer>
              <jet-secondary-button @click.native="bookingRequestBeingDeleted = null">Nevermind</jet-secondary-button>

              <jet-danger-button
                class="ml-2"
                @click.native="deleteBookingRequest"
                :class="{ 'opacity-25': deleteBookingRequestForm.processing }"
                :disabled="deleteBookingRequestForm.processing"
              >Delete</jet-danger-button>
            </template>
          </jet-confirmation-modal>
        </template>
      </jet-action-section>
    </div>
  </div>
</template>

<script>
import JetSectionBorder from "@src/Jetstream/SectionBorder";
import JetActionSection from "@src/Jetstream/ActionSection";
import JetConfirmationModal from "@src/Jetstream/ConfirmationModal";
import JetSecondaryButton from "@src/Jetstream/SecondaryButton";
import JetDangerButton from "@src/Jetstream/DangerButton";
import JetButton from "@src/Jetstream/Button";
import JetModal from "@src/Jetstream/Modal";
import Dropdown from "@src/Jetstream/Dropdown";
import JetInput from "@src/Jetstream/Input";
import JetInputError from "@src/Jetstream/InputError";
import JetLabel from "@src/Jetstream/Label";
import ViewBookingRequestStatusModal from "./ViewBookingRequestStatusModal";

const moment= require('moment')

export default {
  props: {
    booking_requests: {
      type: Array,
      default: function() {
        return [];
      }
    },
    rooms: {
      type: Array,
      default: function() {
        return [];
      }
    }
  },

  components: {
    Dropdown,
    JetSectionBorder,
    JetActionSection,
    JetButton,
    JetDangerButton,
    JetSecondaryButton,
    JetConfirmationModal,
    JetModal,
    JetInput,
    JetLabel,
    JetInputError,
    ViewBookingRequestStatusModal
  },

  data() {
    return {
      deleteBookingRequestForm: this.$inertia.form(),
      bookingRequestToTrack: null,
      bookingRequestBeingUpdated: null,
      bookingRequestBeingDeleted: null
    };
  },

  methods: {
    deleteBookingRequest() {
      this.deleteBookingRequestForm
        .delete("/reservation/" + this.bookingRequestBeingDeleted.id, {
          preserveScroll: true,
          preserveState: true
        })
        .then(() => {
          this.bookingRequestBeingDeleted = null;
        });
    },
  },
  computed: {
        availableRooms: function () {
          return this.rooms.filter(room => room.status === "available");
        },
    }
};
</script>
