<template>
  <div class="table-container">
    <table class="table-auto responsive-spaced">
      <caption></caption>
      <thead>
      <tr>
        <th class="lt-grey" id="id_blackout_name">Name</th>
        <th class="lt-grey" id="id_blackout_name">Start Time</th>
        <th class="lt-grey" id="id_blackout_email">End Time</th>
        <th class="lt-grey" id="id_actions">Actions</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="blackout in blackouts" :key="blackout.id">
        <td class="text-center lt-grey">{{ blackout.name }}</td>
        <td class="text-center lt-grey">{{ blackout.start_time | legible_date}}</td>
        <td class="text-center lt-grey">{{ blackout.end_time | legible_date }}</td>
        <td class="text-center lt-grey">
          <slot name="blackout" v-bind:blackout="blackout"></slot>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>
<script>

import JetInput from '@src/Jetstream/Input'
import moment from "moment";

export default {
  components: {
    JetInput,
    moment
  },

  name: "blackoutTable",
  props: {
    blackouts: {
      type: Array,
      default: [],
      required: true
    },
  },

     filters: {
        legible_date: function (date) {
            return moment(date).format("LLL");
        }
     }

};
</script>
