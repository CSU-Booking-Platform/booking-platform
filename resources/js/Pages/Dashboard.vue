<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <a href="users">Users Page</a>
                            <a href="rooms">Rooms Page</a>
                            <welcome />
                        </div>
                    </div>
                </div>
            </h2>
        </template>

    </app-layout>
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
