<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <a href="users">Users Page</a>
                    <welcome />
                </div>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Table 
                :tableHeaders="tableHeaders"
                :tableCaption="tableCaption"
                :tableData="tableData"
                />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@src/Layouts/AppLayout'
    import Welcome from '@src/Jetstream/Welcome'
    import Table from '@src/Components/Tables/Table'

    export default {
        components: {
            AppLayout,
            Welcome,
            Table
        },
          data() {
            return {
                tableHeaders: ['th1', 'th2', 'th3', 'th4', 'th5'],
                tableCaption: '',
                tableData: [
                    ['tr1', 'Tr1', 'tr1', 'tr1', 'tr1'],
                    ['tr2', 'tr2', 'tr2', 'tr2', 'tr2'],
                    ['tr3', 'tr3', 'tr3', 'tr3', 'tr3']
                ]
            }
  }
    }
</script>
