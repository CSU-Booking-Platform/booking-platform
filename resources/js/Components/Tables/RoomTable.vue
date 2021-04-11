<template>
  <div class="table-container">
    <div class="table-filter-container mb-12 flex flex-row">
      <div class="ml-3 mr-6">
        <h3 class="font-black">ROOMS</h3>
      </div>
      <div class="border shadow-md">
        <input id="search-rooms" type="text" v-model="filter"/>
      </div>
      <div class="bg-yellow-300 shadow-md">
        <em class="fas fa-search m-2"></em>
      </div>


      <!--vertical line-->
      <div class="border mx-16">
      </div>
      <div class="mx-6">
        <h3 class="font-black">FILTER</h3>
      </div>
      <div class="mx-2 border shadow-md bg-yellow-300 min-w-24">
        <button :dusk="'toggle-advanced-filters'" @click="toggleAdvancedFilters()">
          <em class="fas fa-filter mx-2 pt-2 max-w"></em>
        </button>
      </div>
    </div>

    <ul class="list-reset flex border-b">
      <li v-if="listViewSelected" class="-mb-px mr-1">
        <a @click="listViewSelected = true"
          class="bg-white inline-block border-l border-t border-r rounded-t py-2 px-4 text-blue-dark font-semibold"
          href="#">List View</a
        >
      </li>
      <li v-else class="mr-1">
        <a @click="listViewSelected = true"
          class="bg-white inline-block py-2 px-4 text-blue hover:text-blue-darker font-semibold"
          href="#">List View</a
        >
      </li>
      <li v-if="listViewSelected" class="mr-1">
        <a @click="listViewSelected = false"
          class="bg-white inline-block py-2 px-4 text-blue hover:text-blue-darker font-semibold"
          href="#">Calendar View</a
        >
      </li>
      <li v-else class="-mb-px mr-1">
        <a @click="listViewSelected = false"
          class="bg-white inline-block border-l border-t border-r rounded-t py-2 px-4 text-blue-dark font-semibold"
          href="#">Calendar View</a
        >
      </li>
    </ul>

    <table v-if="listViewSelected" class="table-auto responsive-spaced mt-4">
      <caption></caption>
      <thead>
        <tr>
          <th @click="sort('name')" id="id_room_id" class="cursor-pointer">
            Room Name
            <span v-if="currentSort == 'name'">
              <span v-if="currentSortDir == 'asc'">
                &blacktriangle;
              </span>
              <span v-else>
                &blacktriangledown;
              </span>
            </span>
            <span v-else>
              &blacktriangledown;
            </span>
          </th>
          <th @click="sort('room_type')"  id="id_room_type" class="cursor-pointer">
            Room Type
            <span v-if="currentSort == 'room_type'">
              <span v-if="currentSortDir == 'asc'">
                &blacktriangle;
              </span>
              <span v-else>
                &blacktriangledown;
              </span>
            </span>
            <span v-else>
              &blacktriangledown;
            </span>
          </th>
          <th @click="sort('building')" id="id_room_building" class="cursor-pointer">
            Building
            <span v-if="currentSort == 'building'">
              <span v-if="currentSortDir == 'asc'">
                &blacktriangle;
              </span>
              <span v-else>
                &blacktriangledown;
              </span>
            </span>
            <span v-else>
              &blacktriangledown;
            </span>
          </th>
          <th @click="sort('number')" id="id_room_number" class="cursor-pointer">
            Number
            <span v-if="currentSort == 'number'">
              <span v-if="currentSortDir == 'asc'">
                &blacktriangle;
              </span>
              <span v-else>
                &blacktriangledown;
              </span>
            </span>
            <span v-else>
              &blacktriangledown;
            </span>
          </th>
          <th @click="sort('floor')" id="id_room_floor" class="cursor-pointer">
            Floor
            <span v-if="currentSort == 'floor'">
              <span v-if="currentSortDir == 'asc'">
                &blacktriangle;
              </span>
              <span v-else>
                &blacktriangledown;
              </span>
            </span>
            <span v-else>
              &blacktriangledown;
            </span>

          </th>
          <th @click="sort('attributes.capacity_sitting')" id="id_room_capacity" class="cursor-pointer">
            Seating Capacity
            <span v-if="currentSort == 'attributes.capacity_sitting'">
              <span v-if="currentSortDir == 'asc'">
                &blacktriangle;
              </span>
              <span v-else>
                &blacktriangledown;
              </span>
            </span>
            <span v-else>
              &blacktriangledown;
            </span>
          </th>
          <th id="id_room_action">Action</th>
        </tr>
      </thead>
      <tbody>
         <tr v-for="room in filteredRooms" :key="room.id" :id="'room-item-' + room.id" :dusk="'room-item-' + room.id">
            <td class="text-center">{{room.name}}</td>
            <td class="text-center">{{room.room_type}}</td>
            <td class="text-center">{{room.building}}</td>
            <td class="text-center">{{room.number}}</td>
            <td class="text-center">{{room.floor}}</td>
            <td class="text-center">{{room.attributes.capacity_sitting}}</td>
            <td class="text-center space-x-2">
              <jet-button id="create" :dusk="'room-select-' + room.id"
                      @click.native="roomBeingBooked = room"
              >
                Create Booking
              </jet-button>

              <jet-button @click.native="roomBeingInspected = room">
                View Details
              </jet-button>
            </td>
        </tr>
      </tbody>
    </table>


    <div v-if="!listViewSelected">
      <CalendarViewTable dusk="calendar-view-table" />
    </div>

    <CreateBookingRequestModal
      :room="roomBeingBooked"
      @close="roomBeingBooked = null"
    ></CreateBookingRequestModal>

    <AvailabilitiesModal
      :room="seeRoomAvailability"
      @close="seeRoomAvailability = null"
    >
    </AvailabilitiesModal>

    <RoomDetailedView
      :room="roomBeingInspected"
      @close="roomBeingInspected = null"
    ></RoomDetailedView>

      <jet-dialog-modal :show="showFilterModal">
          <template #title>
              Additional Room Filters
          </template>

          <template #content>
            <div class="overflow-y-auto">
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
                          <div><input type="checkbox" :dusk="'computers'"></div>
                          <div class="text-sm text-gray-400 px-2">
                              Computers
                          </div>
                      </div>
                      <div class="flex flex-row">
                          <div><input type="checkbox" :dusk="'televisions'"></div>
                          <div class="text-sm text-gray-400 px-2">
                              Television
                          </div>
                      </div>
                      <div class="flex flex-row">
                          <div><input type="checkbox" :dusk="'whiteboards'" v-model="jsonFilters.whiteboard"></div>
                          <div class="text-sm text-gray-400 px-2">
                              Whiteboard
                          </div>
                      </div>
                      <div class="flex flex-row">
                          <div><input type="checkbox" :dusk="'projectors'" v-model="jsonFilters.projector"></div>
                          <div class="text-sm text-gray-400 px-2">
                              Projector
                          </div>
                      </div>
                  </div>
                  <div class="flex flex-col flex-1 py-2 px-3">
                      <div><h2>Amenities Permitted</h2></div>
                      <div class="flex flex-row">
                          <div><input type="checkbox" :dusk="'food'" v-model="jsonFilters.food"></div>
                          <div class="text-sm text-gray-400 px-2">
                              Food
                          </div>
                      </div>
                      <div class="flex flex-row">
                          <div><input type="checkbox" :dusk="'alcohol'" v-model="jsonFilters.alcohol"></div>
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
                      <jet-secondary-button @click.native="addDate" id="filter-add-date">
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
              <div class="flex flex-wrap grid grid-cols-2 gap-4 max-h-72">
                <div v-for="(dates, index) in jsonFilters.recurrences" :key="index">
                  <div class="m-6">
                    <jet-label for="start_time" value="Start Time" />
                    <date-time-picker
                      :id="'start_time-'+index"
                      class="mt-1 block w-full"
                      v-model="dates.start_time"
                      autofocus
                    />
                  </div>

                  <div class="m-6">
                    <jet-label for="end_time" value="End Time" />
                    <date-time-picker
                      :id="'end_time-'+index"
                      class="mt-1 block w-full"
                      v-model="dates.end_time"
                      autofocus
                    />
                  </div>

                  <div class="m-6">
                    <jet-secondary-button v-if="numDates > 0" @click.native="removeDate(index)" :id="'filter-remove-date-'+index">
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
                id="filter-rooms"
              >
                  Filter
              </jet-button>
              <jet-secondary-button @click.native="toggleAdvancedFilters">
                  Close
              </jet-secondary-button>
          </template>
      </jet-dialog-modal>

    <div class="pt-3 px-2">
      <Paginator :paginator="paginator" />
    </div>

  </div>
