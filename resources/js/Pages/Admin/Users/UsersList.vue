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
                                <div class="text-md mr-3">
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
import moment from "moment";

export default {
    props: {
        users: {
            type: Array,
            default: function () {
                return []
            },
        }
    },

    components: {
        JetSectionBorder,
        JetActionSection,
        JetButton,
        JetDangerButton,
        JetSecondaryButton,
        JetConfirmationModal,
    },

    data() {
        return {
            deleteUserForm: this.$inertia.form(),
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
                this.userBeingDeleted = null
            })
        },

        fromNow(timestamp) {
            return moment(timestamp).local().fromNow()
        },

    }
}
</script>
