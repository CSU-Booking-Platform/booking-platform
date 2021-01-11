<template>
    <jet-dialog-modal :show="room" @close="closeModal">
        <template #title>
            Update Room
        </template>

        <template #content>
            <div class="m-6">
                <jet-label for="name" value="Room name" />
                <jet-input
                    id="name"
                    type="name"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    autofocus
                />
                <jet-input-error :message="form.error('name')" class="mt-2" />
            </div>

            <div class="m-6">
                <jet-label for="roomnumber" value="Room Number" />
                <jet-input
                    id="number"
                    type="roomnumber"
                    class="mt-1 block w-full"
                    v-model="form.number"
                    autofocus
                />
                <jet-input-error :message="form.error('number')" class="mt-2" />
            </div>

            <div class="m-6">
                <jet-label for="floor" value="Floor" />
                <jet-input
                    id="floor"
                    type="number"
                    class="mt-1 block w-full"
                    v-model="form.floor"
                    autofocus
                />
                <jet-input-error :message="form.error('floor')" class="mt-2" />
            </div>

            <div class="m-6">
                <jet-label for="building" value="Building" />
                <jet-input
                    id="building"
                    type="building"
                    class="mt-1 block w-full"
                    v-model="form.building"
                    autofocus
                />
                <jet-input-error
                    :message="form.error('building')"
                    class="mt-2"
                />
            </div>

            <div class="m-6">
                <jet-label for="status" value="Status" />
                <select v-model="form.status" class="mt-1 block w-full" name="status" id="statusUpdate">
                    <option :value="form.status" selected="selected">{{form.status}}</option>
                    <option v-if="form.status != 'available'" :value="'available'">available</option>
                    <option v-else :value="'unavailable'">anavailable</option>
                </select>
                <jet-input-error
                    :message="form.error('status')"
                    class="mt-2"
                />
            </div>


            <div class="m-6">
                <jet-label for="min_days_advance" value="Minimum Days Before Booking" />
                <jet-input
                    id="min_days_advance"
                    type="min_days_advance"
                    class="mt-1 block w-full"
                    v-model="form.min_days_advance"
                    autofocus
                />
                <jet-input-error :message="form.error('number')" class="mt-2" />
            </div>
            <div class="m-6">
                <jet-label for="max_days_advance" value="Maximum Days Before Booking" />
                <jet-input
                    id="max_days_advance"
                    type="max_days_advance"
                    class="mt-1 block w-full"
                    v-model="form.max_days_advance"
                    autofocus
                />
                <jet-input-error :message="form.error('number')" class="mt-2" />
            </div>

        </template>

        <template #footer>
            <jet-secondary-button @click.native="closeModal">
                Nevermind
            </jet-secondary-button>

            <jet-button
                class="ml-2"
                @click.native="updateRoom"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Update
            </jet-button>
        </template>
    </jet-dialog-modal>
</template>

<script>
import JetActionMessage from "@src/Jetstream/ActionMessage";
import JetFormSection from "@src/Jetstream/FormSection";
import JetDialogModal from "@src/Jetstream/DialogModal";
import JetButton from "@src/Jetstream/Button";
import JetSecondaryButton from "@src/Jetstream/SecondaryButton";
import JetLabel from "@src/Jetstream/Label";
import JetInput from "@src/Jetstream/Input";
import JetInputError from "@src/Jetstream/InputError";

export default {
    components: {
        JetActionMessage,
        JetFormSection,
        JetDialogModal,
        JetButton,
        JetSecondaryButton,
        JetLabel,
        JetInput,
        JetInputError
    },

    props: {
        room: {
            type: Object,
            required: false
        }
    },

    data() {
        return {
            form: this.$inertia.form(
                {
                    name: "",
                    number: null,
                    floor: "",
                    building: "",
                    status: "",
                    min_days_advance:"",
                    max_days_advance:""
                },
                {
                    bag: "updateRoom"
                }
            )
        };
    },

    methods: {
        closeModal() {
            if (this.$page && this.$page.errorBags.updateRoom) {
                delete this.$page.errorBags.updateRoom;
            }
            this.$emit("close");
        },
        updateRoom() {
            this.form
                .put("/rooms/" + this.room?.id, {
                    preserveState: true
                })
                .then(() => {
                    if (this.form.successful) {
                        this.closeModal();
                    }
                });
        }
    },
    watch: {
        room(room) {
            this.form.name = room?.name;
            this.form.number = room?.number;
            this.form.floor = room?.floor;
            this.form.building = room?.building;
            this.form.status = room?.status;
            this.form.status = room?.min_days_advance;
            this.form.status = room?.max_days_advance;
        }
    }
};
</script>
