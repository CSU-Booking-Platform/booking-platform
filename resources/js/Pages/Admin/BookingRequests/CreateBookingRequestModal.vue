<template>
    <jet-dialog-modal :show="room" @close="closeModal">
        <template #title>
            Create Booking Request
        </template>

        <template #content>
            <div class="m-6">
                <jet-label for="name" value="Room" />
                <jet-input
                    id="room_id"
                    type="text"
                    class="mt-1 block w-full"
                    :value="room_name"
                    disabled
                />
                <jet-input-error :message="createBookingRequestForm.error('room_id')" class="mt-2" />
            </div>

            <div class="m-6">
                <jet-label for="start_time" value="Start Time" />
                <jet-input
                    id="start_time"
                    type="datetime-local"
                    class="mt-1 block w-full"
                    v-model="createBookingRequestForm.start_time"
                    autofocus
                />
                <jet-input-error :message="createBookingRequestForm.error('start_time')" class="mt-2" />
            </div>

            <div class="m-6">
                <jet-label for="end_time" value="End Time" />
                <jet-input
                    id="end_time"
                    type="datetime-local"
                    class="mt-1 block w-full"
                    v-model="createBookingRequestForm.end_time"
                    autofocus
                />
                <jet-input-error
                    :message="createBookingRequestForm.error('end_time')"
                    class="mt-2"
                />
            </div>

            <div class="m-6">
                <jet-label>Upload Reference Files</jet-label>
                <input
                type="file"
                @change="fieldChange"
                multiple
                />
            </div>
            <div class="m-6">
                <jet-input-error
                    :message="createBookingRequestForm.error('availabilities')"
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
                @click.native="createBookingRequest"
                :class="{ 'opacity-25': createBookingRequestForm.processing }"
                :disabled="createBookingRequestForm.processing"
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

export default {
  components: {
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
    JetSecondaryButton
  },

  props: {
    room: {
      type: Object,
      required: false
    }
  },

  data() {
    return {
      createBookingRequestForm: this.$inertia.form(
        {
          room_id: null,
          start_time: "",
          end_time: "",
          reference: []
        },
        {
          bag: "createBookingRequest",
          resetOnSuccess: true
        }
      ),
      room_name: ""
    };
  },
  methods: {
    closeModal() {
        this.createBookingRequestForm.room_id = null;
        this.createBookingRequestForm.start_time = "";
        this.createBookingRequestForm.end_time = "";
        this.createBookingRequestForm.reference = [];
        this.$emit("close");
    },
    createBookingRequest() {
      this.createBookingRequestForm.post("/bookings", {
        preserveScroll: true
      }).then( () => {
        this.closeModal();
      });
    },
    fieldChange(e) {
      let selectedFiles = e.target.files;

      if (!selectedFiles.length) return false;

      for (let file of selectedFiles) {
        this.createBookingRequestForm.reference.push(file);
      }
    }
  },
  watch: {
        room(room) {
            this.createBookingRequestForm.room_id = room?.id;
            this.room_name = room?.name;
        }
    }
};
</script>
