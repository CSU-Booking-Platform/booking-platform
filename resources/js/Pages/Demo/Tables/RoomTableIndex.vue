<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Demo Room Table
            </h2>
        </template>
         <div class="py-12">
            <div class="">
                <RoomTable 
                :rooms="rooms"
                />
            </div>
        </div>
    </app-layout>
</template>

<script>

import AppLayout from '@src/Layouts/AppLayout'
import RoomTable from '@src/Components/Tables/RoomTable'

export default {
  props: {
    rooms: {
      type: Array,
      default: function() {
        return [];
      }
    }
  },

  components: {
      AppLayout,
      RoomTable
  },
};
</script>
