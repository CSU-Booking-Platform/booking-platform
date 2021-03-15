<template>
        <jet-form-section @submitted="updateConfigSetting">
            <template #title>
                Application Office 365 Credentials
            </template>

            <template #form>
                    
                <div class="col-span-12 sm:col-span-6">
                    <jet-label for="app_sso" value="Client Secret"/>
                    <jet-input id="client_secret" name="app_sso" type="text" class="mt-1 block w-full" v-model="updateConfSettingform.client_secret"
                               autofocus/>
                    <jet-input-error :message="updateConfSettingform.error('app_sso')" class="mt-2"/>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <jet-label for="app_sso" value="Client ID"/>
                    <jet-input id="client_id" name="app_sso" type="text" class="mt-1 block w-full" v-model="updateConfSettingform.client_id"
                               autofocus/>
                    <jet-input-error :message="updateConfSettingform.error('app_sso')" class="mt-2"/>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <jet-label for="app_sso" value="Redirect URI"/>
                    <jet-input id="uri" name="app_sso" type="text" class="mt-1 block w-full" v-model="updateConfSettingform.redirect_uri"
                               autofocus/>
                    <jet-input-error :message="updateConfSettingform.error('app_sso')" class="mt-2"/>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <jet-label for="app_sso" value="Tennant ID"/>
                    <jet-input id="tenant" name="app_sso" type="text" class="mt-1 block w-full" v-model="updateConfSettingform.tenant"
                               autofocus/>
                    <jet-input-error :message="updateConfSettingform.error('app_sso')" class="mt-2"/>
                </div>
            </template>

            <template #actions>
                <jet-action-message :on="updateConfSettingform.recentlySuccessful" class="mr-3">
                    Updated.
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': updateConfSettingform.processing }"
                            :disabled="updateConfSettingform.processing">
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

export default {
    components: {
        JetButton,
        JetInput,
        JetFormSection,
        JetActionMessage,
        JetInputError,
        JetLabel,
    },
    data() {
        return {
            updateConfSettingform: this.$inertia.form({
                label: 'app_config',
                client_secret: this.settings?.secret,
                client_id: this.settings?.id,
                redirect_uri: this.settings?.uri,
                tenant: this.settings?.tenant
            }, {
                bag: 'updateConfigSetting',
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
        updateConfigSetting() {
            this.updateConfSettingform.post('/admin/settings/app_config', {
                preserveScroll: true,
            })
        },
    },
}
</script>