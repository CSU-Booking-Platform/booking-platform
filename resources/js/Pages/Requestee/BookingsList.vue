<template>
    <app-layout>
        <div class="m-24">
             <BookingsTable
            :bookings="bookings"
            @filterBookingsJson="filterBookingsJson($event)"
            />
        </div>
    </app-layout>
</template>

<script>
import JetSectionBorder from '@src/Jetstream/SectionBorder'
import AppLayout from '@src/Layouts/AppLayout';
import BookingsTable from '@src/Components/Tables/BookingsTable';
import axios from "axios";

export default {
    components: {
        AppLayout,
        JetSectionBorder,
        BookingsTable
    },
    props: {
        bookings: {
            type: Array,
            default: function () {
                return []
            },
        },
    },
  methods:{
    filterBookingsJson(e) {
      axios.post('/api/filterBookings', e)
        .then((response)=>{
          this.bookings = response.data;
        })
    }
  }
}
</script>
