<template>
        <jet-form-section @submitted="updateSSOSetting">
            <template #title>
                Application Office 365 Credentials
            </template>

            <template #form>
                    <jet-input id="label" type="hidden" class="mt-1 block w-full" value="app_sso"/>
                <div class="col-span-12 sm:col-span-6">
                    <jet-label for="app_sso" value="Client Secret"/>
                    <jet-input id="client_secret" name="app_sso" type="text" class="mt-1 block w-full" v-model="updateSSOSettingform.client_secret"
                               autofocus/>
                    <jet-input-error :message="updateSSOSettingform.error('app_sso')" class="mt-2"/>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <jet-label for="app_sso" value="Client ID"/>
                    <jet-input id="client_id" name="app_sso" type="text" class="mt-1 block w-full" v-model="updateSSOSettingform.client_id"
                               autofocus/>
                    <jet-input-error :message="updateSSOSettingform.error('app_sso')" class="mt-2"/>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <jet-label for="app_sso" value="Redirect UR"/>
                    <jet-input id="uri" name="app_sso" type="text" class="mt-1 block w-full" v-model="updateSSOSettingform.redirect_uri"
                               autofocus/>
                    <jet-input-error :message="updateSSOSettingform.error('app_sso')" class="mt-2"/>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <jet-label for="app_sso" value="Tennant ID"/>
                    <jet-input id="tenant" name="app_sso" type="text" class="mt-1 block w-full" v-model="updateSSOSettingform.tenant"
                               autofocus/>
                    <jet-input-error :message="updateSSOSettingform.error('app_sso')" class="mt-2"/>
                </div>
            </template>

            <template #actions>
                <jet-action-message :on="updateSSOSettingform.recentlySuccessful" class="mr-3">
                    Updated.
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': updateSSOSettingform.processing }"
                            :disabled="updateSSOSettingform.processing">
                    Update Application Credentials
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
            updateSSOSettingform: this.$inertia.form({
                label: 'app_sso',
                client_secret: this.settings.secret,
                client_id: this.settings.id,
                redirect_uri: this.settings.uri,
                tenant: this.settings.tenant
            }, {
                bag: 'updateSSOSetting',
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
        updateSSOSetting() {
            this.updateSSOSettingform.post('/admin/settings/app_sso', {
                preserveScroll: true,
            })
        },
    },
}
</script>
