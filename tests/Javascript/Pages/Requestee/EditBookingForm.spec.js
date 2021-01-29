import {beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import EditBookingForm from '@src/Pages/Requestee/EditBookingForm'
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import moment from "moment";

let localVue
let wrapper

beforeEach(() => {
  InertiaFormMock.error.mockClear()
  InertiaFormMock.post.mockClear()
  InertiaFormMock.delete.mockClear()

  localVue = createLocalVue()
  localVue.use(InertiaApp)
  localVue.use(InertiaForm)

});


test('should mount without crashing', () => {

     wrapper = shallowMount(EditBookingForm, {
      localVue,
      propsData: {
          booking: {
              event: {"start_event":"19:11","end_event":"19:13","title":"event 3","type":"event3","description":"3","guest_speakers":"3","attendees":"3","fee":"yes","music":"music","food":{"low_risk":false,"high_risk":false},"alcohol":false,"av":true,"furniture":true},
              reservations:  [{"id":1,"room_id":8,"booking_request_id":1,"start_time":"2021-01-26T05:29:15.000000Z","end_time":"2021-01-26T05:31:15.000000Z","created_at":"2021-01-26T05:29:28.000000Z","updated_at":"2021-01-26T05:29:28.000000Z","room":{"id":8,"name":"event3","number":"3","floor":3,"building":"3","created_at":"2021-01-26T05:25:15.000000Z","updated_at":"2021-01-26T05:25:15.000000Z","status":"available","min_days_advance":null,"max_days_advance":null,"attributes":{"food":false,"sofas":null,"chairs":null,"tables":null,"alcohol":false,"computer":false,"projector":false,"fundraiser":false,"television":false,"whiteboard":false,"a_v_permitted":false,"ambiant_music":false,"coffee_tables":null,"sale_for_profit":false,"capacity_sitting":null,"capacity_standing":3},"room_type":"Mezzanine"}}],
              reference:{"path":"9_1611689494_reference"},
              onsite_contact: {}
          }
      },
    })

    expect(wrapper.text()).toBeDefined()
  })

  test('updateForm()', () => {
    const booking = {
      event: {"start_event":"19:11","end_event":"19:13","title":"event 3","type":"event3","description":"3","guest_speakers":"3","attendees":"3","fee":"yes","music":"music","food":{"low_risk":false,"high_risk":false},"alcohol":false,"av":true,"furniture":true},
      reservations:  [{"id":1,"room_id":8,"booking_request_id":1,"start_time":"2021-01-26T05:29:15.000000Z","end_time":"2021-01-26T05:31:15.000000Z","created_at":"2021-01-26T05:29:28.000000Z","updated_at":"2021-01-26T05:29:28.000000Z","room":{"id":8,"name":"event3","number":"3","floor":3,"building":"3","created_at":"2021-01-26T05:25:15.000000Z","updated_at":"2021-01-26T05:25:15.000000Z","status":"available","min_days_advance":null,"max_days_advance":null,"attributes":{"food":false,"sofas":null,"chairs":null,"tables":null,"alcohol":false,"computer":false,"projector":false,"fundraiser":false,"television":false,"whiteboard":false,"a_v_permitted":false,"ambiant_music":false,"coffee_tables":null,"sale_for_profit":false,"capacity_sitting":null,"capacity_standing":3},"room_type":"Mezzanine"}}],
      reference:{"path":"9_1611689494_reference"},
      onsite_contact: {}
      }

     wrapper = shallowMount(EditBookingForm, {
        localVue,
        propsData: {
          booking: booking
      },
    })
    wrapper.vm.updateForm(booking)
})



test('toggleNullableForms()', () => {
  const booking = {
    event: {"start_event":"19:11","end_event":"19:13","title":"event 3","type":"event3","description":"3","guest_speakers":"3","attendees":"3","fee":"yes","music":"music","food":{"low_risk":false,"high_risk":false},"alcohol":false,"av":true,"furniture":true},
    reservations:  [{"id":1,"room_id":8,"booking_request_id":1,"start_time":"2021-01-26T05:29:15.000000Z","end_time":"2021-01-26T05:31:15.000000Z","created_at":"2021-01-26T05:29:28.000000Z","updated_at":"2021-01-26T05:29:28.000000Z","room":{"id":8,"name":"event3","number":"3","floor":3,"building":"3","created_at":"2021-01-26T05:25:15.000000Z","updated_at":"2021-01-26T05:25:15.000000Z","status":"available","min_days_advance":null,"max_days_advance":null,"attributes":{"food":false,"sofas":null,"chairs":null,"tables":null,"alcohol":false,"computer":false,"projector":false,"fundraiser":false,"television":false,"whiteboard":false,"a_v_permitted":false,"ambiant_music":false,"coffee_tables":null,"sale_for_profit":false,"capacity_sitting":null,"capacity_standing":3},"room_type":"Mezzanine"}}],
    reference:{"path":"9_1611689494_reference"},
    onsite_contact: {},
    // this.form.event.show?.contact === false

    }

   wrapper = shallowMount(EditBookingForm, {
      localVue,
      propsData: {
        booking: booking
    },
  })
  wrapper.vm.toggleNullableForms()
})

test('uploads can be set via a method', () => {
  wrapper.vm.uploadedFiles([]);

  expect(wrapper.vm.form.files).toStrictEqual([]);
})

test('disabling question forms will save their state in the form', () => {
  wrapper.vm.form.event.show = {contact:false, fee: false, music: false}

  wrapper.vm.toggleNullableForms();

  expect(wrapper.vm.form.event.show).toStrictEqual({contact:false, fee: false, music: false});
})

test('filter returns date', () => {

  expect(wrapper.vm.only_date(moment())).toBe(moment().format("dddd, Do MMMM YYYY"));
})

test('filter returns time', () => {

  expect(wrapper.vm.only_time(moment())).toBe(moment().format("LT"));
})

test('computed isRecurring should be false based on given data', () => {
    expect(wrapper.vm.isRecurring).toBe(false);
})

test('computed minStart and maxEnd should be true based on given data', () => {
  wrapper = shallowMount(EditBookingForm, {localVue,
    propsData: {
        booking:{
        reservations: [
            { start_time: "2020-01-20T18:02", end_time: "2020-01-20T18:05" },
            { start_time: "2020-01-21T18:02", end_time: "2020-01-21T18:05" },
        ],
        event: {"start_event":"19:11","end_event":"19:13","title":"event 3","type":"event3","description":"3","guest_speakers":"3","attendees":"3","fee":"yes","music":"music","food":{"low_risk":false,"high_risk":false},"alcohol":false,"av":true,"furniture":true},
        onsite_contact: {},
      }
    },
  })
    expect(wrapper.vm.minStart).toBe(moment().format("HH:mm"));
    expect(wrapper.vm.maxEnd).toBe(moment().format("HH:mm"));
})





