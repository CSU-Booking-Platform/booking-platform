<template>

    <!-- Generate Role -->
    <jet-form-section @submitted="createRole">
        <template #title>
            Create Role
        </template>

        <template #description>
            Roles are used across the application to authorize users when performing actions or viewing sensitive information.
        </template>

        <template #form>
            <!-- Role Name -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Name"/>
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autofocus/>
                <jet-input-error :message="form.error('name')" class="mt-2"/>
            </div>

            <!-- Permissions -->
            <div class="col-span-6" v-if="availablePermissions.length > 0">
                <jet-label for="permissions" value="Permissions" />

                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div v-for="permission in availablePermissions">
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox" :value="permission.name" v-model="form.permissions">
                            <span class="ml-2 text-md text-black">{{ permission.name }}</span>
                            <span class="ml-2 text-sm text-gray-600">{{ permission.guard_name }}</span>
                        </label>
                    </div>
                </div>
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Created
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Create
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
    import JetActionMessage from '@src/Jetstream/ActionMessage';
    import JetFormSection from '@src/Jetstream/FormSection';
    import JetButton from '@src/Jetstream/Button';
    import JetLabel from '@src/Jetstream/Label';
    import JetInput from '@src/Jetstream/Input';
    import JetInputError from '@src/Jetstream/InputError';

    export default {
        components: {
            JetActionMessage,
            JetFormSection,
            JetButton,
            JetLabel,
            JetInput,
            JetInputError,
        },

        props: {
            availablePermissions: {
                type: Array,
                default: () => {
                    return [];
                },
            }
        },

        data() {
            return {
                form: this.$inertia.form({
                    name: '',
                    permissions: [],
                }, {
                    bag: 'createRole',
                    resetOnSuccess: true,
                })
            }

        },

        methods: {
            createRole() {
                this.form.post('/roles', {
                    preserveScroll: true,
                }).then(response => {
                    //Handle response
                })
            },
        }
    }
</script>
