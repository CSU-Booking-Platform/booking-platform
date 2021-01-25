<template>
    <app-layout>
        <div class="flex flex-col lg:flex-row md:mx-auto md:w-2/3 my-3">
            <div class="m-4 w-1/3">
                <create-room-form :available-room-types="availableRoomTypes" />
            </div>
            <div v-if="rooms.length > 0" class="m-4 w-2/3">
                <rooms-list :rooms="rooms"  :available-room-types="availableRoomTypes"  />
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

export default {
    components: {
        CreateRoomForm,
        AppLayout,
        RoomsList,
        JetSectionBorder,
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
