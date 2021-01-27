<template>
    <div>
        <form-section @submitted="createUser">
            <template #title>
                Create a User
            </template>

            <template #form>
                <div class="mb-3">
                    <jet-input id="name" type="text" class="mt-1 block w-full" v-model="createUserForm.name" autofocus/>
                    <jet-label for="name" value="Name"/>
                    <jet-input-error :message="createUserForm.error('name')" class="mt-2"/>
                </div>
                <div class="mb-3">
                    <jet-input id="email" type="email" class="mt-1 block w-full" v-model="createUserForm.email"/>
                    <jet-label for="email" value="Email"/>
                    <jet-input-error :message="createUserForm.error('email')" class="mt-2"/>
                </div>
                <div class="mb-3">
                    <jet-input id="password" type="password" class="mt-1 block w-full"
                               v-model="createUserForm.password"/>
                    <jet-label for="password" value="Password"/>
                    <jet-input-error :message="createUserForm.error('password')" class="mt-2"/>
                </div>
                <div class="mb-3">
                    <jet-input id="password_confirmation" type="password" class="mt-1 block w-full"
                               v-model="createUserForm.password_confirmation"/>
                    <jet-label for="password_confirmation" value="Password Confirmation"/>
                    <jet-input-error :message="createUserForm.error('password_confirmation')" class="mt-2"/>
                </div>
            </template>

            <template #actions>
                <jet-action-message :on="createUserForm.recentlySuccessful" class="mr-3">
                    Created.
                </jet-action-message>

                <jet-button id="create" :class="{ 'opacity-25': createUserForm.processing }" :disabled="createUserForm.processing">
                    Create
                </jet-button>
            </template>
        </form-section>
    </div>
</template>

<script>

import JetButton from '@src/Jetstream/Button'
import JetInput from '@src/Jetstream/Input'
import JetActionMessage from '@src/Jetstream/ActionMessage'
import FormSection from '@src/Components/FormSection'
import JetInputError from '@src/Jetstream/InputError'
import JetLabel from '@src/Jetstream/Label'

export default {
    components: {
        JetButton,
        JetInput,
        JetActionMessage,
        JetInputError,
        JetLabel,
        FormSection,
    },

    data() {
        return {
            createUserForm: this.$inertia.form({
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
            }, {
                bag: 'createUser',
                resetOnSuccess: true,
            }),
        }
    },
    methods: {
        createUser() {
            this.createUserForm.post('/users', {
                preserveScroll: true,
            })
        },
    },
}
</script>
