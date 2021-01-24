<template>
    <div>
        <jet-form-section @submitted="createBlackout">
            <template #title>
                Schedule a Blackout
            </template>
            <template #description>
                Schedule a period where rooms cannot be booked.
            </template>
            <template #form>
                <div class="col-span-6 sm:col-span-3">
                    <jet-label for="start" value="Start time"/>
                    <jet-input id="start" type="datetime-local" class="mt-1 block w-full" v-model="form.start" autofocus/>
                    <jet-input-error :message="form.error('start')" class="mt-2"/>
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <jet-label for="end" value="End Time"/>
                    <jet-input id="end" type="datetime-local" class="mt-1 block w-full" v-model="form.end"/>
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
        </jet-form-section>
    </div>
</template>

<script>

import JetButton from '@src/Jetstream/Button'
import JetInput from '@src/Jetstream/Input'
import JetActionMessage from '@src/Jetstream/ActionMessage'
import JetFormSection from '@src/Jetstream/FormSection'
import JetInputError from '@src/Jetstream/InputError'
import JetLabel from '@src/Jetstream/Label'

export default {
    components: {
        JetButton,
        JetInput,
        JetFormSection,
        JetActionMessage,
        JetInputError,
        JetLabel
    },

    data() {
        return {
            form: this.$inertia.form({
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
