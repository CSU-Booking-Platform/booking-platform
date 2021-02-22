<template>
    <app-layout>
        <div class="m-24">
             <RoomTable
            :rooms="dataRooms"
            @filterRoomsJson="filterRoomsJson($event)"
            />
        </div>
    </app-layout>
</template>

<script>
import JetSectionBorder from '@src/Jetstream/SectionBorder'
import CreateBookingRequestForm from './CreateBookingRequestForm';
import AppLayout from '@src/Layouts/AppLayout';
import RoomTable from '@src/Components/Tables/RoomTable';
import axios from 'axios';

export default {
    components: {
        CreateBookingRequestForm,
        AppLayout,
        JetSectionBorder,
        RoomTable
    },
    props: {
        rooms: {
            type: Array,
            default: function () {
                return []
            },
        },

    },
    mounted(){
        this.dataRooms = this.rooms ?? [];
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
