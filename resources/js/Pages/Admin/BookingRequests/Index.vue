<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Booking Requests page
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <CreateBookingRequestForm/>
                </div>
            </div>
        </div>
        <div v-if="booking_requests.length > 0" class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <jet-section-border/>
            <BookingRequestsList :booking_requests="booking_requests"/>
        </div>

    </app-layout>
</template>

<script>
import JetSectionBorder from '@src/Jetstream/SectionBorder'
import CreateBookingRequestForm from './CreateBookingRequestForm';
import BookingRequestsList from './BookingRequestsList';
import AppLayout from '@src/Layouts/AppLayout';

export default {
    components: {
        CreateBookingRequestForm,
        AppLayout,
        BookingRequestsList,
        JetSectionBorder,
    },
    props: {
        booking_requests: {
            type: Array,
            default: function () {
                return []
            },
        }
    }
}
</script>
