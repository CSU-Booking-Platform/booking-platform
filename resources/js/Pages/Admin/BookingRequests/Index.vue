<template>
    <app-layout>
        <template #header>
        </template>
        <div class="m-24">
             <RoomTable
            :rooms="dataRooms"
            :paginator="paginator"
            @filterRoomsJson="filterRoomsJson($event)"
            />
        </div>
    </app-layout>
</template>

<script>
import JetSectionBorder from '@src/Jetstream/SectionBorder'
import AppLayout from '@src/Layouts/AppLayout';
import RoomTable from '@src/Components/Tables/RoomTable';
import axios from 'axios';

export default {
    components: {
        AppLayout,
        JetSectionBorder,
        RoomTable,
    },
    props: {
        booking_requests: {
            type: Array,
            default: function () {
                return []
            },
        },
        rooms: {
            type: Array,
            default: function () {
                return []
            },
        },
        paginator: Object

    },
    mounted(){
        this.dataRooms = this.paginator.data ?? [];
    },
    data() {
        return {
            dataRooms: []
        }
    },
    methods:{
        filterRoomsJson(e) {
            axios.post('/api/filterRooms', e)
                .then((response)=>{
                    this.dataRooms = response.data;
                })
        }
    }
}
</script>
