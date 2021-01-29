<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Submit A Booking Request
            </h2>
        </template>

        <form class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8" @submit.prevent="submitBooking">
            <!-- General Info -->
            <jet-action-section>
                <template #title>
                    General Information
                </template>

                <template #description>
                    <p class="py-2">
                        In order to book the 7th floor large lounge or cafeteria, please complete and return the
                        attached form a full <strong>ten (10) business days</strong> prior to your event.
                    </p>

                    <p class="py-2">
                        For an event where alcohol is part of the menu, your space request form along with an
                        Alcohol Waiver and Responsibilities Regarding the Sale/Service of Alcohol on Campus
                        (the “Alcohol Waiver”) must be received at least <strong>twenty (20) business days</strong>
                        prior to your event.
                    </p>
                    <p class="py-2">
                        For an event requiring a Special Events permit from MAPAQ (refer to Sale or Service of Food on
                        Campus Waiver Form for Self-Catered Events, the “Food Waiver”), your application must be
                        received at least <strong>twenty five (25) business days</strong> prior to your event.
                    </p>
                </template>

                <template #content>
                    <div class="grid grid-cols-6 gap-6">
                        <!-- Organization -->
                        <div class="col-span-6">
                            <jet-label value="Organization"/>
                            <h3 class="text-lg font-medium text-gray-900 py-2">
                                {{ $page.user.organization }}
                            </h3>
                        </div>

                        <!-- Booking Officer -->
                        <div class="col-span-6">
                            <jet-label value="Booking Officer"/>
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ $page.user.name }}
                            </h3>
                        </div>

                        <!-- Email -->
                        <div class="col-span-3">
                            <jet-label value="Email"/>
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ $page.user.email }}
                            </h3>
                        </div>

                        <!-- Phone -->
                        <div class="col-span-3">
                            <jet-label value="Phone"/>
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ $page.user.phone }}
                            </h3>
                        </div>

                        <!-- Onsite Contact -->
                        <div class="col-span-6" v-if="typeof form.event.show.contact !== undefined">
                            <app-question v-model="form.event.show.contact">
                                <template #header>
                                    <jet-label value="Onsite Contact is different from Booking Officer?"/>
                                </template>

                                <div class="grid grid-cols-6 gap-6">
                                    <!-- Name -->
                                    <div class="col-span-6">
                                        <jet-label for="onsite_name" value="Name"/>
                                        <jet-input id="onsite_name" type="text" class="mt-1 block w-full"
                                                   v-model="form.onsite_contact.name " autofocus/>
                                        <jet-input-error :message="form.error('onsite_contact.name')" class="mt-2"/>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-span-3">
                                        <jet-label for="onsite_email" value="Email"/>
                                        <jet-input id="onsite_email" type="email" class="mt-1 block w-full"
                                                   v-model="form.onsite_contact.email " autofocus/>
                                        <jet-input-error :message="form.error('onsite_contact.email')" class="mt-2"/>
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-span-3">
                                        <jet-label for="onsite_phone" value="Phone"/>
                                        <jet-input id="onsite_phone" type="phone" class="mt-1 block w-full"
                                                   v-model="form.onsite_contact.phone " autofocus/>
                                        <jet-input-error :message="form.error('onsite_contact.phone')" class="mt-2"/>
                                    </div>
                                </div>

                            </app-question>
                        </div>

                    </div>
                </template>
            </jet-action-section>

            <jet-section-border />

            <jet-action-section>
                <template #title>
                    Event Description
                </template>

                <template #description>
                    <section class="pt-4">
                        <strong>NOTE:</strong>
                        <p class="py-2">
                            A Food Waiver is not required for events where 1. lower risk food (refer to Definitions below)
                            is served/sold, or 2. food is provided by an University approved caterer.
                        </p>
                        <p class="py-2">
                            For an exhibition, indicate the theme and objects of display, along with materials and equipment
                            used to display these objects. Sample pictures are required for exhibit of paintings, drawings
                            and photographs. A layout of the arrangement may be requested. Where applicable, indicate how
                            the exhibition will be secured overnight
                        </p>
                        <p class="py-2">
                            Please re-submit a new space request if the main components of your event are modified.
                        </p>
                    </section>
                    <section class="pt-4">
                        <strong>DEFINITIONS:</strong>
                        <p class="py-2">
                            <strong>Lower Risk Foods</strong> are foods that pathogens have trouble surviving in because
                            they are drier, have a high sugar or salt content, or are more acidic. These foods do not
                            need to be kept hot or cold. Ex: cakes (except cheesecake or cream/dairy filled cakes),
                            fruit pies, tarts, breads, candies, popcorns, chocolates, fudge, and brownies.
                        </p>
                        <p class="py-2">
                            <strong>Higher Risk Foods</strong> are foods that can support the growth of pathogens and/or
                            foods that have been associated with causing food borne illness. These foods must be kept
                            and served hot or cold (ex: cheesecake, pumpkin and meringue pies, frostings or fillings
                            with cream cheese, custards, whipped cream or cheese, meats, samosas, rice, cooked
                            vegetables, tofu, sprouted grains and dairy products).
                        </p>
                    </section>


                </template>

                <template #content>
                    <div class="grid grid-cols-6 gap-6">
                        <!-- Date -->
                        <div class="col-span-3">
                            <jet-label value="Date"/>
                            <div class="flex space-x-2 py-2">
                                <h3 class="text-lg font-medium text-gray-900">
                                    {{ only_date(reservation.start_time) }}
                                </h3>
                                <div v-if="isRecurring">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Reservation Info -->
                        <div class="col-span-3">
                            <jet-label>
                                Hours Of Reservation
                                <span class="text-xs italic">including setup / take down time</span>
                            </jet-label>

                            <div class="flex items-start justify-center py-2 space-x-5">
                                <h3 class="text-lg font-medium text-gray-900">
                                    {{ only_time(reservation.start_time) }}
                                </h3>
                                <span class="block font-medium text-sm text-gray-700"> To </span>
                                <h3 class="text-lg font-medium text-gray-900">
                                    {{ only_time(reservation.end_time) }}
                                </h3>
                            </div>
                        </div>

                        <!-- Actual Event Hours -->
                        <div class="col-span-6">
                            <jet-label value="Actual Time Of Event"/>
                            <div class="flex items-center justify-center py-2 space-x-5">
                                <div class="flex-1">
                                    <jet-label for="event_start" value="Start"/>
                                    <jet-input id="event_start" type="time" class="mt-1 block w-full"
                                               v-model="form.event.start_time" autofocus/>
                                    <jet-input-error :message="form.error('event.start_time')" class="mt-2"/>
                                </div>

                                <span class="mt-4 text-lg font-medium text-gray-900"> To </span>

                                <div class="flex-1">
                                    <jet-label for="event_end" value="Finish"/>
                                    <jet-input id="event_end" type="time" class="mt-1 block w-full"
                                               v-model="form.event.end_time" autofocus/>
                                    <jet-input-error :message="form.error('event.end_time')" class="mt-2"/>
                                </div>
                            </div>
                        </div>

                        <!-- Location Requested -->
                        <div class="col-span-6">
                            <jet-label value="Location Requested"/>
                            <div class="flex space-x-8 py-2">
                                <span class="py-1 font-medium text-sm text-gray-700">Name</span>
                                <span class="text-lg font-medium text-gray-900"> {{ reservation.room.name }}</span>
                                <span class="py-1 font-medium text-sm text-gray-700">Building</span>
                                <span class="text-lg font-medium text-gray-900"> {{ reservation.room.building }}</span>
                                <span class="py-1 font-medium text-sm text-gray-700">Floor</span>
                                <span class="text-lg font-medium text-gray-900"> {{ reservation.room.floor }}</span>
                                <span class="py-1 font-medium text-sm text-gray-700">Number</span>
                                <span class="text-lg font-medium text-gray-900"> {{ reservation.room.number }}</span>
                            </div>
                        </div>

                        <!-- Title Of Event -->
                        <div class="col-span-6">
                            <jet-label for="event_title" value="Title Of Event"/>
                            <jet-input id="event_title" type="text" class="mt-1 block w-full"
                                       v-model="form.event.title" autofocus/>
                            <jet-input-error :message="form.error('event.title')" class="mt-2"/>
                        </div>

                        <!-- Type Of Event -->
                        <div class="col-span-6">
                            <jet-label for="event_type" value="Type Of Event"/>
                            <jet-input id="event_type" type="text" class="mt-1 block w-full"
                                       v-model="form.event.type" autofocus/>
                            <jet-input-error :message="form.error('event.type')" class="mt-2"/>
                        </div>

                        <!-- Description Of Event -->
                        <div class="col-span-6">
                            <jet-label for="event_description" value="Description"/>
                            <app-textbox id="event_description" class="mt-1 block w-full"
                                         v-model="form.event.description" autofocus/>
                            <jet-input-error :message="form.error('event.event_description')" class="mt-2"/>
                        </div>

                        <!-- Guest Speakers -->
                        <div class="col-span-6">
                            <jet-label for="guest_speakers" value="Guest Speakers"/>
                            <app-textbox id="guest_speakers" class="mt-1 block w-full"
                                         v-model="form.event.guest_speakers" autofocus/>
                            <jet-input-error :message="form.error('event.guest_speakers')" class="mt-2"/>
                        </div>

                        <!-- Number of Attendees -->
                        <div class="col-span-6">
                            <jet-label for="attendees" value="Number of Attendees"/>
                            <jet-input id="attendees" type="number" class="mt-1 block w-full"
                                       v-model="form.event.attendees" autofocus/>
                            <jet-input-error :message="form.error('event.attendees')" class="mt-2"/>
                        </div>

                        <!-- Fee -->
                        <div class="col-span-6" v-if="typeof form.event.show.fee !== undefined">
                            <app-question v-model="form.event.show.fee" @change="toggleNullableForms">
                                <template #header>
                                    <jet-label value="Will there be a registration/admission fee or suggested donation?"/>
                                </template>
                                <jet-label for="fee" value="Please specify"/>
                                <jet-input id="fee" type="text" class="mt-1 block w-full"
                                           v-model="form.event.fee" autofocus/>
                                <jet-input-error :message="form.error('event.fee')" class="mt-2"/>
                            </app-question>
                        </div>

                        <!-- Music -->
                      <div class="col-span-6" v-if="typeof form.event.show.music !== undefined">
                          <app-question v-model="form.event.show.music" @change="toggleNullableForms">
                              <template #header>
                                  <jet-label value="Will there be music or sound on site?"/>
                              </template>
                              <jet-label for="music" value="Please specify"/>
                              <jet-input id="music" type="text" class="mt-1 block w-full"
                                     v-model="form.event.music" autofocus/>
                              <jet-input-error :message="form.error('event.music')" class="mt-2"/>
                          </app-question>
                      </div>
                            

                        <!-- Food -->
                      <div class="col-span-6">
                        <app-question v-if="reservation.room.attributes.food" :checked="form.event.food.high_risk == true || form.event.food.low_risk == true">
                          <template #header>
                            <jet-label value="Will there be any food?"/>
                          </template>

                          <div class="ml-4">
                            <!-- Food Type -->
                            <div class="flex space-x-2 py-2">
                              <jet-label value="High Risk Food"/>
                              <jet-checkbox v-model="form.event.food.high_risk"/>

                              <!-- Low Risk Food -->
                              <jet-label class="pl-8" value="Lower Risk Food"/>
                              <jet-checkbox v-model="form.event.food.low_risk"/>
                            </div>

                            <!-- High Risk Food -->
                            <div v-if="form.event.food.high_risk">
                              <div class="flex space-x-2 py-2">
                                <jet-label value="Will the event be self catered?"/>
                                <jet-checkbox v-model="form.event.food.self_catered"/>
                                <span v-if="form.event.food.self_catered" class="text-md text-black">Yes</span>
                                <span v-else class="text-md text-black">No</span>
                              </div>

                              <app-warning v-if="form.event.food.self_catered" class="text-indigo-600">
                                <template #icon>
                                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                  </svg>
                                </template>
                                Please remember to attach a filled out "Food Waiver" to the document section.
                              </app-warning>
                              <div v-else>
                                <jet-label for="caterer" value="Specify the catering company"/>
                                <jet-input id="caterer" type="text" class="mt-1 block w-full"
                                           v-model="form.event.food.caterer" autofocus/>
                                <jet-input-error :message="form.error('event.food.caterer')" class="mt-2"/>
                              </div>
                            </div>
                          </div>

                        </app-question>
                        <app-warning v-else>
                          Please be aware that food is prohibited at the requested location.
                        </app-warning>
                      </div>

                        <!-- Alcohol -->
                        <div class="col-span-6">
                            <div v-if="booking.room.attributes.alcohol">
                                <div class="flex space-x-2">
                                    <jet-label for="alcohol" value="Is alcohol involved?"/>
                                    <jet-checkbox id="alcohol" v-model="form.event.alcohol"/>
                                    <span v-if="form.event.alcohol" class="text-md text-black">Yes</span>
                                    <jet-input-error :message="form.error('event.alcohol')" class="mt-2"/>
                                </div>

                                <app-warning v-if="form.event.alcohol" class="py-2 text-indigo-600">
                                    <template #icon>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                    </template>
                                    Please remember to attach a filled out "Alcohol Waiver" to the document section.
                                </app-warning>
                            </div>

                            <app-warning v-else>
                                Please be aware that alcohol is prohibited at the requested location.
                            </app-warning>
                        </div>


                        <!-- Lounge room dependent fillables -->

                        <!-- minor children involved -->
                     <div class="col-span-6" v-if="booking.room.room_type == 'Lounge'">
                                <div class="flex-column">
                                <div class="flex items-center space-x-2">
                                <jet-label value="Will there be appliances involved?"/>
                               <jet-checkbox v-model="form.event.children"/>
                                <span v-if="form.event.children" class="text-md text-black">Yes</span>
                                </div>
                                <app-warning class="py-2 text-indigo-600" v-if="form.event.children">
                                    <template #icon>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                    </template>
                                    Please remember to attach a filled out "Parental Waiver" to the document section.
                                </app-warning>
                                </div>
                            </div>
                        </div>

                        <!-- appliances involved -->
                        <div class="col-span-6" v-if="booking.room.room_type == 'Lounge'">
                                <div class="flex-column">
                                <div class="flex items-center space-x-2">
                                <jet-label value="Will there be appliances involved?"/>
                               <jet-checkbox v-model="form.event.appliances"/>
                                <span v-if="form.event.appliances" class="text-md text-black">Yes</span>
                                </div>
                                <app-warning class="py-2 text-indigo-600" v-if="form.event.appliances">
                                    <template #icon>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                    </template>
                                    If you are not a CSU club please fill out the "Fire Prevention Form" to the document section
                                </app-warning>
                            </div>
                        </div>

                        <!-- A/V needed -->
                      <div class="col-span-6" v-if="booking.room.room_type == 'Lounge'">
                                <div class="flex-column">
                                <div class="flex items-center space-x-2">
                                <jet-label value="Is A/V needed?"/>
                               <jet-checkbox v-model="form.event.av"/>
                                <span v-if="form.event.av" class="text-md text-black">Yes</span>
                                </div>
                            </div>
                        </div>

                        <!-- furniture -->
                       <div class="col-span-6" v-if="booking.room.room_type == 'Lounge'">
                                <div class="flex-column">
                                <div class="flex items-center space-x-2">
                                    <jet-label value="Is Furniture needed?"/>
                                    <jet-checkbox v-model="form.event.furniture"/>
                                    <span v-if="form.event.furniture" class="text-md text-black">Yes</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mezzanine booking.room dependent fillables -->

                        <!-- bake sale -->
                        <div class="col-span-6" v-if="booking.room.room_type == 'Mezzanine'">
                                <div class="flex-column">
                                <div class="flex items-center space-x-2">
                                    <jet-label value="Is the reservation for a bake sale?"/>
                                    <jet-checkbox v-model="form.event.bake_sale"/>
                                    <span v-if="form.event.bake_sale" class="text-md text-black">Yes</span>
                                </div>
                                <app-warning class="py-2 text-indigo-600" v-if="form.event.bake_sale">
                                    <template #icon>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                    </template>
                                    Please remember to attach a filled out "Bake Sale Form" to the document section
                                </app-warning>
                            </div>
                        </div>

                        <!-- Conference room dependent fillables -->

                        <!-- internal meeting -->
                       <div class="col-span-6" v-if="booking.room.room_type == 'Conference'">
                                <div class="flex-column">
                                <div class="flex items-center space-x-2">
                                    <jet-label value="Is the reservation for a internal meeting?"/>
                                    <jet-checkbox v-model="form.event.internal_meeting"/>
                                    <span v-if="form.event.internal_meeting" class="text-md text-black">Yes</span>
                                </div>
                            </div>
                        </div>

                        <!-- Upload File Storage -->
                        <div class="col-span-6">
                            <jet-label for="files" value="Document Storage"/>
                            <app-sortable-upload id="files" accept="application/pdf, application/msword"
                                                 @change="uploadedFiles($event)"/>
                            <jet-input-error :message="form.error('files')" class="mt-2"/>
                        </div>
                        
                        <!-- Additional Info -->
                        <div class="col-span-6">
                            <jet-label for="notes" value="Additional Information (Optional)"/>
                            <app-textbox id="notes" class="mt-1 block w-full"
                                         placeholder="Please share any additional relevant information for processing your request"
                                         v-model="form.notes" autofocus/>
                            <jet-input-error :message="form.error('notes')" class="mt-2"/>
                        </div>

                    </div>
                </template>
            </jet-action-section>



            <!-- Terms & Conditions -->
            <div class="flex items-center justify-end py-3">
                <app-question v-model="accept_terms">
                    <template #after>
                        <jet-label>
                            I have read and agree to the CSU’s
                            <a class="font-bold hover:text-indigo-600 hover:text-underline"
                               href="https://www.csu.qc.ca/services/bookings/booking-terms-and-conditions/">
                                “Booking Request Terms and Conditions”
                            </a>
                        </jet-label>
                    </template>
                </app-question>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end py-3 text-right">
                <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                    Submitted.
                </jet-action-message>

                <app-warning v-if="form.hasErrors()" class="pr-4">
                    This booking cannot be submitted.
                    <strong v-if="form.error('availabilities')">
                        The room is no longer available.
                    </strong>
                </app-warning>

                <jet-button :class="{ 'opacity-25': (form.processing || !accept_terms) }" :disabled="form.processing || !accept_terms">
                    Submit
                </jet-button>
            </div>
        </form>
    </app-layout>
