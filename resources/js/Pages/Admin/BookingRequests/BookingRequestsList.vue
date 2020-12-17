<template>
    <div>
        <!-- View Rooms -->
        <div class="mt-10 sm:mt-0">
            <jet-action-section>
                <template #title>
                    View Booking Requests
                </template>

                <template #description>
                    Description of booking Requests.
                </template>


                <!-- Rooms List -->
                <template #content>

                    <div class="space-y-6">
                        <div class="grid grid-cols-4">
                            <div class="text-md mx-3">User creating the booking</div>
                            <div class="text-md mx-3">Room Number</div>
                            <div class="text-md mx-3">Start Time</div>
                            <div class="text-md mx-3">End time</div>
                        </div>

                        <div v-for="booking_request in booking_requests" :key="booking_request.id" class="grid flex items-center">
                            <div class="grid grid-cols-4">
                                <div class="text-md mx-3">
                                    {{ booking_request.user_id }}
                                </div>
                                <div class="text-md mx-3">
                                    {{ booking_request.room_id }}
                                </div>
                                <div class="text-md mx-3">
                                    {{ booking_request.start_time }}
                                </div>
                                <div class="text-md mx-3">
                                    {{ booking_request.end_time }}
                                </div>
                            </div>
                        </div>
                    </div>

                </template>
            </jet-action-section>
        </div>

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
import Dropdown from "@src/Jetstream/Dropdown";
import JetInput from "@src/Jetstream/Input"
import JetInputError from "@src/Jetstream/InputError"
import JetLabel from "@src/Jetstream/Label"
import Label from "@src/Jetstream/Label";

export default {
    props: {
        booking_requests: {
            type: Array,
            default: function () {
                return []
            }
        }
    },

    components: {
        Label,
        Dropdown,
        Input,
        JetSectionBorder,
        JetActionSection,
        JetButton,
        JetDangerButton,
        JetSecondaryButton,
        JetConfirmationModal,
        JetModal,
        JetInput,
        JetLabel,
        JetInputError
    },

    methods: {
    }
}
</script>
