<template>
  <div class="table-container">
    <div class="table-filter-container">
      <input type="text"
         placeholder="Search Rooms Table"
         v-model="filter"
         />
        <button
            class="btn submit-btn"
            @click="toggleAdvancedFilters()"
        >Additional Search</button>
    </div>

    <table class="table-auto responsive-spaced">
      <caption></caption>
      <thead>
        <tr>
          <th class="lt-grey" id="id_room_id">Room Name</th>
          <th class="lt-grey" id="id_room_type">Room Type</th>
          <th class="lt-grey" id="id_room_building">Building</th>
          <th class="lt-grey" id="id_room_number">Number</th>
          <th class="lt-grey" id="id_room_floor">Floor</th>
          <th class="lt-grey" id="id_room_availability">Availability</th>
          <th class="lt-grey" id="id_room_reference">Reference</th>
          <th class="lt-grey" id="id_room_action">Action</th>
        </tr>
      </thead>
      <tbody>
         <tr v-for="room in filteredRooms" :key="room.id">
            <td class="text-center lt-grey">{{room.name}}</td>
            <td class="text-center lt-grey">{{room.room_type}}</td>
            <td class="text-center lt-grey">{{room.building}}</td>
            <td class="text-center lt-grey">{{room.number}}</td>
            <td class="text-center lt-grey">{{room.floor}}</td>
            <td class="text-center lt-grey">{{room.status}}</td>
            <td class="text-center lt-grey">Reference</td>
            <td class="lt-grey p-3">
             <div class="text-md mx-2">
               <jet-dropdown width="48">
                 <template #trigger>
                   <button
                     class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out mx-auto"
                   >
                     <div class="text-3xl">. . .</div>
                   </button>
                 </template>

                 <template #content>
                   <div class="text-md mx-3">
                     <button
                       class="cursor-pointer text-sm text-blue-800 focus:outline-none"
                       @click="roomBeingBooked = room"
                     >
                       Create Booking
                     </button>
                   </div>
                   <div class="text-md mx-3">
                     <button
                       class="cursor-pointer text-sm text-blue-800 focus:outline-none"
                       @click="roomBeingInspected = room"
                     >
                       View Details
                     </button>
                   </div>
                 </template>
               </jet-dropdown>
             </div>
           </td>
        </tr>
      </tbody>
    </table>

    <CreateBookingRequestModal
      :room="roomBeingBooked"
      @close="roomBeingBooked = null"
    ></CreateBookingRequestModal>

    <RoomDetailedView
      :room="roomBeingInspected"
      @close="roomBeingInspected = null"
    ></RoomDetailedView>

      <jet-dialog-modal :show="showFilterModal">
          <template #title>
              Additional Room Filters
          </template>

          <template #content>
            <div class="overflow-y-auto h-96">
              <div class="flex flex-row">
                  <div class="flex flex-col flex-1 py-2 px-3">
                      <div><h2>Capacity Standing</h2></div>
                      <div class="flex flex-row">
                          <input
                              type="number"
                              id="capacity_standing"
                              class="form-input w-2/3 h-2/3"
                              v-model.number="jsonFilters.capacity_standing"
                          >
                      </div>
                  </div>
                  <div class="flex flex-col flex-1 py-2 px-3">
                      <div><h2>Capacity Sitting</h2></div>
                      <div class="flex flex-row">
                          <input
                              type="number"
                              id="capacity_sitting"
                              class="form-input w-2/3 h-2/3"
                              v-model.number="jsonFilters.capacity_sitting"
                          >
                      </div>
                  </div>
                  <div class="flex flex-col flex-1 py-2 px-3">
                      <div><h2>Coffee Tables</h2></div>
                      <div class="flex flex-row">
                          <input
                              type="number"
                              id="coffee_tables"
                              class="form-input w-2/3 h-2/3"
                              v-model.number="jsonFilters.coffee_tables"
                          >
                      </div>
                  </div>
              </div>

              <div class="flex flex-row">
                  <div class="flex flex-col flex-1 py-2 px-3">
                      <div><h2>Tables</h2></div>
                      <div class="flex flex-row">
                          <input
                              type="number"
                              id="tables"
                              class="form-input w-2/3 h-2/3"
                              v-model.number="jsonFilters.tables"
                          >
                      </div>
                  </div>
                  <div class="flex flex-col flex-1 py-2 px-3">
                      <div><h2>Chairs</h2></div>
                      <div class="flex flex-row">
                          <input
                              type="number"
                              id="chairs"
                              class="form-input w-2/3 h-2/3"
                              v-model.number="jsonFilters.chairs"
                          >
                      </div>
                  </div>
                  <div class="flex flex-col flex-1 py-2 px-3">
                      <div><h2>Sofas</h2></div>
                      <div class="flex flex-row">
                          <input
                              type="number"
                              id="sofas"
                              class="form-input w-2/3 h-2/3"
                              v-model.number="jsonFilters.sofas"
                          >
                      </div>
                  </div>
              </div>

              <div class="flex flex-row">
                  <div class="flex flex-col flex-1 py-2 px-3">
                      <div><h2>Electronics</h2></div>
                      <div class="flex flex-row">
                          <div><input type="checkbox"></div>
                          <div class="text-sm text-gray-400 px-2">
                              Computers
                          </div>
                      </div>
                      <div class="flex flex-row">
                          <div><input type="checkbox"></div>
                          <div class="text-sm text-gray-400 px-2">
                              Television
                          </div>
                      </div>
                      <div class="flex flex-row">
                          <div><input type="checkbox" v-model="jsonFilters.whiteboard"></div>
                          <div class="text-sm text-gray-400 px-2">
                              Whiteboard
                          </div>
                      </div>
                      <div class="flex flex-row">
                          <div><input type="checkbox" v-model="jsonFilters.projector"></div>
                          <div class="text-sm text-gray-400 px-2">
                              Projector
                          </div>
                      </div>
                  </div>
                  <div class="flex flex-col flex-1 py-2 px-3">
                      <div><h2>Amenities Permitted</h2></div>
                      <div class="flex flex-row">
                          <div><input type="checkbox" v-model="jsonFilters.food"></div>
                          <div class="text-sm text-gray-400 px-2">
                              Food
                          </div>
                      </div>
                      <div class="flex flex-row">
                          <div><input type="checkbox" v-model="jsonFilters.alcohol"></div>
                          <div class="text-sm text-gray-400 px-2">
                              Alcohol
                          </div>
                      </div>
                      <div class="flex flex-row">
                          <div><input type="checkbox" v-model="jsonFilters.a_v_permitted"></div>
                          <div class="text-sm text-gray-400 px-2">
                              AV
                          </div>
                      </div>
                      <div class="flex flex-row">
                          <div><input type="checkbox" v-model="jsonFilters.ambiant_music"></div>
                          <div class="text-sm text-gray-400 px-2">
                              Ambient Music
                          </div>
                      </div>
                  </div>
                  <div class="flex flex-col flex-1 py-2 px-3">
                      <div><h2>Event Type</h2></div>
                      <div class="flex flex-row">
                          <div><input type="checkbox" v-model="jsonFilters.sale_for_profit"></div>
                          <div class="text-sm text-gray-400 px-2">
                              Sales for Profit
                          </div>
                      </div>
                      <div class="flex flex-row">
                          <div><input type="checkbox" v-model="jsonFilters.fundraiser"></div>
                          <div class="text-sm text-gray-400 px-2">
                              Fundraiser
                          </div>
                      </div>
                  </div>
              </div>

              <div class="flex flex-row">
                <div class="flex flex-col flex-1 py-2 px-3">
                  <div class="flex flex-row">
                    <div class="m-2">
                      <h2>
                        Availabilities
                      </h2>
                    </div>
                    <div class="m-2">
                      <jet-secondary-button @click.native="addDate">
                        Add Date
                      </jet-secondary-button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="flex flex-row">
                <div class="flex flex-col flex-1 py-2 px-3 m-2" v-if="missingDates">
                <jet-input-error
                  :message="'Please make sure all date fields are entered'"
                  class="mt-2"
                />
              </div>
              </div>
              <div class="flex flex-wrap grid grid-cols-2 gap-4">
                <div v-for="(dates, index) in jsonFilters.recurrences" :key="index">
                  <div class="m-6">
                    <jet-label for="start_time" value="Start Time" />
                    <jet-input
                      id="start_time"
                      type="datetime-local"
                      class="mt-1 block w-full"
                      v-model="dates.start_time"
                      autofocus
                    />
                  </div>

                  <div class="m-6">
                    <jet-label for="end_time" value="End Time" />
                    <jet-input
                      id="end_time"
                      type="datetime-local"
                      class="mt-1 block w-full"
                      v-model="dates.end_time"
                      autofocus
                    />
                  </div>

                  <div class="m-6">
                    <jet-secondary-button v-if="numDates > 0" @click.native="removeDate(index)">
                      Remove
                    </jet-secondary-button>
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

