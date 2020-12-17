<template>
    <div>
        <jet-form-section @submitted="createBookingRequest">
            <template #title>
                Create a Booking Request
            </template>

            <template #form>
                <div class="col-span-6 sm:col-span-3">

                    <div class="m-6">
                        <jet-label for="room_id" value="Room ID"/>
                        <jet-input id="room_id" type="number" class="mt-1 block w-full" v-model="createBookingRequest.room_id" autofocus/>
                        <!-- <jet-input-error :message="createBookingRequest.error('room_id')" class="mt-2"/> -->
                    </div>
                     
                    <div class="m-6">
                        <jet-label for="user_id" value="User ID"/>
                        <jet-input id="user_id" type="number" class="mt-1 block w-full" v-model="createBookingRequest.user_id"/>
                        <!-- <jet-input-error :message="createBookingRequest.error('user_id')" class="mt-2"/> -->
                    </div>

                    <div class="m-6">
                        <jet-label for="start_time" value="Start Time"/>
                        <jet-input id="start_time" type="datetime-local" class="mt-1 block w-full" v-model="createBookingRequest.start_time"/>
                        <!-- <jet-input-error :message="createBookingRequest.error('start_time')" class="mt-2"/> -->
                    </div>

                    <div class="m-6">  
                        <jet-label for="end_time" value="End Time"/>
                        <jet-input id="end_time" type="datetime-local" class="mt-1 block w-full" v-model="createBookingRequest.end_time"/>
                        <!-- <jet-input-error :message="createBookingRequest.error('end_time')" class="mt-2"/> -->
                    </div>

                </div>
            </template>

            <template #actions>
                <jet-action-message :on="createBookingRequest.recentlySuccessful" class="mr-3">
                    Created.
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': createBookingRequest.processing }" :disabled="createBookingRequest.processing">
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
        JetLabel,
    },

    data() {
        return {
            createBookingRequestForm: this.$inertia.form({
                user_id: '',
                room_id: '',
                start_time: '',
                end_time: '',
            }, {
                bag: 'createBookingRequest',
                resetOnSuccess: true,
            }),
        }
    },
    methods: {
        createBookingRequest() {
            this.createBookingRequest.post('/book', {
                preserveScroll: true,
            })
        },
    },
}
</script>
