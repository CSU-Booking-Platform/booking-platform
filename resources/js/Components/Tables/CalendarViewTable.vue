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
      <div class="mx-6 m-auto">
        <button @click="bumpCalendar('left')">
          Left
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
      <div  class="mx-6 m-auto">
        <button @click="bumpCalendar('right')">
          Right
        </button>
      </div>


    </div>

    <table class="table-auto responsive-calendar">
      <caption></caption>
      <thead>
      <tr>
        <th class="font-bold w-1/8">Room Name</th>
        <th class="text-center w-1/16" v-for="hour in dailyHours.slice(leftHourDelimiter, rightHourDelimiter)">{{hour}}</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="room in calendarRooms" :key="room.id">
        <td class="text-center">{{room.name}}</td>
        <td v-for="(i, ctr) in 13">
          <div class="flex flex-row justify-between">
            <div
              v-bind:class="[timeChunkBooked(room, ctr+leftHourDelimiter, ctr+leftHourDelimiter+0.25) ? 'bg-red-500 w-1/4 border-2' : 'bg-green-400 w-1/4 border-2']"
            >
              &nbsp;
            </div>
            <div
              v-bind:class="[timeChunkBooked(room, ctr+leftHourDelimiter+0.25, ctr+leftHourDelimiter+0.5) ? 'bg-red-500 w-1/4 border-2' : 'bg-green-400 w-1/4 border-2']"
            >
              &nbsp;
            </div>
            <div
              v-bind:class="[timeChunkBooked(room, ctr+leftHourDelimiter+0.5, ctr+leftHourDelimiter+0.75) ? 'bg-red-500 w-1/4 border-2' : 'bg-green-400 w-1/4 border-2']"
            >
              &nbsp;
            </div>
            <div
              v-bind:class="[timeChunkBooked(room, ctr+leftHourDelimiter+0.75, ctr+leftHourDelimiter+1) ? 'bg-red-500 w-1/4 border-2' : 'bg-green-400 w-1/4 border-2']"
            >
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

export default {
  props: {
  },

  components: {
    Button,
    JetInput
  },

  data() {
    return {
      dateSelected: '',
      leftHourDelimiter: 8,
      rightHourDelimiter: 21,
      calendarRooms: [
        {
          name: 'Art Nook',
          id: 1,
          reservations: [
            {
              start_time: "9:15",
              end_time: "9:45"
            }
          ]
        }
      ],
      dailyHours:
        ['00:00', '1:00', '2:00', '3:00', '4:00', '5:00', '6:00', '7:00', '8:00', '9:00', '10:00', '11:00',
          '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00']
    }
  },

  methods: {
    getCalendarRooms() {
      axios.post('/api/calendarRooms', { date: this.dateSelected })
        .then((response)=>{
          this.calendarRooms = response.data;
        })
    },
    timeToFloat(time){
      const h = parseInt(time.split(':')[0]);
      const m = parseInt(time.split(':')[1])/60;
      return h + m;
    },
    timeChunkBooked(room, chunkStart, chunkEnd){
      let booked = room.reservations.find(reservation => {
        let start = this.timeToFloat(reservation.start_time);
        let end = this.timeToFloat(reservation.end_time);
        if(start <= chunkStart && end >= chunkEnd){
          return true;
        }
      });
      return booked !== undefined;
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

  }
};
</script>
