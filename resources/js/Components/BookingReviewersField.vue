<template>
  <div>
    <div v-if="editing">
      <multi-select :options="options" :selected-on-init="selected" @change="updateReviewers" @opened="fetchAssignableUsers" />
    </div>

    <div v-else class="flex flex-wrap">
      <div v-for="reviewer in reviewers" class="flex flex-auto py-2 pr-2">
        <img class="h-8 w-8 rounded-full object-cover" :src="reviewer.profile_photo_url" :alt="reviewer.name"/>
        <span class="ml-2 mt-2"> {{ reviewer.name }} </span>
      </div>
    </div>
  </div>
</template>

<script>
import MultiSelect from "@src/Components/Form/MultiSelect";
import JetDropdown from "@src/Jetstream/Dropdown";
import axios from "axios";

export default {
  components: {
    MultiSelect,
    JetDropdown,
  },

  props: {
    editing: {
      type: Boolean,
      default: false,
    },
    booking_request_id: {
      type: Number,
      required: true,
    },
    reviewers: {
      type: Array,
      required: true,
    },
  },

  data() {
    return {
      selected: {},
      options: {},
      form: this.$inertia.form({
        reviewers_ids: {}
      }, {
        bag: 'setBookingReviewers'
      }),
    }
  },

  created() {
    if (this.editing) {
      this.options = this.selected = this.reduceUsersForSelect(this.reviewers);
      this.fetchAssignableUsers();
    }
  },

  methods: {
    reduceUsersForSelect(users) {
      return users.reduce(function(groups, item) {
        groups[item.id] = { text: item.name, icon: item.profile_photo_url };
        return groups;
      }, {})
    },

    fetchAssignableUsers() {
      return axios.get('/api/bookings/assignable').then(response => {
          this.options = this.reduceUsersForSelect(response.data);
        })
    },

    updateReviewers(selected) {
      this.form.reviewers_ids = Object.keys(selected);

      this.form.post("/bookings/" + this.booking_request_id + "/assign", {
        preserveState: true
      });
    }
  },
}
</script>
