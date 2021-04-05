<template>
  <app-layout>
    <div class="mt-12 flex flex-col items-center lg:flex-row mx-10">
      <div class="m-4 p-2">
        <create-room-form :available-room-types="availableRoomTypes"
                          :available-buildings="availableBuildings"/>
      </div>
      <div v-if="rooms.length > 0" class="px-3 ">
        <ViewActiveRoomsTable :rooms="rooms" :available-room-types="availableRoomTypes"
                              :available-buildings="availableBuildings" :roles="roles"/>
      </div>
    </div>
  </app-layout>
</template>

<script>
import JetSectionBorder from '@src/Jetstream/SectionBorder'
import CreateRoomForm from './CreateRoomForm';
import AppLayout from '@src/Layouts/AppLayout';
import ViewActiveRoomsTable from '@src/Components/Tables/ViewActiveRoomsTable';

export default {
  components: {
    CreateRoomForm,
    AppLayout,
    JetSectionBorder,
    ViewActiveRoomsTable,
  },
  props: {
    rooms: {
      type: Array,
      default: function () {
        return []
      },
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
    availableBuildings: {
      type: Array,
      required: true
    },
  }
}
</script>
