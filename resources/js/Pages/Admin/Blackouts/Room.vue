<template>
    <app-layout>
        <div class="flex flex-col lg:flex-row md:mx-auto md:w-11/12">
            <div class="m-4 w-1/4 pt-12 pb-48">
                <create-blackout-form :room='room'/>
            </div>
            <div v-if="blackouts.length > 0" class="m-4 w-3/4">
                <div class="mx-24 mt-16">
                    <blackout-list :blackouts="blackouts" :room="room" class="w-full" dusk="blackout-list"/>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import CreateBlackoutForm from './CreateBlackoutForm';
import BlackoutList from './BlackoutList';
import AppLayout from '@src/Layouts/AppLayout';

export default {
    components: {
        CreateBlackoutForm,
        AppLayout,
        BlackoutList,
    },
    props: {
        room: {
            required:true
        },
        blackouts: {
            type: Array,
            default: function () {
                return []
            },
        }
    }
}
</script>
