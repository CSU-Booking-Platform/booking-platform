<template>
    <div>
        <!-- Manage This Room's Blackouts -->
    <blackouts-table :blackouts="blackouts">
      <template v-slot:blackout="{ blackout }">
        <div class="flex items-center">

          <button class="cursor-pointer ml-6 text-sm focus:outline-none"
                  @click="openUpdateModal(blackout)">
            Edit
          </button>

          <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                  @click="blackoutBeingDeleted = blackout">
            Delete
          </button>

        </div>
      </template>
    </blackouts-table>

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
import BlackoutsTable from "@src/Components/Tables/BlackoutsTable"

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
        JetInputError,
        BlackoutsTable
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
