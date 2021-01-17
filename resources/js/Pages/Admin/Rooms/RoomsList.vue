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
                        <div class="grid grid-cols-7">
                            <div class="text-md mx-3">Room Name</div>
                            <div class="text-md mx-3">Room Number</div>
                            <div class="text-md mx-3">Floor Number</div>
                            <div class="text-md mx-3">Building</div>
                            <div class="text-md mx-3">Status</div>
                        </div>

                        <div v-for="room in rooms" :key="room.id" class="grid flex items-center">
                            <div class="grid grid-cols-7">
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
                                <div class="text-md mx-3">
                                    {{ room.status }}
                                </div>
                                <div class="text-md mx-2">
                                    <button
                                        class="cursor-pointer ml-6 text-sm text-blue-800 focus:outline-none"
                                        @click="roomBeingUpdated = room"
                                    >
                                        Update
                                    </button>
                                </div>
                                <div class="text-md mx-2">
                                    <button
                                        class="cursor-pointer ml-6 text-sm text-blue-800 focus:outline-none"
                                        @click="roomBeingDeleted = room"
                                    >
                                        Delete
                                    </button>
                                </div>

                                <div class="text-md mx-2">
                                <button class="cursor-pointer ml-6 text-sm text-blue-800 focus:outline-none"
                                        @click="openEditModal(room)">
                                    Edit
                                </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <update-room-form
                        :room="roomBeingUpdated"
                        @close="roomBeingUpdated = null">
                    </update-room-form>

                    <jet-confirmation-modal :show="roomBeingDeleted" @close="roomBeingDeleted = null">
                        <template #title>
                            Delete Room
                        </template>

                        <template #content>
                            Are you sure you would like to delete this room?
                        </template>

                        <template #footer>
                            <jet-secondary-button @click.native="roomBeingDeleted = null">
                                Nevermind
                            </jet-secondary-button>

                            <jet-danger-button class="ml-2" @click.native="deleteRoom"
                                               :class="{ 'opacity-25': deleteRoomForm.processing }"
                                               :disabled="deleteRoomForm.processing">
                                Delete
                            </jet-danger-button>
                        </template>
                    </jet-confirmation-modal>

                    <jet-confirmation-modal :show="roomRestBeingUpdated != null" @close="roomRestBeingUpdated = null">
                        <template #title>
                            Update Room Restrictions: {{ roomRestBeingUpdated != null && roomRestBeingUpdated.name }}
                        </template>

                        <template #content>

                            <!-- Permissions -->
                            <div class="mt-2 col-span-12" v-if="roles.length > 0">
                                <jet-label for="restrictions" value="Restrictions"/>

                                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div v-for="role in roles">
                                        <label class="flex items-center">
                                            <input type="checkbox" class="form-checkbox" :value="role.id"
                                                   v-model="updateRoomRestForm.restrictions">
                                            <span class="ml-2 text-md text-black">{{ role.name }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template #footer>
                            <jet-secondary-button @click.native="roomRestBeingUpdated = null">
                                Nevermind
                            </jet-secondary-button>

                            <jet-button class="ml-2" @click.native="updateRestrictions"
                                        :class="{ 'opacity-25': updateRoomRestForm.processing }"
                                        :disabled="updateRoomRestForm.processing">
                                Update
                            </jet-button>
                        </template>
                    </jet-confirmation-modal>


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
import UpdateRoomForm from "./UpdateRoomForm";
import Label from "@src/Jetstream/Label";

export default {
    props: {
        rooms: {
            type: Array,
            default: function () {
                return []
            }
        },
        roles: {
            type: Array,
            default: function () {
                return []
            },
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
        JetInputError,
        UpdateRoomForm
    },

    data() {
        return {
            deleteRoomForm: this.$inertia.form(),
            updateRoomRestForm: this.$inertia.form({
                restrictions: [],
            }, {
                bag: 'updateRoomRestriction',
                resetOnSuccess: true,
            }),
            roomBeingUpdated: null,
            roomRestBeingUpdated: null,
            roomBeingDeleted: null
        };
    },

    methods: {
        deleteRoom() {
            this.deleteRoomForm.delete('/rooms/' + this.roomBeingDeleted.id, {
                preserveScroll: true,
                preserveState: true,
            }).then(() => {
                this.roomBeingDeleted = null
            })
        },

        openEditModal(room) {
            this.setSelectedRestrictions(room)
            this.roomRestBeingUpdated = room;
        },

        setSelectedRestrictions(room) {
            this.updateRoomRestForm.restrictions = this.mapRoomRestrictions(room.restrictions)
        },

        mapRoomRestrictions(restrictions) {
            return restrictions.map((o) => {
                return o.id;
            });
        },
        updateRestrictions() {
            this.updateRoomRestForm.put('/room/restrictions/' + this.roomRestBeingUpdated.id, {
                preserveScroll: true,
                preserveState: true,
            }).then(() => {
                if (this.updateRoomRestForm.successful) {
                    this.roomRestBeingUpdated = null;
                }
            });
        },
    }
}
</script>
