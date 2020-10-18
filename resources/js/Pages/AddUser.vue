<template>
    <app-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <create-user-form/>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import CreateUserForm from '@src/Layouts/AppLayout';
import AppLayout from '@src/Layouts/AppLayout';

export default {
    components: {
        CreateUserForm,
        AppLayout,
    },
}
</script>
