<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Booking Details
      </h2>
    </template>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

      <booking-detailed-view :booking="booking"></booking-detailed-view>

      <div class="flex space-x-10 items-center justify-end py-3 text-right">
        <jet-action-message :on="form.recentlySuccessful" class="mr-3">
          Submitted.
        </jet-action-message>

        <jet-action-message :on="form.hasErrors()" class="mr-3">
          You request has errors and could not be processed at this time.
        </jet-action-message>

        <jet-danger-button :class="{ 'opacity-25': (form.processing) }"
                           :disabled="form.processing"
                           @click.native="refuse"
        >
          Refuse
        </jet-danger-button>

        <jet-button class="bg-green-700 active:bg-green-800 hover:bg-green-500 "
                    :class="{ 'opacity-25': (form.processing) }"
                    :disabled="form.processing"
                    @click.native="approve"
        >
          Approve
        </jet-button>

      </div>

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
import JetSectionBorder from '@src/Jetstream/SectionBorder';
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
    JetSectionBorder
  },

  props: {
    booking: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      form: this.$inertia.form({
        status: '',
        comment: '',
      }, {
        bag: 'reviewBooking',
      })
    }

  },

  methods: {
    submitForm() {
      console.log(this.form.action);
      this.form.post('/bookings/' + this.booking.id + '/review', {
        preserveScroll: true,
      }).then(response => {
        this.form.processing = false;
      })
    },

    approve() {
      this.form.status = 'approved';
      this.submitForm();
    },

    refuse() {
      this.form.status = 'refused';
      this.submitForm();
    },
  },

}
</script>
