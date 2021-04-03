<template>
    <app-layout>
        <div class="flex flex-col lg:flex-row md:mx-auto md:w-11/12">
            <div class="m-4 w-1/4 pt-12 pb-48">
                <create-room-form :available-room-types="availableRoomTypes"
                :available-buildings="availableBuildings"/>
            </div>
            <div v-if="rooms.length > 0" class="m-4 w-3/4">
                <div class="mx-24 mt-16">
                    <ViewActiveRoomsTable :rooms="rooms" :available-room-types="availableRoomTypes"
                                          :available-buildings="availableBuildings" :roles="roles"/>
                </div>
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
