<template>
    <app-layout>
        <div class="flex flex-col lg:flex-row md:mx-auto md:w-11/12">
            <div class="mt-8 w-1/4 pt-12 pb-48">
                <create-user-form/>
            </div>
            <div v-if="users.length > 0" class="m-4 w-3/4">
                <div class="mx-24 mt-16">
                    <users-list :users="users" :roles="roles" class="w-full"/>
                </div>               
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
