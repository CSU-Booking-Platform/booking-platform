/* istanbul ignore file */
require('./bootstrap');

import Vue from 'vue';

import { InertiaApp } from '@inertiajs/inertia-vue';
import { InertiaForm } from 'laravel-jetstream';
import PortalVue from 'portal-vue';
import Calendar from 'v-calendar/lib/components/calendar.umd'
import DateFormatter from "@src/Plugins/date-formatter";

import VueCtkDateTimePicker from 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.umd';
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';

Vue.use(InertiaApp);
Vue.use(InertiaForm);
Vue.use(PortalVue);
Vue.component('calendar', Calendar)
Vue.component('ctk-date-time-picker', VueCtkDateTimePicker)
Vue.use(DateFormatter);

const app = document.getElementById('app');

new Vue({
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);
