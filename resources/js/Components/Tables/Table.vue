<template>
  <div class="table-container">
    <table class="table-auto border-solid border-4 border-light-blue-500">
      <thead>
        <tr>
          <th>Room ID</th>
          <th>Room Type</th>
          <th>Building</th>
          <th>Number</th>
          <th>Floor</th>
          <th>Availability</th>
        </tr>
      </thead>
      <tbody>
        <TableRow v-for="row in tableData" :key="row.id" 
        :rowData="row"
        />
      </tbody>
    </table>
  </div>
</template>
<script>
import TableRow from '@src/Components/Tables/TableRow'

export default {
  name: "Table",
  props: {
    tableData: {
      type: Array,
      default: [],
      required: true
    }
  },
  components: {
    TableRow
  }
};
</script>
