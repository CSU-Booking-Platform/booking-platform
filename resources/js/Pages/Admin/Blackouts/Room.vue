<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Blackouts on Room {{room.name}}, {{room.building}} - {{room.number}}
            </h2>
        </template>
        <div class="py-2">
            <div class="flex flex-col lg:flex-row md:mx-auto md:w-2/3 my-3">
                <div>
                    <create-blackout-form :room='room'/>
                </div>
                <div v-if="blackouts.length > 0">
                    <jet-section-border/>
                    <blackout-list :blackouts="blackouts" class="w-full"/>
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
