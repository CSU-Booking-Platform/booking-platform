<template>
  <div class="col-span-6">
    <jet-label value="Initial Reservation Details"/>
    <div class="flex items-center justify-center py-2 space-x-5">
      <!-- First Date -->
      <div class="flex-1">
        <jet-label value="Date"/>
        <div class="flex space-x-2 py-2">
          <h3 class="text-lg font-medium text-gray-900">
            {{ only_date(reservation.start_time) }}
          </h3>
        </div>
      </div>

      <!-- Actual Event Hours -->
      <div class="flex-1">
        <jet-label>
          <span>Hours Of Reservation</span>
          <span class="text-xs italic">including setup / take down time</span>
        </jet-label>

        <div class="flex items-start justify-center py-2 space-x-5">
          <h3 class="text-lg font-medium text-gray-900">
            {{ only_time(reservation.start_time) }}
          </h3>
          <span class="block font-medium text-sm text-gray-700"> To </span>
          <h3 class="text-lg font-medium text-gray-900">
            {{ only_time(reservation.end_time) }}
          </h3>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
import moment from "moment";
import JetLabel from "@src/Jetstream/Label";

export default {
  components: {
    JetLabel
  },
  props: {
    reservation: {
      type: Object,
      required: true,
    }
  },
  methods: {
    only_date(date) {
      return moment(date).format("dddd, Do MMMM YYYY");
    },
    only_time(date) {
      return moment(date).format("LT");
    },
  }
}
</script>
