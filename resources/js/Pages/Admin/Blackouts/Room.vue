<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Blackouts on Room {{room.name}}, {{room.building}} - {{room.number}}
            </h2>
        </template>
       
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden sm:rounded-lg">
                    <create-blackout-form :room='room'/>
                </div>
                <div v-if="blackouts.length > 0">
                    <jet-section-border/>
                    <blackout-list :blackouts="blackouts"/>
                </div>
            </div>
        </div>
        
    </app-layout>
</template>

<script>
import JetSectionBorder from '@src/Jetstream/SectionBorder'
import CreateBlackoutForm from './CreateBlackoutForm';
import BlackoutList from './BlackoutList';
import AppLayout from '@src/Layouts/AppLayout';

export default {
    components: {
        CreateBlackoutForm,
        AppLayout,
        BlackoutList,
        JetSectionBorder,
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
