<template>
  <jet-form-section @submitted="updateDateTime">
    <template #title>
      {{ academicDate.semester }}
    </template>

    <template #description>
      Update the {{ academicDate.semester }} semester's start and end dates.
    </template>

    <template #form>
      <jet-input
        id="label"
        type="hidden"
        class="mt-1 block w-full"
        value="app_name"
      />
      <div class="col-span-12 sm:col-span-6">
        <div class="mb-2">
          <jet-label
            :for="academicDate.semester + '_start_date'"
            value="Start date"
          />
          <date-picker
            :id="academicDate.semester + '_start_date'"
            :dusk="academicDate.semester + '_start_date'"
            name="start_date"
            type="date"
            class="mt-1 block w-full"
            v-model="updateDateTimeForm.start_date"
            :value="updateDateTimeForm.start_date"
            autofocus
          />
          <jet-input-error
            :message="updateDateTimeForm.error('start_date')"
            class="mt-2"
          />
        </div>
        <jet-label
          :for="academicDate.semester + '_end_date'"
          value="End date"
        />
        <date-picker
          :id="academicDate.semester + '_end_date'"
          :dusk="academicDate.semester + '_end_date'"
          name="end_date"
          type="date"
          class="mt-1 block w-full"
          v-model="updateDateTimeForm.end_date"
          :value="updateDateTimeForm.end_date"
          autofocus
        />
        <jet-input-error
          :message="updateDateTimeForm.error('end_date')"
          class="mt-2"
        />
      </div>
    </template>

    <template #actions>
      <jet-action-message
        :on="updateDateTimeForm.recentlySuccessful"
        class="mr-3"
      >
        Updated.
      </jet-action-message>

      <jet-button
        :id="academicDate.semester + '_submit_button'"
        :dusk="academicDate.semester + '_submit_button'"
        :class="{ 'opacity-25': updateDateTimeForm.processing }"
        :disabled="updateDateTimeForm.processing"
      >
        Update {{ academicDate.semester }} semester dates
      </jet-button>
    </template>
  </jet-form-section>
</template>
<script>
import JetButton from "@src/Jetstream/Button";
import JetInput from "@src/Jetstream/Input";
import JetActionMessage from "@src/Jetstream/ActionMessage";
import JetFormSection from "@src/Jetstream/FormSection";
import JetInputError from "@src/Jetstream/InputError";
import JetLabel from "@src/Jetstream/Label";
import AppWarning from "@src/Components/Form/Warning";
import DatePicker from "@src/Components/Form/DatePicker";

export default {
  components: {
    JetButton,
    JetInput,
    JetFormSection,
    JetActionMessage,
    JetInputError,
    JetLabel,
    AppWarning,
    DatePicker
  },
  data() {
    return {
      updateDateTimeForm: this.$inertia.form(
        {
          start_date: this.academicDate.start_date,
          end_date: this.academicDate.start_date
        },
        {
          bag: "updateDateTime",
          resetOnSuccess: false
        }
      )
    };
  },
  props: {
    academicDate: {
      type: Object,
      required: true
    }
  },
  methods: {
    updateDateTime() {
      this.updateDateTimeForm.post(
        "/admin/settings/academic_date/" + this.academicDate.id,
        {
          preserveScroll: true
        }
      );
    }
  },
  watch: {
    academicDate(academicDate) {
      this.updateDateTimeForm.start_date = academicDate.start_date;
      this.updateDateTimeForm.end_date = academicDate.end_date;
    }
  }
};
</script>
