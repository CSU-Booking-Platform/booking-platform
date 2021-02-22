<template>
  <div class="table-container">
    <div class="table-filter-container mb-12 flex flex-row">
      <div class="ml-3 mr-6">
        <h3 class="font-black">ROOMS</h3>
      </div>
      <div class="mx-2 border shadow-md bg-yellow">
        <input type="text" v-model="filter">
          <i class="fas fa-search ml-1 mr-2"></i>
        </input>
      </div>   
    </div>
    <table class="table-auto responsive-spaced">
      <caption></caption>
      <thead>
        <tr>
          <th id="id_room_id">Room ID</th>
          <th id="id_room_type">Room Name</th>
          <th id="id_room_building">Building</th>
          <th id="id_room_number">Number</th>
          <th id="id_room_floor">Floor</th>
          <th id="id_room_availability">Availability</th>
          <th id="id_room_action">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="room in filterRooms" :key="room.id">
          <td class="text-center p-3">{{room.id}}</td>
          <td class="text-center p-3">{{room.name}}</td>
          <td class="text-center p-3">{{room.building}}</td>
          <td class="text-center p-3">{{room.number}}</td>
          <td class="text-center p-3">{{room.floor}}</td>
          <td
            @click="seeRoomAvailability = room"
            class="text-center p-3 underline">
            {{room.status}}
          </td>
          <td class="p-3">
            <div class="text-md mx-2">
              <jet-dropdown width="48">
                <template #trigger>
                  <button
                  class="flex text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
                  >
                  <span>Action</span>
                  </button>
                </template>

                <template #content>
                  <div class="text-md mx-3">
                    <button
                        class="cursor-pointer text-sm text-blue-800 focus:outline-none"
                        @click="roomBeingUpdated = room"
                    >
                        Update
                    </button>
                  </div>
                  <div class="text-md mx-3">
                    <jet-dropdown-link :href="'/admin/rooms/'+room.id+'/blackouts'">
                        <div class="cursor-pointer text-sm text-blue-800 focus:outline-none">
                          Blackout
                        </div>
                    </jet-dropdown-link>
                  </div>
                  <div class="text-md mx-2">
                    <button class="cursor-pointer text-sm text-blue-800 focus:outline-none"
                            @click="openEditRestrictionsModal(room)">
                      Restricted Roles
                    </button>
                  </div>
                  <div class="text-md mx-2">
                    <button class="cursor-pointer text-sm text-blue-800 focus:outline-none"
                            @click="openEditDateRestrictionsModal(room)">
                      Customize Role Date Restrictions
                    </button>
                  </div>
                  <div class="text-md mx-3">
                    <button
                        class="cursor-pointer text-sm text-red-800 focus:outline-none"
                        @click="roomBeingDeleted = room"
                    >
                        Delete
                    </button>
                  </div>
                </template>
              </jet-dropdown>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <update-room-form
        :room="roomBeingUpdated"
        :available-room-types="availableRoomTypes"
        @close="roomBeingUpdated = null">
    </update-room-form>

    <AvailabilitiesModal
      :room="seeRoomAvailability"
      @close="seeRoomAvailability = null"
    >
    </AvailabilitiesModal>

    <jet-confirmation-modal
      :show="roomBeingDeleted"
      @close="roomBeingDeleted = null"
    >
      <template #title>Delete Room</template>

      <template #content>Are you sure you would like to delete this room?</template>

      <template #footer>
        <jet-secondary-button @click.native="roomBeingDeleted = null">Nevermind</jet-secondary-button>

        <jet-danger-button id="deleteRoom"
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

    <jet-confirmation-modal v-if="roomDateRestrictionsBeingUpdated != null" :show="roomDateRestrictionsBeingUpdated != null" @close="roomDateRestrictionsBeingUpdated = null">
      <template #title>
          Update Room Date Restrictions: {{ roomDateRestrictionsBeingUpdated != null && roomDateRestrictionsBeingUpdated.name }}
      </template>

      <template #content>
        <!-- Custom Date Restrictions -->
        <div class="mt-2 col-span-12" v-if="roles.length > 0" >
          <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4" >
            <div v-for="role in roles">
              <div v-if="typeof updateRoomDateRestrictionsForm.date_restrictions[role.id] !== 'undefined'">
              <label class="flex items-center">
                <span class="ml-2 text-md text-black">{{ role.name }}</span>
              </label>
              <div class="col-span-6 sm:col-span-3">
              <jet-label :for="'min_days_advance_'+role.id" value="Minimum Days Before Booking"/>
                <jet-input :id="'min_days_advance_'+role.id"  class="mt-1 block w-full"
                           v-model="updateRoomDateRestrictionsForm.date_restrictions[role.id].min_days_advance"/>
                <jet-input-error :message="updateRoomDateRestrictionsForm.error('date_restrictions.'+role.id+'.min_days_advance')" class="mt-2"/>
              </div>

              <div class="col-span-6 sm:col-span-3">
                <jet-label :for="'max_days_advance_'+role.id" value="Maximum Days Before Booking"/>
                <jet-input :id="'max_days_advance_'+role.id"  class="mt-1 block w-full"
                           v-model="updateRoomDateRestrictionsForm.date_restrictions[role.id].max_days_advance"/>
                <jet-input-error :message="updateRoomDateRestrictionsForm.error('date_restrictions.'+role.id+'.max_days_advance')" class="mt-2"/>
              </div>
              </div>
            </div>
          </div>
        </div>
      </template>

      <template #footer>
          <jet-secondary-button @click.native="roomDateRestrictionsBeingUpdated = null">
              Nevermind
          </jet-secondary-button>

          <jet-button class="ml-2" @click.native="updateDateRestrictions"
                      :class="{ 'opacity-25': updateRoomDateRestrictionsForm.processing }"
                      :disabled="updateRoomDateRestrictionsForm.processing">
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
import JetDropdown from "@src/Jetstream/Dropdown";
import JetDropdownLink from "@src/Jetstream/DropdownLink";
import AvailabilitiesModal from "@src/Components/AvailabilitiesModal";

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
    AvailabilitiesModal,
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
        JetDropdown,
        JetDropdownLink
  },
  data() {
      return {
        deleteRoomForm: this.$inertia.form(),
        roomBeingUpdated: null,
        roomBeingDeleted: null,
        seeRoomAvailability: null,
        roomRestBeingUpdated: null,
        roomDateRestrictionsBeingUpdated: null,
        filter: '',
        updateRoomRestForm: this.$inertia.form({
            restrictions: [],
        }, {
            bag: 'updateRoomRestriction',
            resetOnSuccess: true,
        }),
        updateRoomDateRestrictionsForm: this.$inertia.form({
          date_restrictions: [],
        }, {
            bag: 'updateRoomDateRestrictions',
            resetOnSuccess: true,
        }),
      }
  },

  methods: {
    deleteRoom() {
        this.deleteRoomForm.delete('/admin/rooms/' + this.roomBeingDeleted.id, {
            preserveScroll: true,
            preserveState: true,
        }).then(() => {
            this.roomBeingDeleted = null
        })
    },

    openEditRestrictionsModal(room) {
        this.setSelectedRestrictions(room)
        this.roomRestBeingUpdated = room;
    },

    openEditDateRestrictionsModal(room) {
      this.setSelectedDateRestrictions(room)
      this.roomDateRestrictionsBeingUpdated = room;
    },

    setSelectedRestrictions(room) {
        this.updateRoomRestForm.restrictions = this.mapRoomRestrictions(room.restrictions)
    },

    setSelectedDateRestrictions(room) {
      this.updateRoomDateRestrictionsForm.date_restrictions = this.mapRoomDateRestrictions(room.date_restrictions)
    },

    mapRoomRestrictions(restrictions) {
        return restrictions.map((o) => {
            return o.id;
        });
    },

    mapRoomDateRestrictions(date_restrictions) {
      var rolesRes = this.roles;
      var finalRolesRes = [];
      rolesRes.forEach((role) => {
        if (typeof date_restrictions[role.id] !== 'undefined')
          finalRolesRes[role.id] = {min_days_advance:date_restrictions[role.id]['min'], max_days_advance:date_restrictions[role.id]['max']};
        else
          finalRolesRes[role.id] = {min_days_advance:null, max_days_advance:null};
      })
      return finalRolesRes;
    },

    updateRestrictions() {
      this.updateRoomRestForm.put('/admin/rooms/' + this.roomRestBeingUpdated.id + '/restrictions/', {
        preserveScroll: true,
        preserveState: true,
      }).then(() => {
        if (this.updateRoomRestForm.successful) {
          this.roomRestBeingUpdated = null;
        }
      });
    },

    updateDateRestrictions() {
      this.updateRoomDateRestrictionsForm.put('/admin/rooms/' + this.roomDateRestrictionsBeingUpdated.id + '/date-restrictions/', {
        preserveScroll: true,
        preserveState: true,
      }).then(() => {
        if (this.updateRoomDateRestrictionsForm.successful) {
          this.roomDateRestrictionsBeingUpdated = null;
        }
      });
    },
  },

  computed: {
    filterRooms() {
        return this.rooms.filter(room => {

            const building = room.building.toLowerCase();
            const status = room.status.toLowerCase();
            const name = room.name.toLowerCase();
            const floor = room.floor.toString();
            const number = room.number.toLowerCase();
            const id = room.id.toString();

            const search = this.filter.toLowerCase();

            return building.includes(search) ||
                    floor.includes(search) ||
                    name.includes(search) ||
                    status.includes(search) ||
                    number.includes(search) ||
                    id.includes(search);

        });
    }
  },
};
</script>
