<template>
    <app-layout>
        <div class="flex flex-col lg:flex-row md:mx-auto md:w-2/3 my-3">
            <div class="m-4 w-1/3">
                <create-user-form/>
            </div>
            <div v-if="users.length > 0" class="m-4 w-2/3">
                <users-list :users="users" :roles="roles" class="w-full"/>
            </div>
        </div>

    </app-layout>
</template>

<script>
import JetSectionBorder from '@src/Jetstream/SectionBorder'
import CreateUserForm from './CreateUserForm';
import UsersList from './UsersList';
import AppLayout from '@src/Layouts/AppLayout';
import XSection from '@src/Components/XSection';

export default {
    components: {
        CreateUserForm,
        AppLayout,
        UsersList,
        JetSectionBorder,
        XSection,
    },
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
    }
}
</script>
