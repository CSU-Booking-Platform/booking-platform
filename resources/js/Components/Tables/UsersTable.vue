<template>
  <div class="table-container">
    <table class="table-auto responsive-spaced">
      <caption></caption>
      <thead>
      <tr>
        <th id="id_user_name">Name</th>
        <th id="id_user_email">Email</th>
        <th id="id_actions">Actions</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="user in Users" :key="user.id">
        <td class="text-center">{{ user.name }}</td>
        <td class="text-center">{{ user.email }}</td>
        <td class="text-center">
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
