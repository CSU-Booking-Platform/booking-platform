<template>
  <div class="table-container">
     
    <table class="table-auto responsive-spaced">
      <caption></caption>
      <thead>
        <tr>
          <th class="lt-grey p-3" id="id_room_id">Room ID</th>
          <th class="lt-grey p-3" id="id_room_type">Room Type</th>
          <th class="lt-grey p-3" id="id_room_building">Building</th>
          <th class="lt-grey p-3" id="id_room_number">Number</th>
          <th class="lt-grey p-3" id="id_room_floor">Floor</th>
          <th class="lt-grey p-3" id="id_room_availability">Availability</th>
          <th class="lt-grey p-3" id="id_room_action">Update</th>
          <th class="lt-grey p-3" id="id_room_action">Delete</th>
          <th class="lt-grey p-3" id="id_room_action">Restricted Roles</th>
        </tr>
      </thead>
      <tbody>
         <tr v-for="room in filteredRooms" :key="room.id">
            <td class="text-center lt-grey p-3">{{room.id}}</td>
            <td class="text-center lt-grey p-3">{{room.name}}</td>
            <td class="text-center lt-grey p-3">{{room.building}}</td>
            <td class="text-center lt-grey p-3">{{room.number}}</td>
            <td class="text-center lt-grey p-3">{{room.floor}}</td>
            <td class="text-center lt-grey p-3">{{room.status}}</td>
            <td class="text-center lt-grey p-3">
              <button
                    class="h-5 m-2 text-blue-500 bg-lt-gray"
                    @click="roomBeingUpdated = room"
                  >Update
              </button>
            </td>
            <td class="text-center lt-grey p-3">
              <button
                    class="h-5 m-2 text-red-500 bg-lt-gray"
                    @click="roomBeingDeleted = room"
                  >Delete
              </button>
            </td>
            <td class="text-center lt-grey p-3">
              <button
                    class="h-5 m-2 text-blue-500 bg-lt-gray"
                    @click="openEditModal(room)"
                  >Restrict
              </button>
            </td>
        </tr>
      </tbody>
    </table>

    <update-room-form
        :room="roomBeingUpdated" 
        :available-room-types="availableRoomTypes" 
        @close="roomBeingUpdated = null">
    </update-room-form>

    <jet-confirmation-modal
      :show="roomBeingDeleted"
      @close="roomBeingDeleted = null"
    >
      <template #title>Delete Room</template>

      <template #content>Are you sure you would like to delete this room?</template>

      <template #footer>
        <jet-secondary-button @click.native="roomBeingDeleted = null">Nevermind</jet-secondary-button>

        <jet-danger-button
          class="ml-2"
          @click.native="deleteRoom"
          :class="{ 'opacity-25': deleteRoomForm.processing }"
          :disabled="deleteRoomForm.processing"
        >Delete</jet-danger-button>
      </template>
    </jet-confirmation-modal>

    <jet-confirmation-modal :show="roomRestBeingUpdated != null" @close="roomRestBeingUpdated = null">
      <template #title>
          Update Room Restrictions: {{ roomRestBeingUpdated != null && roomRestBeingUpdated.name }}
      </template>

      <template #content>

          <!-- Permissions -->
          <div class="mt-2 col-span-12" v-if="roles.length > 0">
              <jet-label for="restrictions" value="Restrictions"/>

              <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div v-for="role in roles">
                      <label class="flex items-center">
                          <input type="checkbox" class="form-checkbox" :value="role.id"
                                  v-model="updateRoomRestForm.restrictions">
                          <span class="ml-2 text-md text-black">{{ role.name }}</span>
                      </label>
                  </div>
              </div>
          </div>
      </template>

      <template #footer>
          <jet-secondary-button @click.native="roomRestBeingUpdated = null">
              Nevermind
          </jet-secondary-button>

          <jet-button class="ml-2" @click.native="updateRestrictions"
                      :class="{ 'opacity-25': updateRoomRestForm.processing }"
                      :disabled="updateRoomRestForm.processing">
              Update
          </jet-button>
      </template>
    </jet-confirmation-modal>

  </div>

</template>

<script>

import JetSectionBorder from '@src/Jetstream/SectionBorder'
import JetActionSection from '@src/Jetstream/ActionSection'
import JetConfirmationModal from '@src/Jetstream/ConfirmationModal'
import JetSecondaryButton from '@src/Jetstream/SecondaryButton'
import JetDangerButton from '@src/Jetstream/DangerButton'
import JetButton from '@src/Jetstream/Button'
import JetModal from '@src/Jetstream/Modal'
import Input from "@src/Jetstream/Input";
import Dropdown from "@src/Jetstream/Dropdown";
import JetInput from "@src/Jetstream/Input"
import JetInputError from "@src/Jetstream/InputError"
import JetLabel from "@src/Jetstream/Label"
import UpdateRoomForm from "@src/Pages/Admin/Rooms/UpdateRoomForm";
import Label from "@src/Jetstream/Label";

export default {
  name: "RoomTable",
  props: {

    rooms: {
      type: Array,
      default: [],
      required: true
    },

    roles: {
      type: Array,
      default: function () {
          return []
      },
    },

    availableRoomTypes: {
      type: Array,
      required: true
    },
  },
  components: {
        Label,
        Dropdown,
        Input,
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
        UpdateRoomForm,  
  },
  data() {
      return {
        deleteRoomForm: this.$inertia.form(),
        roomBeingUpdated: null,
        roomBeingDeleted: null,
        roomRestBeingUpdated: null,
        filter: '',
        updateRoomRestForm: this.$inertia.form({
            restrictions: [],
        }, {
            bag: 'updateRoomRestriction',
            resetOnSuccess: true,
        }),
      }
  },

  methods: {
    deleteRoom() {
        this.deleteRoomForm.delete('/rooms/' + this.roomBeingDeleted.id, {
            preserveScroll: true,
            preserveState: true,
        }).then(() => {
            this.roomBeingDeleted = null
        })
    },

    openEditModal(room) {
        this.setSelectedRestrictions(room)
        this.roomRestBeingUpdated = room;
    },

    setSelectedRestrictions(room) {
        this.updateRoomRestForm.restrictions = this.mapRoomRestrictions(room.restrictions)
    },

    mapRoomRestrictions(restrictions) {
        return restrictions.map((o) => {
            return o.id;
        });
    },
    updateRestrictions() {
        this.updateRoomRestForm.put('/room/restrictions/' + this.roomRestBeingUpdated.id, {
            preserveScroll: true,
            preserveState: true,
        }).then(() => {
            if (this.updateRoomRestForm.successful) {
                this.roomRestBeingUpdated = null;
            }
        });
    },
    
  },

  computed: {
    filteredRooms() {
        return this.rooms.filter(room => {

            
            const building = room.building.toLowerCase();
            const status = room.status.toLowerCase();
            const type = room.name.toLowerCase();
            const floor = room.floor.toString();
            const number = room.number.toLowerCase();
            const id = room.id.toString();

            const searchTerm = this.filter.toLowerCase();

            return building.includes(searchTerm) || 
                    floor.includes(searchTerm) || 
                    type.includes(searchTerm) ||
                    status.includes(searchTerm) ||
                    number.includes(searchTerm) ||
                    id.includes(searchTerm);

        });
    }
  },
};
</script>
