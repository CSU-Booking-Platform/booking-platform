<template>
  <div>
    <form-section @submitted="createRoom">
      <template #title>
        Create a Room
      </template>

            <template #form>
              <div class="overflow-y-auto max-h-96 w-full">
                  <div class="mb-3 w-4/5">
                      <jet-label for="name" value="Room name"/>
                      <jet-input id="name" type="text" class="mt-1 block w-full" v-model="createRoomForm.name" autofocus/>
                      <jet-input-error :message="createRoomForm.error('name')" class="mt-2"/>
                  </div>
                  <div class="mb-3 w-4/5">
                      <jet-label for="roomnumber" value="Room Number"/>
                      <jet-input id="number" type="roomnumber" class="mt-1 block w-full" v-model="createRoomForm.number"/>
                      <jet-input-error :message="createRoomForm.error('roomnumber')" class="mt-2"/>
                  </div>
                  <div class="mb-3 w-4/5">
                      <jet-label for="floor" value="Floor"/>
                      <jet-input id="floor" type="number" min="0" class="mt-1 block w-full" v-model="createRoomForm.floor"/>
                      <jet-input-error :message="createRoomForm.error('floor')" class="mt-2"/>
                  </div>
                  <div class="mb-3 w-4/5">
                      <jet-label for="building" value="Building"/>
                      <select
                        v-model="createRoomForm.building"
                        class="mt-1 block w-full"
                        name="building"
                        id="building"
                      >
                          <option value="" selected disabled hidden>Select Building Name</option>
                          <option v-for="building in availableBuildings" :key="building" :value="building">{{ building }}</option>
                      </select>
                      <jet-input-error :message="createRoomForm.error('building')" class="mt-2"/>
                  </div>
                  <div
                      v-on:click="toggle('attributes');"
                      class="col-span-6 sm:col-span-6 w-4/5"
                  >
                      <br>
                      <h4 id="attributes">Attributes</h4>
                      <hr>
                  </div>

                  <div
                      v-show="showAttributes"
                      class="col-span-6 sm:col-span-3 w-4/5"
                  >
                    <div class="col-span-6 sm:col-span-3"><br>
                        <jet-label for="stand_capacity" value="Standing Capacity"/>
                        <jet-input id="stand_capacity" type="number" min="0" class="mt-1 block w-full" v-model.number="createRoomForm.capacity_standing"/>
                        <jet-input-error :message="createRoomForm.error('capacity_standing')" class="mt-2"/>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <jet-label for="sit_capacity" value="Seating Capacity"/>
                        <jet-input id="sit_capacity" type="number" min="0" class="mt-1 block w-full" v-model.number="createRoomForm.capacity_sitting"/>
                        <jet-input-error :message="createRoomForm.error('sit_capacity')" class="mt-2"/>
                    </div><br>

                    <div class="col-span-6 sm:col-span-3">
                        <jet-label for="food" value="Food"/>
                        <input type="checkbox" id="checkboxFood" class="form-checkbox" v-model="createRoomForm.food"/>
                        <jet-input-error :message="createRoomForm.error('food')" class="mt-2"/>
                    </div><br>

                    <div class="col-span-6 sm:col-span-3">
                        <jet-label for="alcohol" value="Alcohol"/>
                        <input type="checkbox" id="checkboxAlcohol" class="form-checkbox" v-model="createRoomForm.alcohol"/>
                        <jet-input-error :message="createRoomForm.error('alcohol')" class="mt-2"/>
                    </div><br>

            <div class="col-span-6 sm:col-span-3">
              <jet-label for="a_v_permitted" value="A/V Equipment Permitted"/>
              <input type="checkbox" id="checkboxAV" class="form-checkbox" v-model="createRoomForm.a_v_permitted"/>
              <jet-input-error :message="createRoomForm.error('a_v_permitted')" class="mt-2"/>
            </div>
            <br>

            <div class="col-span-6 sm:col-span-3">
              <jet-label for="status" value="Status"/>
              <select v-model="createRoomForm.status" class="mt-1 block w-full" name="status" id="status">
                <option value="" selected disabled hidden>Select Room Status</option>
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
              </select>
              <jet-input-error :message="createRoomForm.error('status')" class="mt-2"/>
            </div>
            <br>

            <div class="col-span-6 sm:col-span-3">
              <jet-label for="room_type" value="Room type"/>
              <select
                v-model="createRoomForm.room_type"
                class="mt-1 block w-full"
                name="room_type"
                id="room_type"
              >
                <option value="" selected disabled hidden>Select Room Type</option>
                <option v-for="roomType in availableRoomTypes" :key="roomType" :value="roomType">{{ roomType }}</option>
              </select>
              <jet-input-error
                :message="createRoomForm.error('room_type')"
                class="mt-2"
              />
            </div>
            <br>

            <div class="col-span-6 sm:col-span-3">
              <jet-label for="equippment" value="Equippment"/>
              <label class="flex items-center">
                <input type="checkbox" class="form-checkbox" v-model="createRoomForm.projector"/>
                <span class="ml-2 text-sm text-black">Projector</span>
              </label>
              <label class="flex items-center">
                <input type="checkbox" class="form-checkbox" v-model="createRoomForm.television"/>
                <span class="ml-2 text-sm text-black">Television</span>
              </label>
              <label class="flex items-center">
                <input type="checkbox" class="form-checkbox" v-model="createRoomForm.computer"/>
                <span class="ml-2 text-sm text-black">Computer</span>
              </label>
              <label class="flex items-center">
                <input type="checkbox" class="form-checkbox" v-model="createRoomForm.whiteboard"/>
                <span class="ml-2 text-sm text-black">Whiteboard</span>
              </label>
              <jet-input-error :message="createRoomForm.error('equippment')" class="mt-2"/>
            </div>
            <br>

            <div class="col-span-6 sm:col-span-3">
              <jet-label for="restrictions" value="Restrictions"/>
              <label class="flex items-center">
                <input type="checkbox" class="form-checkbox" v-model="createRoomForm.ambiant_music"/>
                <span class="ml-2 text-sm text-black">Ambiance Music</span>
              </label>
              <label class="flex items-center">
                <input type="checkbox" class="form-checkbox" v-model="createRoomForm.sale_for_profit"/>
                <span class="ml-2 text-sm text-black">Sale for Profit</span>
              </label>
              <label class="flex items-center">
                <input type="checkbox" class="form-checkbox" v-model="createRoomForm.fundraiser"/>
                <span class="ml-2 text-sm text-black">Fundraiser</span>
              </label>
              <jet-input-error :message="createRoomForm.error('restrictions')" class="mt-2"/>
            </div>
            <br>

            <div class="col-span-6 sm:col-span-3">
              <jet-label for="furniture" value="Furniture"/>
              <br>
              <label class="flex items-center text-sm">Sofa(s)</label>
              <jet-input v-model.number="createRoomForm.sofas" type="number" min="0"/>

              <label class="flex items-center text-sm">Coffee Table(s)</label>
              <jet-input v-model.number="createRoomForm.coffee_tables" type="number" min="0"/>

              <label class="flex items-center text-sm">Table(s)</label>
              <jet-input v-model.number="createRoomForm.tables" type="number" min="0"/>

              <label class="flex items-center text-sm">Chair(s)</label>
              <jet-input v-model.number="createRoomForm.chairs" type="number" min="0"/>

              <jet-input-error :message="createRoomForm.error('furniture')" class="mt-2"/>
            </div>
          </div>

          <div
            v-on:click="toggle('');"
            class="col-span-6 sm:col-span-6 w-4/5"
          >
            <br>
            <h4 id="availabilities">Availabilities</h4>
            <hr>
          </div>

          <div
            v-show="showAvailabilities"
            v-for="(value, key) in createRoomForm.availabilities"
            class="col-span-6 sm:col-span-3 w-4/5"
          ><br>
            <h3>{{ key }}</h3>

            <jet-label value="Opening hours"/>
            <jet-input
              type="time"
              class="mt-1 block w-4/5"
              v-model="value.opening_hours"
            />
            <jet-input-error
              :message="createRoomForm.error('availabilities.' + key + '.opening_hours')"
              class="mt-2"
            />

            <jet-label value="Closing hours"/>
            <jet-input
              type="time"
              class="mt-1 block w-4/5"
              v-model="value.closing_hours"
            />
            <jet-input-error
              :message="createRoomForm.error('availabilities.' + key + '.closing_hours')"
              class="mt-2"
            />
          </div>

          <div
            v-show="showAvailabilities"
            class="col-span-6 sm:col-span-3"
          ><br>
            <h3>Bookable Window</h3><br>
            <jet-label for="min_days_advance" value="Minimum Days Before Booking"/>
            <jet-input id="min_days_advance" type="number" min="0" class="mt-1 block w-full"
                       v-model="createRoomForm.min_days_advance"/>
            <jet-input-error :message="createRoomForm.error('min_days_advance')" class="mt-2"/>
            <jet-label for="max_days_advance" value="Maximum Days Before Booking"/>
            <jet-input id="max_days_advance" type="number" min="0" class="mt-1 block w-full"
                       v-model="createRoomForm.max_days_advance"/>
            <jet-input-error :message="createRoomForm.error('max_days_advance')" class="mt-2"/>
          </div>
        </div>
      </template>

      <template #actions>
        <jet-action-message :on="createRoomForm.recentlySuccessful" class="mr-3">
          Created.
        </jet-action-message>

        <jet-button id="create" :class="{ 'opacity-25': createRoomForm.processing }" :disabled="createRoomForm.processing">
          Create
        </jet-button>
      </template>
    </form-section>
  </div>
