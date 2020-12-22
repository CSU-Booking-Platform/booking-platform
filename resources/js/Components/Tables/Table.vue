<template>
  <div class="table-container">
    <table class="table-auto border-solid border-4 border-light-blue-500">
      <caption>{{tableCaption}}</caption>
      <thead>
        <tr>
          <th v-for="header in tableHeaders" :key="header.id" :id="header.id">{{header}}</th>
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
    },
    tableHeaders: {
      type: Array,
      default: [],
      required: true
    },
    tableCaption:{
      type: String,
      default: '',
      required: true
    }
  },
  components: {
    TableRow
  }
};
</script>
