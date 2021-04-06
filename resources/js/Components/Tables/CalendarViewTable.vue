<template>
  <div class="table-container pt-4">
    <div class="table-filter-container mb-12 flex flex-row">
      <div class="mx-6 m-auto">
        <h3 class="font-black">Current date selected:</h3>
      </div>
      <div class="mr-12">
        <jet-input
          id="date_selected"
          type="date"
          class="mt-1 block w-full"
          v-model="dateSelected"
          autofocus
        />
      </div>
      <!--vertical line-->
      <div class="border mx-16">
      </div>
      <div class="mx-6 m-auto">
        <h3 class="font-black">Hourly Range:</h3>
      </div>
      <div class="mx-6 m-auto mt-4 ">
        <button v-if="leftHourDelimiter >= 1" @click="bumpCalendar('left')" class="h-4 w-4" dusk="hours-left">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
          </svg>
        </button>
      </div>
      <div class="mx-6 m-auto">
        {{dailyHours[leftHourDelimiter]}}
      </div>
      <div class="mx-6 m-auto">
        -
      </div>
      <div class="mx-6 m-auto">
        {{dailyHours[rightHourDelimiter]}}
      </div>
      <div  class="mx-6 m-auto mt-4">
        <button v-if="rightHourDelimiter <= 23" @click="bumpCalendar('right')" class="h-4 w-4" dusk="hours-right">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </button>
      </div>


    </div>

    <table class="table-auto responsive-calendar">
      <caption></caption>
      <thead>
      <tr>
        <th id="room-name" class="font-bold w-1/8">Room Name</th>
        <th id="room-delimiters" class="text-center w-1/16" v-for="hour in dailyHours.slice(leftHourDelimiter, rightHourDelimiter)">{{hour}}</th>
      </tr>
      </thead>
      <tbody>
      <tr v-if="calendarRooms.length > 0" v-for="room in calendarRooms" :key="room.id">
        <td class="text-center">{{room.name}}</td>
        <td v-for="(hour, ctr) in room.day_breakdown.slice(leftHourDelimiter, rightHourDelimiter)">
          <div class="flex flex-row justify-between">
            <div
              v-for="chunk in hour"
              :class="[chunk.closed ? 'bg-gray-800' : (chunk.booked ? 'bg-red-500' : 'bg-green-400')]"
              class="w-1/4 border-2">
              &nbsp;
            </div>
          </div>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>

import JetInput from "@src/Jetstream/Input";
import axios from "axios";
import Button from "@src/Jetstream/Button";
import moment from 'moment';

export default {
  props: {
  },

  components: {
    Button,
    JetInput
  },

  data() {
    return {
      dateSelected: moment(new Date()).format("YYYY-MM-DD"),
      leftHourDelimiter: 8,
      rightHourDelimiter: 21,
      calendarRooms: [],
      dailyHours:
        ['00:00', '1:00', '2:00', '3:00', '4:00', '5:00', '6:00', '7:00', '8:00', '9:00', '10:00', '11:00',
          '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00', '24:00']
    }
  },

  methods: {
    getCalendarRooms() {
      axios.get('/api/reservations/by-date?date='+this.dateSelected).then((response)=>{
          this.calendarRooms = response.data;
        })
    },
    bumpCalendar(direction){
      if(direction == 'right'){
        this.leftHourDelimiter += 1;
        this.rightHourDelimiter += 1;
      }
      if(direction == 'left'){
        this.leftHourDelimiter -= 1;
        this.rightHourDelimiter -= 1;
      }
    }

  },
  computed: {

  },
  mounted(){
    this.getCalendarRooms();
  },
  watch: {
    dateSelected() {
      this.getCalendarRooms();
    }
  }
};
</script>
