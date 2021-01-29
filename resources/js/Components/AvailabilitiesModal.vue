<template>
  <jet-dialog-modal :show="room" @close="closeModal">
    <template #title> Availabilities </template>

    <template #content>
      <div class="overflow-y-auto h-96">
        <table class="table-auto">
          <caption></caption>
          <thead>
            <tr>
              <th id="business" class="lt-grey p-3">Business Hours</th>
              <template v-for="availability in availabilities">
                <th :id="availability" class="lt-grey p-3">
                  {{ availability.weekday }}
                </th>
              </template>
            </tr>
          </thead>
          <tr>
            <td class="lt-grey p-3">Opening Hours</td>
            <template v-for="availability in availabilities">
              <td class="lt-grey p-3">{{ availability.opening_hours }}</td>
            </template>
          </tr>
          <tr>
            <td class="lt-grey p-3">Closing Hours</td>
            <template v-for="availability in availabilities">
              <td class="lt-grey p-3">{{ availability.closing_hours }}</td>
            </template>
          </tr>
        </table>

        <div class="flex justify-evenly pl-2 pr-2 pt-5 pb-5">
          <div>
            <h2>Legend</h2>
            <p>
              <em class="fas fa-circle fill-current text-green-600"></em>
              Available
            </p>
            <p>
              <em class="fas fa-circle fill-current text-red-600"></em>
              Non-Available
            </p>
            <p>
              <em class="fas fa-circle fill-current text-blue-600"></em>
              Selected
            </p>
            <p><em class="fas fa-circle"></em> Blackbox</p>
          </div>
          <div>
            <Calendar
              :attributes="attributes"
              @dayclick="onDayClick"
              trim-weeks
            />
          </div>
        </div>
        <hr />

        <div class="flex justify-evenly pl-2 pr-2 pt-5">
          <div>
            <h2>Bookings for selected day :</h2>
            <template v-for="day in days">
              <p>{{ day.date }}</p>
            </template>
          </div>
          <div>
            <div class="bg-yellow-400 p-3 m-1" v-for="reservation in reservations">
              <p>
                {{ "Start time: " + moment(reservation.start_time) }}
              </p>
              <p>
                {{ "End time: " + moment(reservation.end_time) }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </template>
  </jet-dialog-modal>
</template>

<script>
import JetButton from "@src/Jetstream/Button";
import JetInput from "@src/Jetstream/Input";
import JetActionMessage from "@src/Jetstream/ActionMessage";
import JetFormSection from "@src/Jetstream/FormSection";
import JetInputError from "@src/Jetstream/InputError";
import JetLabel from "@src/Jetstream/Label";
import JetDialogModal from "@src/Jetstream/DialogModal";
import JetDropdown from "@src/Jetstream/Dropdown";
import JetDropdownLink from "@src/Jetstream/DropdownLink";
import JetNavLink from "@src/Components/Navbar/NavLink";
import JetSecondaryButton from "@src/Jetstream/SecondaryButton";
import DialogModal from "@src/Jetstream/DialogModal";
import Calendar from "v-calendar/lib/components/calendar.umd";
import DatePicker from "v-calendar/lib/components/date-picker.umd";
import moment from "moment";
import axios from "axios";

export default {
  components: {
    DialogModal,
    JetButton,
    JetInput,
    JetFormSection,
    JetActionMessage,
    JetInputError,
    JetLabel,
    JetDropdown,
    JetDropdownLink,
    JetNavLink,
    JetDialogModal,
    JetSecondaryButton,
    Calendar,
    DatePicker,
  },

  props: {
    room: {
      type: Object,
      required: false
    }
  },
  computed: {
    dates() {
      return this.days.map(day => day.date);
    },
    attributes() {
      return this.retrieveAttributes();
    }
  },
  data() {
    return {
      availabilities: [],
      days: [],
      blackouts: [],
      reservations: []
    };
  },
  methods: {
    closeModal() {
      this.$emit("close");
    },
    onDayClick(day) {
      const idx = this.days.findIndex(d => d.id === day.id);
      if (idx >= 0) {
        this.days.splice(idx, 1);
      } else {
        this.days = [];
        this.days.push({
          id: day.id,
          date: day.date.toLocaleDateString("en-US", {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric"
          })
        });
      }
    },
    getWeekdayNumber(weekday) {
      switch (weekday) {
        case "Sunday":
          return 1;
        case "Monday":
          return 2;
        case "Tuesday":
          return 3;
        case "Wednesday":
          return 4;
        case "Thursday":
          return 5;
        case "Friday":
          return 6;
        case "Saturday":
          return 7;
      }
    },
    weekdayInsideAvailabilities(weekday) {
      let inside = false;
      if (this.availabilities) {
        this.availabilities.forEach(availability => {
          if (availability.weekday === weekday) {
            inside = true;
          }
        });
      }
      return inside;
    },
    fetchRoomReservations() {
      if (this.room) {
        axios
          .post("/api/reservations/" + this.room.id, {
            date: this.dates[0]
          })
          .then(response => {
            this.reservations = response.data;
          });
      }
    },
    moment: function(date) {
      return moment(date).format("HH:mm:ss");
    },
    retrieveAttributes() {
      let attributes = [];

      this.dates.forEach(date => {
        attributes.push({
          highlight: true,
          dates: date
        });
      });

      this.fetchRoomReservations();

      let dayOfWeek = [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
        "Sunday"
      ];

      dayOfWeek.forEach(day => {
        if (this.weekdayInsideAvailabilities(day)) {
          attributes.push({
            dot: "green",
            dates: { weekdays: this.getWeekdayNumber(day) }
          });
        } else {
          attributes.push({
            dot: "red",
            dates: { weekdays: this.getWeekdayNumber(day) }
          });
        }
      });

      if (this.blackouts) {
        this.blackouts.forEach(blackout => {
          var currDate = moment(blackout.start_time).startOf("day");
          var lastDate = moment(blackout.end_time).startOf("day");

          attributes.push({
            dot: {
              style: {
                backgroundColor: "black"
              }
            },
            dates: new Date(currDate.toDate())
          });

          attributes.push({
            dot: {
              style: {
                backgroundColor: "black"
              }
            },
            dates: new Date(lastDate.toDate())
          });

          while (currDate.add(1, "days").diff(lastDate) < 0) {
            attributes.push({
              dot: {
                style: {
                  backgroundColor: "black"
                }
              },
              dates: new Date(currDate.toDate())
            });
          }
        });
      }

      return attributes;
    }
  },
  watch: {
    room(room) {
      this.availabilities = room?.availabilities;
      this.blackouts = room?.blackouts;
    }
  }
};
</script>
