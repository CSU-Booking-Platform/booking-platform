<template>
    <x-section class="flex flex-col">
        <div class="mb-5">
            <slot name="title"></slot>
            <slot name="description"></slot>
        </div>

        <form @submit.prevent="$emit('submitted')">
            <slot name="form"></slot>
        <div class="my-3"></div>
        <div class="flex items-center justify-end -m-6 px-6 py-4 bg-gray-50 text-right sm:px-6 rounded-md"
             v-if="hasActions">
            <slot name="actions"></slot>
        </div>
        </form>
    </x-section>
</template>

<script>

import XSection from "@src/Components/XSection";

export default {
    components: {XSection},
    computed: {
        hasActions() {
            return !! this.$slots.actions
        }
    }
}
</script>
