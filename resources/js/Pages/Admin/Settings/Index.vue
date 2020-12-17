<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Settings
            </h2>
        </template>
        <div class="py-12">
            <app-name v-bind:settings="settings['app_name']"/>
            <app-logo v-bind:settings="settings['app_logo']"/>
        </div>
    </app-layout>
</template>

<script>
import JetSectionBorder from '@src/Jetstream/SectionBorder'
import AppLayout from '@src/Layouts/AppLayout';
import AppName from "@src/Pages/Admin/Settings/AppName";
import AppLogo from "@src/Pages/Admin/Settings/AppLogo";

export default {
    components: {
        AppName,
        AppLogo,
        AppLayout,
        JetSectionBorder,
    },
    props: {
        settings: {
            type: Array,
            default: function () {
                return []
            },
        },
    },
}
</script>
