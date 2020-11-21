<template>
  <tr>
    <td v-for="item in rowData" :key="item.id">{{item}}</td>
  </tr>
</template>
<script>

export default {
  name: "TableRow",
  props: {
    rowData: {
      type: Array,
      default: [],
      required: true
    }
  },
  components: {
  }
};
</script>