</template>

<script>
import AppLayout from '@src/Layouts/AppLayout'
import JetSectionBorder from '@src/Jetstream/SectionBorder'
import JetActionMessage from '@src/Jetstream/ActionMessage';
import JetActionSection from '@src/Jetstream/ActionSection'
import JetFormSection from '@src/Jetstream/FormSection';
import JetSecondaryButton from '@src/Jetstream/SecondaryButton'
import JetDangerButton from '@src/Jetstream/DangerButton'
import JetButton from '@src/Jetstream/Button';
import JetCheckbox from '@src/Jetstream/Checkbox';
import JetLabel from '@src/Jetstream/Label';
import JetInput from '@src/Jetstream/Input';
import JetInputError from '@src/Jetstream/InputError';
import AppWarning from '@src/Components/Form/Warning';
import AppSelect from '@src/Components/Form/Select';
import AppTextbox from '@src/Components/Form/Textbox';
import AppQuestion from '@src/Components/Form/Question';
import AppSortableUpload from '@src/Components/Form/SortableUpload';
import moment from "moment";

export default {
    components: {
        AppLayout,
        AppWarning,
        AppTextbox,
        AppSelect,
        AppQuestion,
        AppSortableUpload,
        JetSectionBorder,
        JetActionMessage,
        JetActionSection,
        JetFormSection,
        JetSecondaryButton,
        JetDangerButton,
        JetButton,
        JetCheckbox,
        JetLabel,
        JetInput,
        JetInputError,
    },

    props: {
        booking: {
            type: Object,
            required: true,
        },      
    },

    data() {
        return {
            accept_terms: false,
            form: this.$inertia.form({
                '_method': 'PUT',
                onsite_contact: {},
                event: {
                    food: {
                        low_risk: false,
                        high_risk: false,
                    },
                    alcohol: false,
                    show: {
                        contact: false,
                        fee: false,
                        music: false,
                    },
                },
                notes: '',
                files: [],
                reservations: [],
            }, {
                bag: 'updateBookingRequest',
            })
        }

    },

    methods: {
        submitBooking() {
            this.form.put('/bookings/' + this.booking.id, {
                preserveScroll: true,
            }).then(response => {
                this.form.processing = false;
            })
        },
        uploadedFiles(files) {
            this.form.files = files;
        },

        updateForm(booking) {
            this.form.onsite_contact = (booking.onsite_contact.length === 0) ? {} : booking.onsite_contact;
            this.form.event = booking.event;
            this.form.notes = booking.notes ?? null;
            //this.form.files= booking?.files;
        },
        toggleNullableForms() {
            if(this.form.event.show?.contact === false) {
                delete this.form.onsite_contact.name;
                delete this.form.onsite_contact.phone;
                delete this.form.onsite_contact.email;
            }
            if(this.form.event.show?.fee === false) {
                delete this.form.event.fee;
            }
            if(this.form.event.show?.music === false) {
                delete this.form.event.music;
            }
        },
        only_date(date) {
            return moment(date).format("dddd, Do MMMM YYYY");
        },
        only_time(date) {
            return moment(date).format("LT");
        },
    },

    mounted() {
        this.updateForm(this.booking)
    },


    computed: {
        reservation() {
            return this.booking.reservations[0];
        },
        isRecurring() {
            return Object.keys(this.booking.reservations).length > 1;
        },
        minStart() {
            return moment(this.booking.reservation?.start_time).format("HH:mm");
        },
        maxEnd() {
            return moment(this.booking.reservation?.end_time).format("HH:mm");
        },
    },

    watch: {
        booking(booking) {
            this.updateForm(this.booking)
        }
    }
}
</script>
