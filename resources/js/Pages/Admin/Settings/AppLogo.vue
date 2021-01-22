<template>
        <jet-form-section-image @submitted="updateLogoSetting">
            <template #title>
                Application Logo
            </template>

            <template #form >
                    <jet-input id="label" type="hidden" class="mt-1 block w-full" value="app_logo"/>
                <div class="col-span-12 sm:col-span-6">
                    <img :src="updateLogoSettingform.app_path" class="img-responsive" height="70" width="90" alt="Logo Image Preview">
                    <jet-label for="app_logo" value="Application Logo"/>
                    <input type="file"  @change="selectFile">
                    <jet-input-error :message="updateLogoSettingform.error('app_logo')" class="mt-2"/>
                </div>
            </template>

            <template #actions>
                <jet-action-message :on="updateLogoSettingform.recentlySuccessful" class="mr-3">
                    Updated.
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': updateLogoSettingform.processing }"
                            :disabled="updateLogoSettingform.processing">
                    Update
                </jet-button>
            </template>
        </jet-form-section-image>
</template>
<script>

import JetButton from '@src/Jetstream/Button'
import JetInput from '@src/Jetstream/Input'
import JetActionMessage from '@src/Jetstream/ActionMessage'
import JetFormSectionImage from '@src/Components/FormSectionImage'
import JetInputError from '@src/Jetstream/InputError'
import JetLabel from '@src/Jetstream/Label'
import AppName from "@src/Pages/Admin/Settings/AppName";

export default {
    components: {
        AppName,
        JetButton,
        JetInput,
        JetFormSectionImage,
        JetActionMessage,
        JetInputError,
        JetLabel,
    },
    data() {
        return {
            updateLogoSettingform: this.$inertia.form({
                label: 'app_logo',
                app_logo: '',
                app_path: this.settings.path,
            }, {
                bag: 'updateNameSetting',
                resetOnSuccess: false,
            }),
            formData: new FormData()
        }
    },
    props: {
        settings: {
            type: Object,
        },
    },
    watch: {
        settings: {
            // the callback will be called immediately after the start of the observation
            immediate: true,
            handler (val, oldVal) {
                this.updateLogoSettingform.app_path = this.settings.path
            }
        }
    },
    methods: {
        updateLogoSetting() {
            this.updateLogoSettingform.post('/settings/app_logo')
        },
        selectFile(event) {
            this.updateLogoSettingform.app_logo = event.target.files[0];
        }
    },
}
</script>