import CreateBookingRequestModal from "@src/Pages/Admin/BookingRequests/CreateBookingRequestModal";
import Button from "@src/Jetstream/Button";
import JetDialogModal from "@src/Jetstream/DialogModal";
import JetSecondaryButton from "@src/Jetstream/SecondaryButton";
import Label from "@src/Jetstream/Label";
import JetInput from "@src/Jetstream/Input";
import Input from "@src/Jetstream/Input";
import JetDropdown from "@src/Jetstream/Dropdown";
import JetDropdownLink from "@src/Jetstream/DropdownLink";
import RoomDetailedView from "@src/Components/RoomDetailedView";
import JetButton from "@src/Jetstream/Button";
import JetLabel from "@src/Jetstream/Label";
import JetInputError from "@src/Jetstream/InputError";

export default {
  name: "RoomTable",
  props: {
    rooms: {
      type: Array,
      default: [],
      required: true
    },
  },
  components: {
      Input,
      Button,
      CreateBookingRequestModal,
      JetDialogModal,
      JetSecondaryButton,
      Label,
      JetInput,
      JetDropdown,
      JetDropdownLink,
      RoomDetailedView,
      JetButton,
      JetLabel,
      JetInputError
  },
  data() {
      return {
          filter: '',
          roomBeingBooked: null,
          roomBeingInspected: null,
          jsonFilters: {
              capacity_standing: null,
              capacity_sitting: null,
              coffee_tables: null,
              tables: null,
              chairs: null,
              sofas: null,
              computers: false,
              television: false,
              whiteboard: false,
              projector: false,
              food: false,
              alcohol: false,
              a_v_permitted: false,
              ambiant_music: false,
              sale_for_profit: false,
              fundraiser: false,
              recurrences:[]
          },
        showFilterModal: false,
      }
  },
    computed: {
        filteredRooms() {
            return this.rooms.filter(room => {


                const building = room.building.toLowerCase();
                const status = room.status.toLowerCase();
                const type = room.room_type.toLowerCase();
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
        },
        activeJsonFilters: function () {
            let activeJsonFilters = {};
            for (let key of Object.keys(this.jsonFilters)) {
                if(this.jsonFilters[key]){
                    activeJsonFilters[key] = this.jsonFilters[key];
                }
            }
            return activeJsonFilters;
        },
        numDates: function() {
          return this.jsonFilters.recurrences.length;
        },
        missingDates: function(){
          if(this.numDates > 0){
            for(let i = 0; i < this.jsonFilters.recurrences.length; i++){
              console.log(this.jsonFilters.recurrences[i].start_time)
              if(!this.jsonFilters.recurrences[i].start_time || !this.jsonFilters.recurrences[i].end_time) {
                return true;
              }
            }
            return false;
          }else{
            return false;
          }
        }
    },
    methods: {
        advancedFilters(){
          if(!this.missingDates) {
            this.$emit('filterRoomsJson', this.activeJsonFilters);
            this.toggleAdvancedFilters();
          }
        },
        toggleAdvancedFilters(){
          this.showFilterModal = !this.showFilterModal;
        },
        removeDate(pos) {
          this.jsonFilters.recurrences.splice(pos,1)
        },
        addDate() {
          this.jsonFilters.recurrences.push({
            start_time: "",
            end_time: "",
          })
        }
    },

};
</script>
