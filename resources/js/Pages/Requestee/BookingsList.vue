<template>
    <app-layout>
        <div class="m-24">
             <BookingsTable 
            :bookings="bookings"
            />
        </div>
    </app-layout>
</template>

<script>
import JetSectionBorder from '@src/Jetstream/SectionBorder'
import AppLayout from '@src/Layouts/AppLayout';
import BookingsTable from '@src/Components/Tables/BookingsTable';

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
}
</script>