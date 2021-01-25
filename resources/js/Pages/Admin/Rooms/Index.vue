<template>
    <app-layout>
        <div class="flex flex-col lg:flex-row md:mx-auto md:w-11/12 ">
            <div class="m-4 w-1/4 pt-12">
                <create-room-form :available-room-types="availableRoomTypes" />
            </div>
            <div v-if="rooms.length > 0" class="m-4 w-3/4">
                <div class="py-12">
                    <ViewActiveRoomsTable :rooms="rooms" :availableRoomTypes="availableRoomTypes" :roles="roles"/>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import JetSectionBorder from '@src/Jetstream/SectionBorder'
import CreateRoomForm from './CreateRoomForm';
import RoomsList from './RoomsList';
import AppLayout from '@src/Layouts/AppLayout';
import XSection from '@src/Components/XSection';
import ViewActiveRoomsTable from '@src/Components/Tables/ViewActiveRoomsTable';

export default {
    components: {
        CreateRoomForm,
        AppLayout,
        RoomsList,
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
        }
    }
}
</script>