</template>
<script>

import CreateBookingRequestModal from "@src/Pages/Admin/BookingRequests/CreateBookingRequestModal";
import JetDialogModal from "@src/Jetstream/DialogModal";
import JetSecondaryButton from "@src/Jetstream/SecondaryButton";
import JetInput from "@src/Jetstream/Input";
import JetDropdown from "@src/Jetstream/Dropdown";
import JetDropdownLink from "@src/Jetstream/DropdownLink";
import RoomDetailedView from "@src/Components/RoomDetailedView";
import JetButton from "@src/Jetstream/Button";
import JetLabel from "@src/Jetstream/Label";
import JetInputError from "@src/Jetstream/InputError";
import AvailabilitiesModal from "@src/Components/AvailabilitiesModal";
import CalendarViewTable from "@src/Components/Tables/CalendarViewTable";
import DateTimePicker from "@src/Components/Form/DateTimePicker";
import Paginator from "@src/Components/Paginator";

export default {
  name: "RoomTable",
  props: {
    rooms: {
      type: Array,
      default: [],
      required: true
    },
    paginator: Object
  },
  components: {
      DateTimePicker,
      CreateBookingRequestModal,
      JetDialogModal,
      JetSecondaryButton,
      JetInput,
      JetDropdown,
      JetDropdownLink,
      RoomDetailedView,
      JetButton,
      JetLabel,
      JetInputError,
      AvailabilitiesModal,
      CalendarViewTable,
      Paginator
  },
  data() {
      return {
          filter: '',
          roomBeingBooked: null,
          roomBeingInspected: null,
          seeRoomAvailability: null,
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
        currentSort: 'name',
        currentSortDir: 'asc',
        listViewSelected: true
      }
  },
    computed: {
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
            for(let recurrence of this.jsonFilters.recurrences){
              if(!recurrence.start_time || !recurrence.end_time) {
                return true;
              }
            }
            return false;
          }else{
            return false;
          }
        },
        sortedRooms:function() {
          return this.paginator.data.sort((a,b) => {
            let modifier = 1;
            if(this.currentSortDir === 'desc') modifier = -1;
            if(this.currentSort.includes('attributes')){
              if(parseInt(a['attributes'][this.currentSort.split(".")[1]]) < parseInt(b['attributes'][this.currentSort.split(".")[1]])) return -1 * modifier;
              if(parseInt(a['attributes'][this.currentSort.split(".")[1]])> parseInt(b['attributes'][this.currentSort.split(".")[1]])) return 1 * modifier;
            }else{
              if(a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
              if(a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
            }
            return 0;
          });
        },
        filteredRooms:function(){
          return this.sortedRooms.filter(room => {
            const building = room.building.toLowerCase();
            const status = room.status.toLowerCase();
            const type = room.room_type.toLowerCase();
            const floor = room.floor.toString();
            const number = room.number.toLowerCase();
            const name = room.name.toLowerCase();

            const searchTerm = this.filter.toLowerCase();

            return building.includes(searchTerm) ||
              floor.includes(searchTerm) ||
              type.includes(searchTerm) ||
              status.includes(searchTerm) ||
              number.includes(searchTerm) ||
              name.includes(searchTerm);

          });
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
        },
        sort:function(s) {
          //if s == current sort, reverse
          if(s === this.currentSort) {
            this.currentSortDir = this.currentSortDir==='asc'?'desc':'asc';
          }
          this.currentSort = s;
        },
    },

};
</script>
