<template>
    <div>
        <!-- Manage This Room's Blackouts -->
        <div class="mt-10 sm:mt-0">
            <jet-action-section>
                <!-- Blackouts List -->
                <template #content>
                    <div class="space-y-6">
                        <div v-for="blackout in blackouts" :key="blackout.id" class="flex items-center justify-between">
                            <div class="flex items-center col-md-auto">
                                <div class="md-6">
                                    Name: {{ blackout.name}}
                                </div>
                                <div class="">
                                    Start: {{ blackout.start_time | legible_date}}
                                </div>
                                <div class="">
                                   End: {{ blackout.end_time | legible_date}}
                                </div>
                            </div>

                            <div class="flex items-center">
                                <button class="cursor-pointer ml-4 text-sm focus:outline-none"
                                        @click="openUpdateModal(blackout)">
                                    Edit
                                </button>

                                <button class="cursor-pointer ml-4 text-sm text-red-500 focus:outline-none"
                                        @click="blackoutBeingDeleted = blackout">
                                    Delete
                                </button>

                            </div>
                        </div>
                    </div>
                </template>
            </jet-action-section>
        </div>

        <!--Update Blackout Form -->
        <jet-confirmation-modal :show="blackoutBeingUpdated != null" @close="blackoutBeingUpdated = null">
            <template #title>
                Update Blackout: {{ blackoutBeingUpdated.name}}
            </template>

            <template #content>
                <div>
                     <jet-label for="start" value="Start time"/>
                    <jet-input id="start" type="datetime-local" class="mt-1 block w-full" v-model="updateBlackoutForm.start" autofocus/>
                    <jet-input-error :message="updateBlackoutForm.error('start')" class="mt-2"/>
                </div>
                <div>
                    <jet-label for="end" value="End Time"/>
                    <jet-input id="end" type="datetime-local" class="mt-1 block w-full" v-model="updateBlackoutForm.end"/>
                    <jet-input-error :message="updateBlackoutForm.error('end')" class="mt-2"/>
                </div>
            </template>

            <template #footer>
                <jet-secondary-button @click.native="blackoutBeingUpdated = null">
                    Nevermind
                </jet-secondary-button>

                <jet-button class="ml-2" @click.native="updateBlackout"
                            :class="{ 'opacity-25': updateBlackoutForm.processing }"
                            :disabled="updateBlackoutForm.processing">
                    Update
                </jet-button>
            </template>
        </jet-confirmation-modal>


        <!-- Delete Blackout Confirmation Modal -->
        <jet-confirmation-modal :show="blackoutBeingDeleted" @close="blackoutBeingDeleted = null">
            <template #title>
                Delete blackout period
            </template>

            <template #content>
                Are you sure you would like to delete this blackout period?
            </template>

            <template #footer>
                <jet-secondary-button @click.native="blackoutBeingDeleted = null">
                    Nevermind
                </jet-secondary-button>

                <jet-danger-button class="ml-2" @click.native="deleteBlackout"
                                   :class="{ 'opacity-25': deleteBlackoutForm.processing }"
                                   :disabled="deleteBlackoutForm.processing">
                    Delete
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>
    </div>
</template>

<script>
import JetActionSection from '@src/Jetstream/ActionSection'
import JetConfirmationModal from '@src/Jetstream/ConfirmationModal'
import JetSecondaryButton from '@src/Jetstream/SecondaryButton'
import JetDangerButton from '@src/Jetstream/DangerButton'
import JetButton from '@src/Jetstream/Button'
import JetModal from '@src/Jetstream/Modal'
import moment from "moment";
import Input from "@src/Jetstream/Input";
import Dropdown from "@src/Jetstream/Dropdown";
import JetInput from "@src/Jetstream/Input"
import JetInputError from "@src/Jetstream/InputError"
import JetLabel from "@src/Jetstream/Label"
import Label from "@src/Jetstream/Label";

export default {
    props: {
        blackouts: {
            type: Array,
            default: function () {
                return []
            },
        },
    },

    components: {
        Label,
        Dropdown,
        Input,
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

    data() {
        return {
            deleteBlackoutForm: this.$inertia.form(),
            updateBlackoutForm: this.$inertia.form({
                start: '',
                end: '',
                room: [],
            }, {
                bag: 'updateBlackout',
                resetOnSuccess: true,
            }),
            blackoutBeingDeleted: null,
            blackoutBeingUpdated: null,
        }
    },

    filters: {

        legible_date: function (date) {
            return moment(date).format("LLL");
        },
    },

    methods: {
        deleteBlackout() {
            this.deleteBlackoutForm.delete('/blackouts/'+ this.blackoutBeingDeleted.id, {
                preserveScroll: true,
                preserveState: true,
            }).then(() => {
                this.blackoutBeingDeleted = null
            })
        },

        openUpdateModal(blackout) {
            this.blackoutBeingUpdated = blackout;
            this.updateBlackoutForm.start = blackout.start_time;
            this.updateBlackoutForm.end = blackout.end_time;
        },


        updateBlackout() {
            this.updateBlackoutForm.put('/blackouts/'+ this.blackoutBeingUpdated.id,{
                preserveScroll: true,
                preserveState: true,
            }).then(() => {
                if (this.updateBlackoutForm.successful) {
                    this.blackoutBeingUpdated = null;
                }
            });
        },

    }
}
</script>
