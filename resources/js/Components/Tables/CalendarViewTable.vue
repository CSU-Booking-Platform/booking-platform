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
        <button v-if="leftHourDelimiter >= 1" @click="bumpCalendar('left')">
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
        <button v-if="rightHourDelimiter <= 23" @click="bumpCalendar('right')">
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
        <td v-for="(hour, ctr) in room.color_breakdown.slice(leftHourDelimiter, rightHourDelimiter)">
          <div class="flex flex-row justify-between">
            <div v-for="chunk in hour" :class="chunk.color" class="w-1/4 border-2">
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
          color_breakdown: [
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-red-500"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-red-500"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-red-500"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-red-500"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-red-500"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-red-500"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-green-400"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-gray-800"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-green-400"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-gray-800"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-green-400"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-gray-800"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-green-400"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-gray-800"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-green-400"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-gray-800"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-green-400"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-gray-800"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-gray-800"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-gray-800"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-gray-800"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-gray-800"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-green-400"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-gray-800"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-green-400"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-gray-800"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-green-400"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-gray-800"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-green-400"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-gray-800"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-green-400"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-gray-800"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-green-400"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-gray-800"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-green-400"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-gray-800"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-green-400"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-gray-800"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-green-400"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-gray-800"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-green-400"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-gray-800"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-green-400"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-gray-800"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-green-400"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-gray-800"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
              {
                start_time: "00:15",
                end_time: "00:30",
                color: "bg-red-500"
              },
              {
                start_time: "00:30",
                end_time: "00:45",
                color: "bg-green-400"
              },
              {
                start_time: "00:45",
                end_time: "01:00",
                color: "bg-gray-800"
              }
            ],
            [
              {
                start_time: "00:00",
                end_time: "00:15",
                color: "bg-red-500"
              },
                {
                  start_time: "00:15",
                  end_time: "00:30",
                  color: "bg-red-500"
                },
                {
                  start_time: "00:30",
                  end_time: "00:45",
                  color: "bg-green-400"
                },
                {
                  start_time: "00:45",
                  end_time: "01:00",
                  color: "bg-gray-800"
                }
              ]
          ],
        }
      ],
      dailyHours:
        ['00:00', '1:00', '2:00', '3:00', '4:00', '5:00', '6:00', '7:00', '8:00', '9:00', '10:00', '11:00',
          '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00', '24:00']
    }
  },

  methods: {
    getCalendarRooms() {
      axios.get('/api/reservations/by-date', {
        date: this.dateSelected
      }).then((response)=>{
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
  watch: {
    dateSelected() {
      this.getCalendarRooms();
    }
  }
};
</script>
