<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Booking Details
      </h2>
    </template>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

      <h2 v-if="booking.status == 'Review'">This request is currently under review and cannot be modified</h2>
      <booking-detailed-view :booking="booking"></booking-detailed-view>

    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@src/Layouts/AppLayout'
import JetActionMessage from '@src/Jetstream/ActionMessage';
import JetActionSection from '@src/Jetstream/ActionSection'
import JetFormSection from '@src/Jetstream/FormSection';
import JetSecondaryButton from '@src/Jetstream/SecondaryButton'
import JetDangerButton from '@src/Jetstream/DangerButton'
import JetButton from '@src/Jetstream/Button';
import BookingDetailedView from "@src/Components/BookingDetailedView";

export default {
  components: {
    AppLayout,
    BookingDetailedView,
    JetActionMessage,
    JetActionSection,
    JetFormSection,
    JetSecondaryButton,
    JetDangerButton,
    JetButton,
  },

  props: {
    booking: {
      type: Object,
      required: true,
    },
  },

}
</script>
