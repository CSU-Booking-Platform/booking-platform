<template>
  <jet-dialog-modal :dusk="'createBookingModal'" :show="room" @close="closeModal" max-width="5/6">
    <template #content>
      <h2>Create a booking request</h2>
      <div class="mx-2 my-3">
        <jet-label for="name" value="Room"/>
        <jet-input
          id="room_id"
          type="text"
          class="mt-1 cursor-not-allowed block w-full bg-gray-100"
          :value="room_name"
          disabled
        />
        <jet-input-error :message="createBookingRequestForm.error('room_id')" class="mt-2"/>
      </div>
      <h3>Reservation(s):</h3>
      <!-- FOR EACH DATE -->
      <div v-for="(dates, index) in createBookingRequestForm.reservations" :key="index">
        <div class="flex flex-row">
          <!-- START TIME -->
          <div class="my-3 mx-2 flex-grow">
            <jet-label :for="'start_time_'+index" value="Start Time"/>
            <date-time-picker
              :id="'start_time_'+index"
              class="mt-1 block w-full"
              v-model="dates.start_time"
              autofocus
            />
            <jet-input-error :message="createBookingRequestForm.error('reservations.'+index+'.start_time')"
                             class="mt-2"/>
          </div>
          <!-- END TIME -->
          <div class="my-3 mx-2 flex-grow">
            <jet-label :for="'end_time_'+index" value="End Time"/>
            <date-time-picker
              :id="'end_time_'+index"
              class="mt-1 block w-full"
              v-model="dates.end_time"
              autofocus
            />
            <jet-input-error
              :message="createBookingRequestForm.error('reservations.'+index+'.start_time')"
              class="mt-2"
            />
            <jet-input-error :message="createBookingRequestForm.error('reservations.'+index+'.duration')" class="mt-2"/>
          </div>
          <div v-if="numDates > 1" class="my-3 mx-2 pt-4 flex items-center">
            <jet-danger-button class="m-1" @click.native="removeDate(index)">
              Delete
            </jet-danger-button>
          </div>
        </div>
        <jet-input-error :message="createBookingRequestForm.error('reservations.'+index)" class="mt-2"/>
      </div>

      <div class="my-3 mx-2 flex flex-row space-x-1">
        <jet-input v-model="recurrenceForm.recurrences" id="recurrence_quantity" type="number" min="1" />

        <x-select class="border-1" v-model="recurrenceForm.type">
          <option v-for="option in recurrenceOptions" :value="option.value"> {{ option.name }}</option>
        </x-select>

        <jet-secondary-button @click.native="addDates">
          Add Recurrences
        </jet-secondary-button>
      </div>

      <div class="m-6">
        <jet-input-error
          :message="createBookingRequestForm.error('availabilities')"
          class="mt-2"
        />
      </div>
      <div class="m-6">
        <jet-input-error
          :message="createBookingRequestForm.error('booking_request_exceeded')"
          class="mt-2"
        />
      </div>
    </template>

    <template #footer>
      <jet-secondary-button @click.native="closeModal">
        Nevermind
      </jet-secondary-button>

      <jet-button
        class="ml-2"
        @click.native="setDuration(); createBookingRequest();"
        :class="{ 'opacity-25': createBookingRequestForm.processing }"
        :disabled="createBookingRequestForm.processing"
        :id="'createBookingRequest'"
      >
        Create
      </jet-button>
    </template>
  </jet-dialog-modal>
</template>

<script>
import JetButton from "@src/Jetstream/Button";
import JetInput from "@src/Jetstream/Input";
import JetActionMessage from "@src/Jetstream/ActionMessage";
import JetFormSection from "@src/Jetstream/FormSection";
import JetInputError from "@src/Jetstream/InputError";
import JetLabel from "@src/Jetstream/Label";
import JetDialogModal from "@src/Jetstream/DialogModal";
import JetDropdown from "@src/Jetstream/Dropdown";
import JetDropdownLink from "@src/Jetstream/DropdownLink";
import JetNavLink from "@src/Components/Navbar/NavLink";
import JetSecondaryButton from "@src/Jetstream/SecondaryButton";
import DialogModal from "@src/Jetstream/DialogModal";
import Availabilities from "@src/Components/Availabilities";
import JetDangerButton from "@src/Jetstream/DangerButton";
import moment from "moment"
import DateTimePicker from "@src/Components/Form/DateTimePicker";
import XSelect from "@src/Components/Form/Select";

