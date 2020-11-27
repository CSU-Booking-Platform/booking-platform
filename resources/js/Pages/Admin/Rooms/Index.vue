<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Rooms page
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <create-room-form/>
                </div>
            </div>
        </div>
        <div v-if="rooms.length > 0" class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <jet-section-border/>
            <rooms-list :rooms="rooms"/>
        </div>

    </app-layout>
</template>

<script>
import JetSectionBorder from '@src/Jetstream/SectionBorder'
import CreateRoomForm from './CreateRoomForm';
import RoomsList from './RoomsList';
import AppLayout from '@src/Layouts/AppLayout';

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
        }
    }
}
</script>
