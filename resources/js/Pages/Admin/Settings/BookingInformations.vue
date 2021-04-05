<template>
  <jet-form-section>
    <template #title>
      Bookings Description
    </template>
    <template #description>
      Change the default bookings description as seen by the users creating a booking.
    </template>
    <template #form>
      <div class="col-span-12 sm:col-span-6">
        <div class="p-2">
          <jet-label for="general_information" value="General Information"/>
          <div v-if="general_information">
            <RichTextEditor
              :editable="true"
              :incomingText="general_information.html"
              :onSave="onBookingsGeneralInformationSave"
            />
          </div>
          <div v-else>
            <RichTextEditor
              :editable="true"
              :incomingText="''"
              :onSave="onBookingsGeneralInformationSave"
            />
          </div>
        </div>
        <div class="p-2">
          <jet-label for="event_description" value="Event description"/>
          <div v-if="event_description">
            <RichTextEditor
              :editable="true"
              :incomingText="event_description.html"
              :onSave="onBookingsEventDescriptionSave"
            />
          </div>
          <div v-else>
            <RichTextEditor
              :editable="true"
              :incomingText="''"
              :onSave="onBookingsEventDescriptionSave"
            />
          </div>
        </div>
      </div>
    </template>
  </jet-form-section>
</template>

<script>
import JetFormSection from "@src/Jetstream/FormSection";
import JetLabel from "@src/Jetstream/Label";
import RichTextEditor from "@src/Components/RichTextEditor";
import axios from "axios";

export default {
  components: {
    RichTextEditor,
    JetFormSection,
    JetLabel
  },
  props: {
    general_information: {
      type: Object,
    },
    event_description: {
      type: Object,
    }
  },
  methods: {
    onBookingsGeneralInformationSave(data) {
      axios.patch("/api/booking-setting", {
        slug: "general_information",
        data: data
      });
    },
    onBookingsEventDescriptionSave(data) {
      axios.patch("/api/booking-setting", {
        slug: "event_description",
        data: data
      });
    }
  }
};
</script>
