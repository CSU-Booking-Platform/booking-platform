<template>
  <div>
    <jet-form-section @submitted="createBookingRequest">
      <template #title>Create a Booking Request</template>

      <template #form>
        <div class="col-span-6 sm:col-span-3">
          <div class="m-6">
            <jet-label for="room_id" value="Room" />
            <select v-model="createBookingRequestForm.room_id" class="mt-1 block w-full" name="availableRooms" id="availableRooms">
              <option :value="null" selected="selected">Choose here</option>
              <option v-for="room in availableRooms" :key="room.id" :value="room.id">{{room.name}}</option>
            </select>
            <jet-input-error :message="createBookingRequestForm.error('room_id')" class="mt-2" />
          </div>

          <div class="m-6">
            <jet-label for="start_time" value="Start Time" />
            <jet-input
              id="start_time"
              type="datetime-local"
              class="mt-1 block w-full"
              v-model="createBookingRequestForm.start_time"
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
            />
            <jet-input-error :message="createBookingRequestForm.error('end_time')" class="mt-2" />
          </div>

          <div class="m-6 form-group">
            <jet-label>Upload Reference Files</jet-label>
            <input 
              type="file" 
              @change="fieldChange"
              multiple  
            >
          </div>         
          <div class="m-6">
            <jet-input-error :message="createBookingRequestForm.error('availabilities')" class="mt-2" />
          </div>
        </div>
      </template>

      <template #actions>
        <jet-action-message :on="createBookingRequestForm.recentlySuccessful" class="mr-3">Created.</jet-action-message>

        <jet-button
          :class="{ 'opacity-25': createBookingRequestForm.processing }"
          :disabled="createBookingRequest.processing"
        >Create</jet-button>
      </template>
    </jet-form-section>
  </div>
</template>

<script>
import JetButton from "@src/Jetstream/Button";
import JetInput from "@src/Jetstream/Input";
import JetActionMessage from "@src/Jetstream/ActionMessage";
import JetFormSection from "@src/Jetstream/FormSection";
import JetInputError from "@src/Jetstream/InputError";
import JetLabel from "@src/Jetstream/Label";

import JetDropdown from "@src/Jetstream/Dropdown";
import JetDropdownLink from "@src/Jetstream/DropdownLink";
import JetNavLink from "@src/Jetstream/NavLink";

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
    JetNavLink
  },

  props: {
    availableRooms: {
      type: Array,
      default: function() {
        return [];
      }
    }
  },

  data() {
    return {
      createBookingRequestForm: this.$inertia.form(
        {
          room_id: null,
          start_time: "",
          end_time: "",
          reference: [],
        },
        {
          bag: "createBookingRequest",
          resetOnSuccess: true
        }
      ),

    };
  },
  methods: {
    createBookingRequest() {
      this.createBookingRequestForm.post("/bookings", {
        preserveScroll: true
      });
    },
    fieldChange(e){
      let selectedFiles = e.target.files;

      if(!selectedFiles.length)
        return false;

      for(let file of selectedFiles)
      {
        this.createBookingRequestForm.reference.push(file);
      }
    }    
  }
};
</script>
