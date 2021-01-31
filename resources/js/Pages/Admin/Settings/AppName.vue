<template>
        <jet-form-section @submitted="updateNameSetting">
            <template #title>
                Application Name
            </template>

            <template #form>
                    <jet-input id="label" type="hidden" class="mt-1 block w-full" value="app_name"/>
                <div class="col-span-12 sm:col-span-6">
                    <jet-label for="app_name" value="Application Name"/>
                    <jet-input id="app_name" name="app_name" type="text" class="mt-1 block w-full" v-model="updateNameSettingform.app_name"
                               autofocus/>
                    <jet-input-error :message="updateNameSettingform.error('app_name')" class="mt-2"/>
                </div>
            </template>

            <template #actions>
                <jet-action-message :on="updateNameSettingform.recentlySuccessful" class="mr-3">
                    Updated.
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': updateNameSettingform.processing }"
                            :disabled="updateNameSettingform.processing">
                    Update Application Name
                </jet-button>
            </template>
        </jet-form-section>
</template>
<script>

import JetButton from '@src/Jetstream/Button'
import JetInput from '@src/Jetstream/Input'
import JetActionMessage from '@src/Jetstream/ActionMessage'
import JetFormSection from '@src/Jetstream/FormSection'
import JetInputError from '@src/Jetstream/InputError'
import JetLabel from '@src/Jetstream/Label'
import AppName from "@src/Pages/Admin/Settings/AppName";

export default {
    components: {
        AppName,
        JetButton,
        JetInput,
        JetFormSection,
        JetActionMessage,
        JetInputError,
        JetLabel,
    },
    data() {
        return {
            updateNameSettingform: this.$inertia.form({
                label: 'app_name',
                app_name: this.settings.name,
            }, {
                bag: 'updateNameSetting',
                resetOnSuccess: false,
            }),
        }
    },
    props: {
        settings: {
            type: Object,
        },
    },
    methods: {
        updateNameSetting() {
            this.updateNameSettingform.post('/admin/settings/app_name', {
                preserveScroll: true,
            })
        },
    },
}
</script>
