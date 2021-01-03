<template>
  <div class="table-container">
    <div class="table-filter-container">
      <input type="text"
         placeholder="Search Table"
         v-model="filter" 
         />
    </div>
     
    <table class="table-auto responsive-spaced">
      <caption>{{tableCaption}}</caption>
      <thead>
        <tr>
          <th class="lt-grey" v-for="header in tableHeaders" :key="header.id" :id="header.id">{{header}}</th>
        </tr>
      </thead>
      <tbody>
         <tr v-for="row in filteredRows" :key="row.id">
          <td class="text-center lt-grey" v-for="item in row" :key="item.id">{{item}}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>

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
    
  },
  data() {
      return {
          filter: '',
          rows: this.tableData
      }
  },
  computed: {
    filteredRows() {
      return this.rows.filter(row => {
        console.log(row)
        const searchTerm = this.filter.toLowerCase();
       
        return row.find(item =>
            item.includes(searchTerm));
        
      });
    }
  },
};
</script>