</template>

<script>
import JetButton from "@src/Jetstream/Button";
import JetInput from "@src/Jetstream/Input";
import JetActionMessage from "@src/Jetstream/ActionMessage";
import JetFormSection from "@src/Jetstream/FormSection";
import FormSection from '@src/Components/FormSection'
import JetInputError from "@src/Jetstream/InputError";
import JetLabel from "@src/Jetstream/Label";

export default {
  components: {
    JetButton,
    JetInput,
    JetFormSection,
    FormSection,
    JetActionMessage,
    JetInputError,
    JetLabel,
  },

  props: {
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
      createRoomForm: this.$inertia.form(
        {
          name: "",
          number: "",
          floor: "",
          building: "",
          projector: false,
          television: false,
          computer: false,
          whiteboard: false,
          capacity_standing: "",
          capacity_sitting: "",
          food: false,
          alcohol: false,
          a_v_permitted: false,
          sofas: "",
          coffee_tables: "",
          tables: "",
          chairs: "",
          ambiant_music: false,
          sale_for_profit: false,
          fundraiser: false,
          status: "",
          room_type: "",
          availabilities: {
            Monday: {
              opening_hours: "",
              closing_hours: "",
            },
            Tuesday: {
              opening_hours: "",
              closing_hours: "",
            },
            Wednesday: {
              opening_hours: "",
              closing_hours: "",
            },
            Thursday: {
              opening_hours: "",
              closing_hours: "",
            },
            Friday: {
              opening_hours: "",
              closing_hours: "",
            },
            Saturday: {
              opening_hours: "",
              closing_hours: "",
            },
            Sunday: {
              opening_hours: "",
              closing_hours: "",
            },
          },
          min_days_advance: "",
          max_days_advance: "",
        },
        {
          bag: "createRoom",
          resetOnSuccess: true,
        }
      ),
      showAttributes: false,
      showAvailabilities: false,
    };
  },
  methods: {
    createRoom() {
      this.createRoomForm.post("/admin/rooms", {
        preserveScroll: true,
      });
    },
    toggle(e) {
      if (e === 'attributes') {
        this.showAttributes = !this.showAttributes;
      } else {
        this.showAvailabilities = !this.showAvailabilities;
      }
    },
  },
};
</script>
