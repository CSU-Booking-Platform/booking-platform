<template>
  <div class="table-container">
    <div class="flex flex-row-reverse w-full mb-4">
      <jet-input type="text"
                 placeholder="Search Users Table"
      />
    </div>

    <table class="table-auto responsive-spaced">
      <caption></caption>
      <thead>
      <tr>
        <th class="lt-grey" id="id_user_name">Name</th>
        <th class="lt-grey" id="id_user_email">Email</th>
        <th/>
      </tr>
      </thead>
      <tbody>
      <tr v-for="user in Users" :key="user.id">
        <td class="text-center lt-grey">{{ user.name }}</td>
        <td class="text-center lt-grey">{{ user.email }}</td>
        <td class="text-center lt-grey">
          <slot name="user" v-bind:user="user"></slot>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>
<script>

import JetInput from '@src/Jetstream/Input'

export default {
  components: {
    JetInput,
  },

  name: "userTable",
  props: {
    Users: {
      type: Array,
      default: [],
      required: true
    },
  }
};
</script>
