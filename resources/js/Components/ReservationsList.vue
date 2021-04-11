<template>
  <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
    <li v-if="reservations.length > 0 " v-for="reservation in reservations" class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
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

</template>

<script>
export default {
  props: {
    reservations: {
      type: Array,
      default: function () {
        return [];
      }
    }
  }
}
</script>
