<template>
  <div>

    <!-- Booking -->
    <div class="bg-white shadow sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          Booking Overview
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
          General booking request information and status
        </p>
      </div>
      <div class="border-t border-gray-200">
        <dl>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Request Submitted At
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ booking.created_diff }}
              <span class="text-xs text-gray-500">
                ( {{ booking.created_at }} )
              </span>
            </dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Request Last Updated At
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ booking.updated_diff }}
              <span class="text-xs text-gray-500">
                ( {{ booking.updated_at }} )
              </span>
            </dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Request Status
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              <span class="rounded-full py-2 px-6 text-white text-center font-bold"
                    :class="{ 'bg-green-700': (booking.status === 'Approved'),
                              'bg-red-700': (booking.status === 'Refused'),
                              'bg-yellow-400': (booking.status === 'Review'),
                              'bg-indigo-500': (booking.status !== 'Approved' && booking.status !== 'Refused' && booking.status !== 'Review' ),
              }">
                {{ booking.status }}
              </span>
            </dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Request Reviewers
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              <booking-reviewers-field v-if="$page"
                                       :reviewers="booking.reviewers" :booking_request_id="booking.id"
                                       :editing="(booking.status === 'Review' && this.$page.user.can.includes('bookings.approve')) || false" />
            </dd>
          </div>
        </dl>
      </div>
    </div>

    <jet-section-border />

    <!-- Contact -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          Contact Information
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
          Requester and onsite contact details (if applicable)
        </p>
      </div>
      <div class="border-t border-gray-200">
        <dl>
          <!-- CSU Organization -->
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              CSU Organization
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ booking.requester.organization }}
            </dd>
          </div>

          <!-- Onsite Contact -->
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Booking Officer
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                  <div class="mr-4 flex-shrink-0">
                    Name
                  </div>
                  <div class="w-0 flex-1 flex items-center">
                      <span class="ml-2 flex-1 w-0 truncate">
                        {{ booking.requester.name }}
                      </span>
                  </div>
                </li>
                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                  <div class="mr-4 flex-shrink-0">
                    Email
                  </div>
                  <div class="w-0 flex-1 flex items-center">
                      <span class="ml-2 flex-1 w-0 truncate">
                        {{ booking.requester.email }}
                      </span>
                  </div>
                </li>
                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                  <div class="mr-4 flex-shrink-0">
                    Phone
                  </div>
                  <div class="w-0 flex-1 flex items-center">
                      <span class="ml-2 flex-1 w-0 truncate">
                        {{ booking.requester.phone }}
                      </span>
                  </div>
                </li>
              </ul>
            </dd>
          </div>

          <!-- Onsite Contact -->
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Onsite Contact
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                <li v-if="booking.onsite_contact.name" class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                  <div class="mr-4 flex-shrink-0">
                    Name
                  </div>
                  <div class="w-0 flex-1 flex items-center">
                      <span class="ml-2 flex-1 w-0 truncate">
                        {{ booking.onsite_contact.name }}
                      </span>
                  </div>
                </li>
                <li v-if="booking.onsite_contact.email" class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                  <div class="mr-4 flex-shrink-0">
                    Email
                  </div>
                  <div class="w-0 flex-1 flex items-center">
                      <span class="ml-2 flex-1 w-0 truncate">
                        {{ booking.onsite_contact.email }}
                      </span>
                  </div>
                </li>
                <li v-if="booking.onsite_contact.phone" class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                  <div class="mr-4 flex-shrink-0">
                    Phone
                  </div>
                  <div class="w-0 flex-1 flex items-center">
                      <span class="ml-2 flex-1 w-0 truncate">
                        {{ booking.onsite_contact.phone }}
                      </span>
                  </div>
                </li>
              </ul>
            </dd>
          </div>
        </dl>
      </div>
    </div>

    <jet-section-border />

    <!-- Event -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          Event Information
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
          Event description and details
        </p>
      </div>
      <div class="border-t border-gray-200">
        <dl>

          <!-- Required event fields -->
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Event Title
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ booking.event.title }}
            </dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Event Type
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ booking.event.type }}
            </dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Event Description
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ booking.event.description }}
            </dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Guest Speakers
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ booking.event.guest_speakers }}
            </dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Number of Attendees
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ booking.event.attendees }}
            </dd>
          </div>

          <!-- optional event fields -->
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Actual Event Start Time
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ booking.event.start_time || 'N/A' }}
            </dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Actual Event End Time
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ booking.event.end_time || 'N/A' }}
            </dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Admission Fee
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ booking.event.fee || 'N/A' }}
            </dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Onsite Music / Sound
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ booking.event.music || 'N/A' }}
            </dd>
          </div>

          <!-- Food Details -->
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Food Details
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              <app-warning v-if="!booking.room.attributes.food" class="mb-3">
                Food is prohibited at the requested location.
              </app-warning>

              <ul v-if="booking.event.food" class="border border-gray-200 rounded-md divide-y divide-gray-200">
                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                  <div class="mr-4 flex-shrink-0">
                    Low Risk Food:
                  </div>
                  <div class="w-0 flex-1 flex items-center">
                      <span class="ml-2 flex-1 w-0 truncate">
                        {{ booking.event.food.low_risk || false }}
                      </span>
                  </div>
                </li>
                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                  <div class="mr-4 flex-shrink-0">
                    High Risk Food:
                  </div>
                  <div class="w-0 flex-1 flex items-center">
                      <span class="ml-2 flex-1 w-0 truncate">
                        {{ booking.event.food.high_risk || false }}
                      </span>
                  </div>
                </li>
                <li v-if="booking.event.food.high_risk" class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                  <div class="mr-4 flex-shrink-0">
                    Catered By:
                  </div>
                  <div class="w-0 flex-1 flex items-center">
                    <div class="ml-2 flex-1 w-0 truncate">
                        <span v-if="booking.event.food.self_catered">
                          Self Catered, verify the food waver!
                        </span>
                      <span v-else>
                          {{ booking.event.food.caterer || 'Not Specified' }}
                        </span>
                    </div>
                  </div>
                </li>
              </ul>
              <span v-else>
                  No food details have been declared for this booking request
                </span>
            </dd>
          </div>

          <!-- Alcohol -->
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Alcohol Permit Required?
            </dt>
            <dd class="flex space-x-10 mt-1 text-sm text-gray-900sm:mt-0 sm:col-span-2">
                <span>
                  {{ booking.event.alcohol ? 'Yes, verify the alcohol waiver' : 'No' }}
                </span>
              <app-warning v-if="!booking.room.attributes.alcohol">
                Alcohol is prohibited at the requested location.
              </app-warning>
            </dd>
          </div>

          <!-- Additional Info -->
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Additional Considerations
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">

              <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                <li v-if="booking.room.room_type === 'Lounge'" class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                  <div class="mr-4 flex-shrink-0">
                    Minors Participating?
                  </div>
                  <div class="w-0 flex-1 flex items-center">
                      <span class="ml-2 flex-1 w-0 truncate">
                        {{ booking.event.children ? 'Yes, verify the parental waiver!' : 'No' }}
                      </span>
                  </div>
                </li>
                <li v-if="booking.room.room_type === 'Lounge'" class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                  <div class="mr-4 flex-shrink-0">
                    Appliances Needed?
                  </div>
                  <div class="w-0 flex-1 flex items-center">
                      <span class="ml-2 flex-1 w-0 truncate">
                        {{ booking.event.appliances ? 'Yes, verify the fire prevention form!' : 'No' }}
                      </span>
                  </div>
                </li>
                <li v-if="booking.room.room_type === 'Lounge'" class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                  <div class="mr-4 flex-shrink-0">
                    A/V Equipment Needed?
                  </div>
                  <div class="w-0 flex-1 flex items-center">
                      <span class="ml-2 flex-1 w-0 truncate">
                        {{ booking.event.av ? 'Yes' : 'No' }}
                      </span>
                  </div>
                </li>
                <li v-if="booking.room.room_type === 'Lounge'" class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                  <div class="mr-4 flex-shrink-0">
                    Furniture Needed?
                  </div>
                  <div class="w-0 flex-1 flex items-center">
                      <span class="ml-2 flex-1 w-0 truncate">
                        {{ booking.event.furniture ? 'Yes' : 'No' }}
                      </span>
                  </div>
                </li>
                <li v-if="booking.room.room_type === 'Mezzanine'" class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                  <div class="mr-4 flex-shrink-0">
                    Is this a bake sale?
                  </div>
                  <div class="w-0 flex-1 flex items-center">
                      <span class="ml-2 flex-1 w-0 truncate">
                        {{ booking.event.bake_sale ? 'Yes' : 'No' }}
                      </span>
                  </div>
                </li>
                <li v-if="booking.room.room_type === 'Conference'" class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                  <div class="mr-4 flex-shrink-0">
                    Is this an internal meeting?
                  </div>
                  <div class="w-0 flex-1 flex items-center">
                      <span class="ml-2 flex-1 w-0 truncate">
                        {{ booking.event.internal_meeting ? 'Yes' : 'No' }}
                      </span>
                  </div>
                </li>
              </ul>
            </dd>
          </div>

          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Additional Notes
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ booking.notes }}
            </dd>
          </div>

        </dl>
      </div>
    </div>

    <jet-section-border />

    <!-- Reservation -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          Reservation Information
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
          Room and reservation details related to this booking request
        </p>
      </div>
      <div class="border-t border-gray-200">
        <dl>
          <!-- Room -->
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Room Location (Building / Floor / Number)
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ booking.room.building }} / {{ booking.room.floor }} / {{ booking.room.number }}
            </dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Room Name
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ booking.room.name }}
            </dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Room Type
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ booking.room.room_type }}
            </dd>
          </div>

          <!-- Reservations -->
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Reservations List
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                <span v-if="booking.reservations.length === 0 ">
                  This request does not have any reservation dates associated to it.
                </span>
              <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                <li v-if="booking.reservations.length > 0 " v-for="reservation in booking.reservations" class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                  <div class="w-0 flex-1 flex items-center">

                    <div class="flex space-x-2 text-gray-400">
                      <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <span>Start:</span>
                    </div>
                    <span class="ml-2 flex-1 w-0">
                      {{ reservation.start_time }}
                    </span>

                    <div class="flex space-x-2 text-gray-400">
                      <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <span>End:</span>
                    </div>
                    <span class="ml-2 flex-1 w-0">
                      {{ reservation.end_time }}
                    </span>
                  </div>
                </li>
              </ul>
            </dd>
          </div>

          <!-- Files -->
          <div v-if="booking.reference.path" class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Attachments
            </dt>
            <dd class="flex space-x-2 mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              <!-- Heroicon name: solid/paper-clip -->
              <a :href="'/bookings/download/'+ booking.reference.path" class="font-medium text-indigo-600 hover:text-indigo-500">
                Download All Files
              </a>
              <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
              </svg>
            </dd>
          </div>
          <div v-else class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Attachments
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                <span v-if="booking.reference.length === 0 ">
                  No files were attached to this booking request
                </span>
              <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                <li v-if="booking.reference.length > 0 " v-for="file in booking.reference" class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                  <div class="w-0 flex-1 flex items-center">
                    <!-- Heroicon name: solid/paper-clip -->
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-2 flex-1 w-0 truncate">
                        {{ file.name }}
                      </span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <a :href="file.link" class="font-medium text-indigo-600 hover:text-indigo-500">
                      Open
                    </a>
                  </div>
                </li>
              </ul>
            </dd>
          </div>
        </dl>
      </div>
    </div>

    <jet-section-border />

    <!-- history details go here -->
    <div class="bg-grey shadow overflow-hidden sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          Booking History
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
          User comments and work log
        </p>
      </div>
      <div class="border-t border-gray-200">

        <!-- comments thread -->
        <div v-for="comment in booking.comments" class="px-0 mx-auto sm:px-4">
          <div class="flex-col w-full py-4 mx-auto bg-white sm:px-4 sm:py-4 md:px-4">
            <div class="flex flex-row">
              <span class="relative">
                <img class="object-cover w-12 h-12 border-2 border-gray-300 rounded-full"
                     :alt="comment.user.id" :src="comment.user.profile_photo_url">
                <svg v-if="!booking.system" class="absolute right-0 bottom-1 p-1 w-6 h-6 bg-gray-200 rounded-full"
                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
              </span>
              <div class="flex-col mt-1">
                <div class="flex items-center flex-1 px-4 font-bold leading-tight">
                  {{ comment.user.name }}
                  <span class="ml-2 text-xs font-normal text-gray-500">{{ comment.created_diff }}</span>
                </div>
                <div v-html="comment.body" class="flex-1 px-2 ml-2 text-sm font-medium leading-loose text-gray-600"></div>

              </div>
            </div>
          </div>
        </div>

        <!-- post comment -->
        <div class="flex px-0 mx-auto my-4 sm:px-8 space-x-4">
          <img v-if="$page" class="object-cover w-12 h-12 border-2 border-gray-300 rounded-full"
               :alt="$page.user.id" :src="$page.user.profile_photo_url">


          <div class="flex flex-col space-y-4 w-full">
            <div class="flex space-x-2 w-full">
              <RichTextEditor
                class="w-full"
                :editable="true"
                :incomingText="''"
                :onSave="saveComment"
              />
            </div>
            <jet-input-error :message="form.error('comment')" class="mt-2"/>
          </div>
        </div>

      </div>
    </div>

  </div>
</template>

<script>
import JetSectionBorder from '@src/Jetstream/SectionBorder'
import JetActionSection from '@src/Jetstream/ActionSection'
import JetFormSection from '@src/Jetstream/FormSection';
import JetButton from '@src/Jetstream/Button';
import JetLabel from '@src/Jetstream/Label';
import JetInput from '@src/Jetstream/Input';
import JetInputError from '@src/Jetstream/InputError';
import AppWarning from '@src/Components/Form/Warning';
import BookingReviewersField from "@src/Components/BookingReviewersField";
import RichTextEditor from "@src/Components/RichTextEditor";

export default {
  components: {
    BookingReviewersField,
    AppWarning,
    JetSectionBorder,
    JetActionSection,
    JetFormSection,
    JetButton,
    JetLabel,
    JetInput,
    JetInputError,
    RichTextEditor,
  },

  props: {
    booking: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      form: this.$inertia.form({
        comment: '',
      }, {
        bag: 'createComment',
      })
    }
  },

  methods: {
    submitComment() {
      this.form.post('/bookings/' + this.booking.id + '/comment', {
        preserveState: true,
      })
    },
    saveComment(comment) {
      this.form.comment = comment;
      this.submitComment();
    }

  },

}
</script>
