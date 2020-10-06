<template>
    <form action="api/users" method="POST" @submit.prevent="createUser" class="w-full max-w-lg">
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">
                    Name
                </label>
                <input
                    v-model="form.name"
                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                    id="name" type="text" placeholder="John Doe">
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                       for="password_confirmation">
                    Email
                </label>
                <input
                    v-model="form.email"
                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                    id="email" type="email" placeholder="email@domain.lel">
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="password">
                    Password
                </label>
                <input
                    v-model="form.password"
                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                    id="password" type="password" placeholder="***********">
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                       for="password_confirmation">
                    Password Confirmation
                </label>
                <input
                    v-model="form.password_confirmation"
                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                    id="password_confirmation" type="password" placeholder="***********">
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-primary">Submit</button>
        </div>
        <li v-for="error in form.errors">
            {{error}}
        </li>
    </form>

</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            form: {
                errors: '',
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
            }
        }
    },
    methods: {
        createUser() {
            this.$inertia.post('/api/users', this.form).then(()=>{})
        }
    }
}
</script>
