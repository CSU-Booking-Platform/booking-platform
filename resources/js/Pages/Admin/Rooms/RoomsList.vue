<template>
    <div>
        <!-- View Rooms -->
        <div class="mt-10 sm:mt-0">
            <jet-action-section>
                <template #title>
                    View Rooms
                </template>

                <template #description>
                    Description of rooms.
                </template>


                <!-- Rooms List -->
                <template #content>

                    <div class="space-y-6">
                        <div class="grid grid-cols-4">
                            <div class="text-md mx-3">Room Name</div>
                            <div class="text-md mx-3">Room Number</div>
                            <div class="text-md mx-3">Floor Number</div>
                            <div class="text-md mx-3">Building</div>
                        </div>

                        <div v-for="room in rooms" class="grid flex items-center">
                            <div class="grid grid-cols-4">
                                <div class="text-md mx-3">
                                    {{ room.name }}
                                </div>
                                <div class="text-md mx-3">
                                    {{ room.number }}
                                </div>
                                <div class="text-md mx-3">
                                    {{ room.floor }}
                                </div>
                                <div class="text-md mx-3">
                                    {{ room.building }}
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
        rooms: {
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
