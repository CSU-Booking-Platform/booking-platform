<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Review Bookings
      </h2>
    </template>


    <div v-if="bookings.length > 0">
      <jet-section-border/>

      <div class="mx-10 mt-5 md:mt-0 md:col-span-2">
        <div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg">
          <div class="mt-10 sm:mt-0">
            <div class="space-y-6">
              <div v-for="booking in bookings" class="flex items-center justify-between">
                <div class="flex items-center">
                  <div class="text-md mr-3">
                    {{ booking.id }}
                  </div>
                  <div class="text-sm text-gray-400">
                    {{ booking.requester.name }}
                  </div>
                  <div class="text-sm text-gray-400">
                    {{ booking.status }}
                  </div>
                </div>

                <div class="flex space-x-4 items-center">
                  <div class="text-sm text-gray-400">
                    Created On {{ booking.created_diff }}
                  </div>
                  <div class="text-sm text-gray-400">
                    Last Updated On {{ booking.updated_diff }}
                  </div>
                  <div class="text-sm text-gray-400">
                    Status: {{ booking.status }}
                  </div>

                  <jet-responsive-nav-link :href="'/bookings/'+booking.id+'/review'"
                                class="cursor-pointer ml-3 text-sm text-blue-800 focus:outline-none">
                    Open Details
                  </jet-responsive-nav-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    </app-layout>
</template>

<script>
import JetResponsiveNavLink from "@src/Jetstream/ResponsiveNavLink"
import JetSectionBorder from '@src/Jetstream/SectionBorder'
import XSection from "@src/Components/XSection"
import AppLayout from '@src/Layouts/AppLayout'

export default {
  components: {
    JetResponsiveNavLink,
    JetSectionBorder,
    AppLayout,
    XSection,
  },
  props: {
    bookings: {
      type: Array,
      default: function () {
        return []
      },
    }
  },
}
</script>
