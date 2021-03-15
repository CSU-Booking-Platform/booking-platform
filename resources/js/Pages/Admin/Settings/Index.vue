<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Settings
            </h2>
        </template>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="mb-5">
                <app-name :settings="settings.app_name"/>
            </div>
            <div class="mb-5">
                <app-logo :settings="settings.app_logo"/>
            </div>
            <div class="mb-5">
                <app-config :settings="settings.app_config"/>
            </div>
            <BookingInformations 
                :general_information="settings.general_information" 
                :event_description="settings.event_description" 
            />
        </div>
    </app-layout>
</template>

<script>
import JetSectionBorder from '@src/Jetstream/SectionBorder'
import AppLayout from '@src/Layouts/AppLayout';
import AppName from "@src/Pages/Admin/Settings/AppName";
import AppLogo from "@src/Pages/Admin/Settings/AppLogo";
import AppConfig from "@src/Pages/Admin/Settings/AppConfig";
import BookingInformations from "@src/Pages/Admin/Settings/BookingInformations";

export default {
    components: {
        AppName,
        AppLogo,
        AppConfig,
        AppLayout,
        BookingInformations,
        JetSectionBorder,
    },
    props: {
        settings: {
            type: Object,
            default: function () {
                return {
                    app_name: {},
                    app_logo: {},
                    app_config: {},
                    general_information: {},
                    event_description: {}
                }
            },
        },
    },
}
</script>
