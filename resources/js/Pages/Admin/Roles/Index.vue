<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Roles
            </h2>
        </template>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <create-role-form></create-role-form>

            <div v-if="roles.length > 0">
                <jet-section-border/>

                <!-- Manage Application Roles -->
                <div class="mt-10 sm:mt-0">
                    <jet-action-section>
                        <template #title>
                            Manage Roles
                        </template>

                        <template #description>
                            You may delete any of your existing roles if they are no longer needed.
                        </template>

                        <!-- Roles List -->
                        <template #content>
                            <div class="space-y-6">
                                <div v-for="role in roles" class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="text-md mr-3">
                                            {{ role.name }}
                                        </div>
                                        <div class="text-sm text-gray-400">
                                            {{ role.guard_name }}
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div v-if="role.created_at" class="text-sm text-gray-400">
                                            Created {{ fromNow(role.created_at) }}
                                        </div>

                                        <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                                                @click="roleBeingDeleted = role">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </jet-action-section>
                </div>
            </div>

            <!-- Delete Role Confirmation Modal -->
            <jet-confirmation-modal :show="roleBeingDeleted" @close="roleBeingDeleted = null">
                <template #title>
                    Delete Role
                </template>

                <template #content>
                    Are you sure you would like to delete this role?
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="roleBeingDeleted = null">
                        Nevermind
                    </jet-secondary-button>

                    <jet-danger-button class="ml-2" @click.native="deleteRole"
                                       :class="{ 'opacity-25': deleteRoleForm.processing }"
                                       :disabled="deleteRoleForm.processing">
                        Delete
                    </jet-danger-button>
                </template>
            </jet-confirmation-modal>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@src/Layouts/AppLayout'
    import JetSectionBorder from '@src/Jetstream/SectionBorder'
    import JetActionSection from '@src/Jetstream/ActionSection'
    import JetConfirmationModal from '@src/Jetstream/ConfirmationModal'
    import JetDangerButton from '@src/Jetstream/DangerButton'
    import JetSecondaryButton from '@src/Jetstream/SecondaryButton'
    import CreateRoleForm from "./CreateRoleForm";
    import moment from "moment";

    export default {
        props: {
            roles: {
                type: Array,
                default: function () {
                    return []
                },
            }
        },

        components: {
            AppLayout,
            JetSectionBorder,
            JetActionSection,
            JetConfirmationModal,
            JetDangerButton,
            JetSecondaryButton,
            CreateRoleForm,
        },

        data() {
            return {
                deleteRoleForm: this.$inertia.form(),
                roleBeingDeleted: null,
            }
        },

        methods: {
            deleteRole() {
                this.deleteRoleForm.delete('/roles/' + this.roleBeingDeleted.id, {
                    preserveScroll: true,
                    preserveState: true,
                }).then(() => {
                    this.roleBeingDeleted = null
                })
            },

            fromNow(timestamp) {
                return moment(timestamp).local().fromNow()
            },

        }
    }
</script>
