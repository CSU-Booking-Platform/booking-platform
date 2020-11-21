<template>
    <app-layout>
        <Navbar/>
    </app-layout>
</template>

<script>
    import AppLayout from '@src/Layouts/AppLayout'
    import Welcome from '@src/Jetstream/Welcome'
    import Navbar from '@src/Components/Navbar'

    export default {
        components: {
            AppLayout,
            Welcome,
            Navbar
        },
    }
</script>
