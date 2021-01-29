<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Booking List Page
            </h2>
        </template>
        <div class="py-12">
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