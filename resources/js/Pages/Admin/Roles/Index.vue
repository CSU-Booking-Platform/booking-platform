<template>
    <app-layout>
        <div class="flex flex-col lg:flex-row md:mx-auto md:w-11/12 ">
            <div class="m-4 w-1/4 pt-12 pb-48">
                <create-role-form :availablePermissions="permissions" />
            </div>
            <div v-if="roles.length > 0" class="m-4 w-3/4">
                <div class="m-12">
                    <role-table :roles="roles" :permissions="permissions"/>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@src/Layouts/AppLayout'
    import JetSectionBorder from '@src/Jetstream/SectionBorder'
    import JetActionSection from '@src/Jetstream/ActionSection'
    import JetConfirmationModal from '@src/Jetstream/ConfirmationModal'
    import JetSecondaryButton from '@src/Jetstream/SecondaryButton'
    import JetDangerButton from '@src/Jetstream/DangerButton'
    import JetButton from '@src/Jetstream/Button'
    import CreateRoleForm from "./CreateRoleForm";
    import UpdateRoleForm from "./UpdateRoleForm";
    import RoleTable from '@src/Components/Tables/RoleTable';

    export default {
        props: {
            roles: {
                type: Array,
                default: function () {
                    return []
                },
            },
            permissions: {
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
            JetButton,
            JetDangerButton,
            JetSecondaryButton,
            JetConfirmationModal,
            CreateRoleForm,
            UpdateRoleForm,
            RoleTable
        },

        data() {
            return {
                deleteRoleForm: this.$inertia.form(),
                roleBeingDeleted: null,
                roleBeingUpdated: null,
            }
        },

        methods: {
            deleteRole() {
                this.deleteRoleForm.delete('/admin/roles/' + this.roleBeingDeleted.id, {
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
