<template>
    <div>
        <jet-form-section @submitted="createRoom">
            <template #title>
                Create a Room
            </template>

            <template #form>
                    <div class="col-span-6 sm:col-span-6">
                        <h4>Attributes</h4>
                        <hr>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <jet-label for="name" value="Room name"/>
                        <jet-input id="name" type="text" class="mt-1 block w-full" v-model="createRoomForm.name" autofocus/>
                        <jet-input-error :message="createRoomForm.error('name')" class="mt-2"/>
                    </div>
                     
                    <div class="col-span-6 sm:col-span-3">
                        <jet-label for="roomnumber" value="Room Number"/>
                        <jet-input id="number" type="roomnumber" class="mt-1 block w-full" v-model="createRoomForm.number"/>
                        <jet-input-error :message="createRoomForm.error('roomnumber')" class="mt-2"/>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <jet-label for="floor" value="Floor"/>
                        <jet-input id="floor" type="number" class="mt-1 block w-full" v-model="createRoomForm.floor"/>
                        <jet-input-error :message="createRoomForm.error('floor')" class="mt-2"/>
                    </div>

                    <div class="col-span-6 sm:col-span-3">  
                        <jet-label for="building" value="Building"/>
                        <jet-input id="building" type="building" class="mt-1 block w-full" v-model="createRoomForm.building"/>
                        <jet-input-error :message="createRoomForm.error('building')" class="mt-2"/>
                    </div>

                    <div class="col-span-6 sm:col-span-3">  
                        <jet-label for="status" value="Status"/>
                        <select v-model="createRoomForm.status" class="mt-1 block w-full" name="status" id="status">
                            <option value="" selected disabled hidden>Select Room Status</option>
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                        <jet-input-error :message="createRoomForm.error('status')" class="mt-2"/>
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <h4>Availabilities</h4>
                        <hr>
                    </div>

                    <div
                        v-for="(value, key) in createRoomForm.availabilities"
                        class="col-span-6 sm:col-span-3"
                    >
                        <h3>{{ key }}</h3>

                        <jet-label value="Opening hours" />
                        <jet-input
                            type="time"
                            class="mt-1 block w-full"
                            v-model="value.opening_hours"
                        />
                        <jet-input-error
                            :message="createRoomForm.error('availabilities.' + key + '.opening_hours')"
                            class="mt-2"
                        />

                        <jet-label value="Closing hours" />
                        <jet-input
                            type="time"
                            class="mt-1 block w-full"
                            v-model="value.closing_hours"
                        />
                        <jet-input-error
                            :message="createRoomForm.error('availabilities.' + key + '.closing_hours')"
                            class="mt-2"
                        />
                    </div>

                    <div class="col-span-6 sm:col-span-3">  
                        <jet-label for="min_days_advance" value="Minimum Days Before Booking"/>
                        <jet-input id="min_days_advance" type="min_days_advance" class="mt-1 block w-full" v-model="createRoomForm.min_days_advance"/>
                        <jet-input-error :message="createRoomForm.error('min_days_advance')" class="mt-2"/>
                    </div>

                    <div class="col-span-6 sm:col-span-3">  
                        <jet-label for="max_days_advance" value="Maximum Days Before Booking"/>
                        <jet-input id="max_days_advance" type="max_days_advance" class="mt-1 block w-full" v-model="createRoomForm.max_days_advance"/>
                        <jet-input-error :message="createRoomForm.error('max_days_advance')" class="mt-2"/>
                    </div>

            </template>

            <template #actions>
                <jet-action-message :on="createRoomForm.recentlySuccessful" class="mr-3">
                    Created.
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': createRoomForm.processing }" :disabled="createRoomForm.processing">
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
            createRoomForm: this.$inertia.form({
                name: '',
                number: '',
                floor: '',
                building: '',
                status: '',
                availabilities: {
                    Monday: {
                        opening_hours: '',
                        closing_hours: ''
                    },
                    Tuesday: {
                        opening_hours: '',
                        closing_hours: ''
                    },
                    Wednesday: {
                        opening_hours: '',
                        closing_hours: ''
                    },
                    Thursday: {
                        opening_hours: '',
                        closing_hours: ''
                    },
                    Friday: {
                        opening_hours: '',
                        closing_hours: ''
                    },
                    Saturday: {
                        opening_hours: '',
                        closing_hours: ''
                    },
                    Sunday: {
                        opening_hours: '',
                        closing_hours: ''
                    },
                },
                min_days_advance:'',
                max_days_advance:''
            }, {
                bag: 'createRoom',
                resetOnSuccess: true,
            }),
        }
    },
    methods: {
        createRoom() {
            this.createRoomForm.post('/rooms', {
                preserveScroll: true,
            })
        },
    },
}
</script>
