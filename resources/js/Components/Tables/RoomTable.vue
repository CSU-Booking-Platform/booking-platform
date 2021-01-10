<template>
  <div class="table-container">
    <div class="table-filter-container">
      <input type="text"
         placeholder="Search Rooms Table"
         v-model="filter" 
         />
    </div>
     
    <table class="table-auto responsive-spaced">
      <caption></caption>
      <thead>
        <tr>
          <th class="lt-grey" id="id_room_id">Room ID</th>
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
            <td class="text-center lt-grey">{{room.id}}</td>
            <td class="text-center lt-grey">{{room.name}}</td>
            <td class="text-center lt-grey">{{room.building}}</td>
            <td class="text-center lt-grey">{{room.number}}</td>
            <td class="text-center lt-grey">{{room.floor}}</td>
            <td class="text-center lt-grey">{{room.status}}</td>
            <td class="text-center lt-grey">Reference</td>
            <td class="text-center lt-grey">Action</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>

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
    
  },
  data() {
      return {
          filter: ''
      }
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
