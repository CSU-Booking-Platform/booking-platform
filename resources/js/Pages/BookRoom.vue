<template>
  <app-layout>
    <Navbar />
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-align: -webkit-center">
      <Table :tableData="data" />
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@src/Layouts/AppLayout";
import Welcome from "@src/Jetstream/Welcome";
import Navbar from "@src/Components/Navbar";
import Table from "@src/Components/Tables/Table";

export default {
  components: {
    AppLayout,
    Welcome,
    Navbar,
    Table
  },
  data() {
    return {
      data: [
          ['H903', 'Mezanine', 'Hall', 903, 9, 'Available'],
          ['H903', 'Mezanine', 'Hall', 903, 9, 'Available'],
          ['H903', 'Mezanine', 'Hall', 903, 9, 'Available'],
          ['H903', 'Mezanine', 'Hall', 903, 9, 'Available'],
          ['H903', 'Mezanine', 'Hall', 903, 9, 'Available'],
      ],
      ughTopCards: []
    };
  }
};
</script>