export const RECURRENCE_OPTIONS = [
  {
    name: 'daily',
    value: 'days'
  },
  {
    name: 'weekly',
    value: 'weeks'
  },
  {
    name: 'monthly',
    value: 'months'
  },
]

export default {
  components: {
    XSelect,
    DateTimePicker,
    DialogModal,
    JetButton,
    JetInput,
    JetFormSection,
    JetActionMessage,
    JetInputError,
    JetLabel,
    JetDropdown,
    JetDropdownLink,
    JetNavLink,
    JetDialogModal,
    JetSecondaryButton,
    JetDangerButton,
    Availabilities
  },

  props: {
    room: {
      type: Object,
      required: false
    }
  },
  computed: {
    numDates: function () {
      return this.createBookingRequestForm.reservations.length;
    },
  },
  data() {
    return {
      recurrenceForm: {
        recurrences: 2,
        type: "weeks"
      },
      recurrenceOptions: RECURRENCE_OPTIONS,
      createBookingRequestForm: this.$inertia.form(
        {
          room_id: null,
          reservations: [
            {
              start_time: "",
              end_time: "",
              duration: 0,
            }
          ],
          reference: [],
        },
        {
          bag: "createReservationsRequest",
          resetOnSuccess: true
        }
      ),
      room_name: ""
    };
  },
  methods: {
    closeModal() {
      this.createBookingRequestForm.room_id = null;
      this.createBookingRequestForm.reservations = [
        {
          start_time: "",
          end_time: "",
          duration: 0,
        }
      ];
      this.createBookingRequestForm.reference = [];
      this.$emit("close");
    },
    addDates() {
      let recurrences = parseInt(this.recurrenceForm.recurrences);
      let type = this.recurrenceForm.type;
      let referenceDate = this.createBookingRequestForm.reservations[0]

      if (referenceDate.start_time === "" || referenceDate.end_time === "")
        return

      for (let i = 1; i <= recurrences; i++) {
        this.createBookingRequestForm.reservations.push({
          start_time: moment(referenceDate.start_time, "YYYY-MM-DD HH:mm")
            .add(i, type)
            .format("YYYY-MM-DD HH:mm"),
          end_time: moment(referenceDate.end_time, "YYYY-MM-DD HH:mm")
            .add(i, type)
            .format("YYYY-MM-DD HH:mm"),
          duration: 0
        })
      }
    },
    addDate() {
      this.createBookingRequestForm.reservations.push({
        start_time: "",
        end_time: "",
        duration: 0,
      })
    },
    removeDate(pos) {
      this.createBookingRequestForm.reservations.splice(pos, 1)
    },
    createBookingRequest() {
      this.setLocalIsCreating(true);
      this.createBookingRequestForm.post("/bookings/create", {
        preserveScroll: true
      }).then(() => {
        if (!this.createBookingRequestForm.hasErrors()) {
          this.closeModal();
        } else {
          this.setLocalIsCreating(false);
        }
      });
    },
    setLocalIsCreating(val) {
      localStorage.isCreatingBooking = val;
    },
    setDuration() {
      for (let reservation of this.createBookingRequestForm.reservations) {
        let moment_start = moment(reservation.start_time);
        let moment_end = moment(reservation.end_time);

        reservation.duration = moment_end.diff(moment_start, 'minutes');
      }
    },
  },
  watch: {
    room(room) {
      this.createBookingRequestForm.room_id = room?.id;
      this.room_name = room?.name;
    },
  }
};
</script>
