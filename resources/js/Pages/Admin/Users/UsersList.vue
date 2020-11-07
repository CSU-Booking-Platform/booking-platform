<template>
    <div>
        <!-- Manage Application Users -->
        <div class="mt-10 sm:mt-0">
            <jet-action-section>
                <template #title>
                    Manage Users
                </template>

                <template #description>
                    You may delete any existing user, besides yourself.
                </template>


                <!-- Users List -->
                <template #content>
                    <div class="space-y-6">
                        <div v-for="user in users" class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="text-md mx-3">
                                    {{ user.name }}
                                </div>
                                <div class="text-sm text-gray-400">
                                    {{ user.email }}
                                </div>
                            </div>

                            <div class="flex items-center">

                                <div v-if="user.created_at" class="text-sm text-gray-400">
                                    Created {{ fromNow(user.created_at) }}
                                </div>

                                <button class="cursor-pointer ml-6 text-sm focus:outline-none"
                                        @click="userBeingUpdated = user">
                                    Edit
                                </button>

                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                                        @click="userBeingDeleted = user">
                                    Delete
                                </button>

                            </div>
                        </div>
                    </div>
                </template>
            </jet-action-section>
        </div>

        <jet-confirmation-modal :show="userBeingUpdated != null" @close="userBeingUpdated = null">
            <template #title>
                Update User: {{ userBeingUpdated != null && userBeingUpdated.name }}
            </template>

            <template #content>
                <div class="col-span-6 sm:col-span-3">
                    <jet-label for="name" value="Name"/>
                    <jet-input id="name" type="text" class="mt-1 block w-full" v-model="updateUserForm.name" autofocus/>
                    <jet-input-error :message="updateUserForm.error('name')" class="mt-2"/>
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <jet-label for="email" value="Email"/>
                    <jet-input id="email" type="email" class="mt-1 block w-full" v-model="updateUserForm.email"/>
                    <jet-input-error :message="updateUserForm.error('email')" class="mt-2"/>
                </div>
            </template>

            <template #footer>
                <jet-secondary-button @click.native="userBeingUpdated = null">
                    Nevermind
                </jet-secondary-button>

                <jet-button class="ml-2" @click.native="updateUser"
                            :class="{ 'opacity-25': updateUserForm.processing }"
                            :disabled="updateUserForm.processing">
                    Update
                </jet-button>
            </template>
        </jet-confirmation-modal>


        <!-- Delete User Confirmation Modal -->
        <jet-confirmation-modal :show="userBeingDeleted" @close="userBeingDeleted = null">
            <template #title>
                Delete User
            </template>

            <template #content>
                Are you sure you would like to delete this user?
            </template>

            <template #footer>
                <jet-secondary-button @click.native="userBeingDeleted = null">
                    Nevermind
                </jet-secondary-button>

                <jet-danger-button class="ml-2" @click.native="deleteUser"
                                   :class="{ 'opacity-25': deleteUserForm.processing }"
                                   :disabled="deleteUserForm.processing">
                    Delete
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>
    </div>
</template>

<script>
import JetSectionBorder from '@src/Jetstream/SectionBorder'
import JetActionSection from '@src/Jetstream/ActionSection'
import JetConfirmationModal from '@src/Jetstream/ConfirmationModal'
import JetSecondaryButton from '@src/Jetstream/SecondaryButton'
import JetDangerButton from '@src/Jetstream/DangerButton'
import JetButton from '@src/Jetstream/Button'
import JetModal from '@src/Jetstream/Modal'
import moment from "moment";
import Input from "@src/Jetstream/Input";
import Dropdown from "@src/Jetstream/Dropdown";
import JetInput from "@src/Jetstream/Input"
import JetInputError from "@src/Jetstream/InputError"
import JetLabel from "@src/Jetstream/Label"

export default {
    props: {
        users: {
            type: Array,
            default: function () {
                return []
            },
        },
        roles: {
            type: Array,
            default: function () {
                return []
            },
        }
    },

    components: {
        Dropdown,
        Input,
        JetSectionBorder,
        JetActionSection,
        JetButton,
        JetDangerButton,
        JetSecondaryButton,
        JetConfirmationModal,
        JetModal,
        JetInput,
        JetLabel,
        JetInputError
    },

    data() {
        return {
            deleteUserForm: this.$inertia.form(),
            updateUserForm: this.$inertia.form({
                name: '',
                email: '',
            }, {
                bag: 'updateUser',
                resetOnSuccess: true,
            }),
            userBeingDeleted: null,
            userBeingUpdated: null,
        }
    },

    methods: {
        deleteUser() {
            this.deleteUserForm.delete('/users/' + this.userBeingDeleted.id, {
                preserveScroll: true,
                preserveState: true,
            }).then(() => {
                // this.userBeingDeleted = null
            })
        },

        updateUser() {
            this.updateUserForm.put('/users/' + this.userBeingUpdated.id, {
                preserveScroll: true,
                preserveState: true,
            }).then(() => {
                this.userBeingUpdated = null
            })
        },

        fromNow(timestamp) {
            return moment(timestamp).local().fromNow()
        },

    }
}
</script>
