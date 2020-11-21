<template>
    <div class="table-container">
        <table> 
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Players</th>
                    <th>Tournament</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in rows" :key="row.id">
                    <td>{{row.id}}</td>
                    <td>{{row.players}}</td>
                    <td>{{row.tournament}}</td>
                    <td>{{row.time}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    import AppLayout from '@src/Layouts/AppLayout'
    import Welcome from '@src/Jetstream/Welcome'

    export default {
        components: {
            AppLayout,
            Welcome,
        },
    }
</script>
