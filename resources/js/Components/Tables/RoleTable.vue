<template>
  <div class="table-container">
    <table class="table-auto responsive-spaced">
      <caption></caption>
      <thead>
        <tr>
          <th class="p-3" id="id_role_name">Role Name</th>
          <th class="p-3" id="id_role_set">Permission Set</th>
          <th class="p-3" id="id_role_action">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr :dusk="'role-'+role.id" v-for="role in roles" :key="role.id">
          <td class="text-center">{{role.name}}</td>
          <td class="text-center">
            <jet-dropdown width="48">
              <template #trigger>
                <button
                  class="flex text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
                >
                  Permissions
                </button>
              </template>

              <template #content>
                <div v-for="permission in role.permissions" :key="permission.id" class="text-md mx-3">
                  {{permission.name}}
                </div>
              </template>
            </jet-dropdown>
          </td>
          <td>
            <div class="text-md mx-2">
                  <div class="text-md mx-3">
                    <button
                        class="cursor-pointer ml-6 text-sm inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 cursor-pointer ml-6 text-sm focus:outline-none"
                        @click="roleBeingUpdated = role"
                    >
                        Update
                    </button>
                    <button
                        class="cursor-pointer ml-6 text-sm text-red-500 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 cursor-pointer ml-6 text-sm focus:outline-none"
                        @click="roleBeingDeleted = role"
                    >
                        Delete
                    </button>
                  </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

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

    <update-role-form :permissions="permissions"
                              :role="roleBeingUpdated"
                              @close="roleBeingUpdated = null"></update-role-form>


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
import Input from "@src/Jetstream/Input";
import JetDropdown from "@src/Jetstream/Dropdown";
import UpdateRoomForm from "@src/Pages/Admin/Rooms/UpdateRoomForm";
import UpdateRoleForm from "@src/Pages/Admin/Roles/UpdateRoleForm";

export default {
  name: "RoleTable",
  props: {

    roles: {
      type: Array,
      default: [],
      required: true
    },
    permissions: {
      type: Array,
      default: [],
      required: true
    },
  },
  components: {
        JetDropdown,
        Input,
        JetSectionBorder,
        JetActionSection,
        JetButton,
        JetDangerButton,
        JetSecondaryButton,
        JetConfirmationModal,
        JetModal,
        UpdateRoomForm,
        UpdateRoleForm
  },
  data() {
      return {
        deleteRoleForm: this.$inertia.form(),
        roleBeingUpdated: null,
        roleBeingDeleted: null,
        filter: '',
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
    }
  }
};
</script>
