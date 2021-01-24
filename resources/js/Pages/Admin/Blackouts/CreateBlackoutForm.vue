<template>
    <div>
        <form-section @submitted="createBlackout">
            <template #title>
                Schedule a Blackout
            </template>
            <template #form>
                <div class="mb-3">
                    <jet-input id="name" type="string" class="mt-1 block w-full" v-model="form.name" autofocus/>
                                        <jet-label for="name" value="Name"/>
                    <jet-input-error :message="form.error('name')" class="mt-2"/>
                </div>
                <div class="mb-3">
                    <jet-input id="start" type="datetime-local" class="mt-1 block w-full" v-model="form.start" autofocus/>
                    <jet-label for="start" value="Start time"/>
                    <jet-input-error :message="form.error('start')" class="mt-2"/>
                </div>
                <div class="mb-3">
                    <jet-input id="end" type="datetime-local" class="mt-1 block w-full" v-model="form.end"/>
                    <jet-label for="end" value="End Time"/>
                    <jet-input-error :message="form.error('end')" class="mt-2"/>
                </div>
                <input v-if = "room" type="hidden" id='room_id' v-model="form.room_id">
            </template>

            <template #actions>
                <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                    Created.
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
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
        FormSection,
        JetActionMessage,
        JetInputError,
        JetLabel
    },

    data() {
        return {
            form: this.$inertia.form({
                name:'',
                start: '',
                end: '',
                room_id:''
            }, {
                bag: 'createBlackout',
                resetOnSuccess: true,
            }),
        }
    },
    methods: {
        createBlackout() {
            this.form.post('/blackouts', {
                preserveScroll: true,
            })
        },
    },
    mounted() {
        this.form.room_id = this.room?.id;
    },
    props:{
        room: {}
    }
}
</script>
