<template>
    <jet-dialog-modal  :show="room" @close="closeModal">
        <template #title>
            Update Room
        </template>

        <template #content>
            <div class="overflow-y-auto max-h-96">
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
                        min="0"
                        class="mt-1 block w-full"
                        v-model="form.floor"
                        autofocus
                    />
                    <jet-input-error :message="form.error('floor')" class="mt-2" />
                </div>

                <div class="m-6">
                    <jet-label for="building" value="Building" />
                    <select v-model="form.building" class="mt-1 block w-full" name="building" id="buildingUpdate">
                       <option v-for="building in availableBuildings" :key="building" :value="building" :selected="form.building===building">{{building}}</option>
                    </select>
                    <jet-input-error
                        :message="form.error('building')"
                        class="mt-2"
                    />
                </div>

                <div class="m-6">
                    <jet-label for="status" value="Status" />
                    <select v-model="form.status" class="mt-1 block w-full" name="status" id="statusUpdate">
                        <option :value="form.status" selected="selected">{{form.status}}</option>
                        <option v-if="form.status !== 'available'" :value="'available'">available</option>
                        <option v-else :value="'unavailable'">unavailable</option>
                    </select>
                    <jet-input-error
                        :message="form.error('status')"
                        class="mt-2"
                    />
                </div>

             <div class="m-6">
                <jet-label for="room_type" value="Room Type" />
                <select v-model="form.room_type" class="mt-1 block w-full" name="room_type" id="room_typeUpdate">
                    <option v-for="roomType in availableRoomTypes" :key="roomType" :value="roomType" :selected="form.room_type===roomType">{{roomType}}</option>
                </select>
                <jet-input-error
                    :message="form.error('room_type')"
                    class="mt-2"
                />
            </div>


                <div class="m-6">
                    <jet-label for="stand_capacity" value="Standing Capacity"/>
                    <jet-input id="stand_capacity" type="number" min="0" class="mt-1 block w-full" v-model="form.capacity_standing"/>
                    <jet-input-error :message="form.error('stand_capacity')" class="mt-2"/>
                </div>

                <div class="m-6">
                    <jet-label for="sit_capacity" value="Seating Capacity"/>
                    <jet-input id="sit_capacity" type="number" min="0" class="mt-1 block w-full" v-model="form.capacity_sitting"/>
                    <jet-input-error :message="form.error('sit_capacity')" class="mt-2"/>
                </div>

                <div class="m-6">
                    <jet-label for="food" value="Food"/>
                    <input type="checkbox" id="food" class="form-checkbox" v-model="form.food"/>
                    <jet-input-error :message="form.error('food')" class="mt-2"/>
                </div>

                <div class="m-6">
                    <jet-label for="alcohol" value="Alcohol"/>
                    <input type="checkbox" id="alcohol" class="form-checkbox" v-model="form.alcohol"/>
                    <jet-input-error :message="form.error('alcohol')" class="mt-2"/>
                </div>

                <div class="m-6">
                    <jet-label for="a_v_permitted" value="A/V Equipment Permitted"/>
                    <input type="checkbox" id="a_v_permitted" class="form-checkbox" v-model="form.a_v_permitted"/>
                    <jet-input-error :message="form.error('a_v_permitted')" class="mt-2"/>
                </div>

                <div class="m-6">
                    <jet-label for="status" value="Status"/>
                    <select v-model="form.status" class="mt-1 block w-full" name="status" id="status">
                        <option value="" selected disabled hidden>Select Room Status</option>
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                    <jet-input-error :message="form.error('status')" class="mt-2"/>
                </div>

                <div class="m-6">
                    <jet-label for="equippment" value="Equippment"/>
                    <br>
                    <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox" v-model="form.projector"/>
                    <span class="ml-2 text-sm text-black">Projector</span>
                    </label>
                    <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox" v-model="form.television"/>
                    <span class="ml-2 text-sm text-black">Television</span>
                    </label>
                    <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox" v-model="form.computer"/>
                    <span class="ml-2 text-sm text-black">Computer</span>
                    </label>
                    <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox" v-model="form.whiteboard"/>
                    <span class="ml-2 text-sm text-black">Whiteboard</span>
                    </label>
                    <jet-input-error :message="form.error('equippment')" class="mt-2"/>
                </div>

                <div class="m-6">
                    <jet-label for="restrictions" value="Restrictions"/>
                    <br>
                    <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox" v-model="form.ambiant_music"/>
                    <span class="ml-2 text-sm text-black">Ambiance Music</span>
                    </label>
                    <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox" v-model="form.sale_for_profit"/>
                    <span class="ml-2 text-sm text-black">Sale for Profit</span>
                    </label>
                    <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox" v-model="form.fundraiser"/>
                    <span class="ml-2 text-sm text-black">Fundraiser</span>
                    </label>
                    <jet-input-error :message="form.error('restrictions')" class="mt-2"/>
                </div>

                <div class="m-6">
                    <jet-label for="furniture" value="Furniture"/>
                    <br>
                    <label class="flex items-center text-sm">Sofa(s)</label>
                    <jet-input  v-model.number="form.sofas" type="number" min="0"/>

                    <label class="flex items-center">Coffee Table(s)</label>
                    <jet-input  v-model.number="form.coffee_tables" type="number" min="0"/>

                    <label class="flex items-center">Table(s)</label>
                    <jet-input  v-model.number="form.tables" type="number" min="0"/>

                    <label class="flex items-center">Chair(s)</label>
                    <jet-input  v-model.number="form.chairs" type="number" min="0"/>

                    <jet-input-error :message="form.error('furniture')" class="mt-2"/>
                </div>

                <div
                    v-on:click="toggle"
                    class="m-6"
                >
                    <br>
                    <h4>Availabilities</h4>
                    <hr>
                </div>

                <div
                    v-show="showAvailabilities"
                    v-for="(value, key) in form.availabilities"
                    class="m-6"
                >
                    <h3>{{ key }}</h3>

                    <jet-label value="Opening hours"/>
                    <jet-input
                        type="time"
                        class="mt-1 block w-full"
                        v-model="value.opening_hours"
                        :id="key + '_opening_hours'"
                        :dusk="key + '_opening_hours'"
                    />
                    <jet-input-error
                        :message="form.error('availabilities.' + key + '.opening_hours')"
                        class="mt-2"
                    />

                    <jet-label value="Closing hours"/>
                    <jet-input
                        type="time"
                        class="mt-1 block w-full"
                        v-model="value.closing_hours"
                        :id="key + '_closing_hours'"
                        :dusk="key + '_closing_hours'"
                    />
                    <jet-input-error
                        :message="form.error('availabilities.' + key + '.closing_hours')"
                        class="mt-2"
                    />
                </div>

                <div
                    v-show="showAvailabilities"
                    class="m-6"
                >
                    <jet-label for="min_days_advance" value="Minimum Days Before Booking"/>
                    <jet-input
                        id="min_days_advance"
                        type="number"
                        min="0"
                        class="mt-1 block w-full"
                        v-model="form.min_days_advance"
                        autofocus
                    />
                    <jet-input-error :message="form.error('number')" class="mt-2"/>
                    <jet-label for="max_days_advance" value="Maximum Days Before Booking"/>
                    <jet-input
                        id="max_days_advance"
                        type="number"
                        min="0"
                        class="mt-1 block w-full"
                        v-model="form.max_days_advance"
                        autofocus
                    />
                    <jet-input-error :message="form.error('number')" class="mt-2"/>
                </div>
            </div>
        </template>

        <template #footer>
            <jet-secondary-button @click.native="closeModal">
                Nevermind
            </jet-secondary-button>

            <jet-button
              id="updateRoom"
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
        },

        availableRoomTypes: {
            type: Array,
            required: true
        },
        availableBuildings: {
            type: Array,
            required: true
        },
    },

    data() {
        return {
            form: this.$inertia.form(
                {
                    name: "",
                    number: null,
                    floor: "",
                    building: "",
                    projector:  false,
                    television: false,
                    computer: false,
                    whiteboard: false,
                    capacity_standing: '',
                    capacity_sitting: '',
                    food: false,
                    alcohol: false,
                    a_v_permitted: false,
                    sofas: '',
                    coffee_tables: '',
                    tables: '',
                    chairs: '',
                    ambiant_music: false,
                    sale_for_profit: false,
                    fundraiser: false,
                    status: "",
                    room_type: "",
                    min_days_advance:"",
                    max_days_advance:"",
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
                },
                {
                    bag: "updateRoom"
                }
            ),
            showAvailabilities: true
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
                .put("/admin/rooms/" + this.room?.id, {
                    preserveState: true
                })
                .then(() => {
                    if (this.form.successful) {
                        this.closeModal();
                    }
                });
        },
        toggle() {
            this.showAvailabilities = !this.showAvailabilities
        },
        resetAvailabilities(){
            this.form.availabilities = {
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
                }
            }
        }
    },
    watch: {
        room(room) {
            this.form.name = room?.name;
            this.form.number = room?.number;
            this.form.floor = room?.floor;
            this.form.building = room?.building;
            this.form.projector = room?.attributes.projector;
            this.form.television = room?.attributes.television;
            this.form.computer = room?.attributes.computer;
            this.form.whiteboard = room?.attributes.whiteboard;
            this.form.capacity_standing = room?.attributes.capacity_standing;
            this.form.capacity_sitting = room?.attributes.capacity_sitting;
            this.form.food = room?.attributes.food;
            this.form.alcohol = room?.attributes.alcohol;
            this.form.a_v_permitted = room?.attributes.a_v_permitted;
            this.form.sofas = room?.attributes.sofas;
            this.form.coffee_tables = room?.attributes.coffee_tables;
            this.form.tables = room?.attributes.tables;
            this.form.chairs = room?.attributes.chairs;
            this.form.ambiant_music = room?.attributes.ambiant_music;
            this.form.sale_for_profit = room?.attributes.sale_for_profit;
            this.form.fundraiser = room?.attributes.fundraiser;
            this.form.status = room?.status;
            this.form.room_type = room?.room_type;
            this.form.min_days_advance = room?.min_days_advance;
            this.form.max_days_advance = room?.max_days_advance;
            this.resetAvailabilities();
            room?.availabilities.forEach((availability) => {
                this.form.availabilities[availability.weekday].opening_hours = availability.opening_hours;
                this.form.availabilities[availability.weekday].closing_hours = availability.closing_hours;
            })
        }
    }
};
</script>
