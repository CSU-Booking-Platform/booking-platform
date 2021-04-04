<template>
  <jet-form-section @submitted="createBlackoutForEveryRoom">
    <template #title>
      Civic Holidays
    </template>

    <template #description>
      A blackout period will be applied from the specified start date to the specified end date for every room.
    </template>

    <template #form>
      <jet-input id="label" type="hidden" class="mt-1 block w-full" value="app_name"/>
      <div class="col-span-12 sm:col-span-6">
        <app-warning class="pr-4 mb-3">
          Using this feature will create a blackout period for every room!
        </app-warning>
        <div class="mb-2">
          <jet-label for="start_date" value="Start date"/>
          <date-time-picker id="start_date" name="start_date" class="mt-1 block w-full"
                            v-model="form.start_date"
                            :value="form.start_date"
          />
          <jet-input-error :message="form.error('start_date')" class="mt-2"/>
        </div>
        <jet-label for="end_date" value="End date"/>
        <date-time-picker id="end_date" name="end_date" class="mt-1 block w-full"
                          v-model="form.end_date"
                          :value="form.end_date"
        />
        <jet-input-error :message="form.error('end_date')" class="mt-2"/>
      </div>
    </template>

    <template #actions>
      <jet-action-message :on="form.recentlySuccessful" class="mr-3">
        Done.
      </jet-action-message>

      <jet-button :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing">
        Create blackout for every room
      </jet-button>
    </template>
  </jet-form-section>
</template>
<script>

import JetButton from '@src/Jetstream/Button'
import JetInput from '@src/Jetstream/Input'
import JetActionMessage from '@src/Jetstream/ActionMessage'
import JetFormSection from '@src/Jetstream/FormSection'
import JetInputError from '@src/Jetstream/InputError'
import JetLabel from '@src/Jetstream/Label'
import AppWarning from '@src/Components/Form/Warning';
import DateTimePicker from "@src/Components/Form/DateTimePicker";

export default {
  components: {
    JetButton,
    JetInput,
    JetFormSection,
    JetActionMessage,
    JetInputError,
    JetLabel,
    AppWarning,
    DateTimePicker,
  },
  data() {
    return {
      form: this.$inertia.form({
        start_date: null,
        end_date: null
      }, {
        bag: 'createBlackoutAll',
        resetOnSuccess: false,
      }),
    }
  },
  methods: {
    createBlackoutForEveryRoom() {
      this.form.post('/admin/rooms/blackouts/all', {
        preserveScroll: true,
      })
    },
  }

}
</script>
