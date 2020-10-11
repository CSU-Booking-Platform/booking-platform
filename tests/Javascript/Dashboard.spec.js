import Vue from 'vue'
import { shallowMount } from '@vue/test-utils'
import { InertiaApp } from '@inertiajs/inertia-vue'
import { InertiaForm } from 'laravel-jetstream'
import Dashboard from '../../resources/js/Pages/Dashboard'
import {test} from "@jest/globals";

Vue.use(InertiaApp)
Vue.use(InertiaForm)

//This is an example, please do more than just test if it crashes :p
test('should mount without crashing', () => {
    shallowMount(Dashboard)
})
