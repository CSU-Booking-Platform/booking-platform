<template>
    <select v-model="selected" v-bind="$attrs" class="form-select form-input rounded-md shadow-sm" :class="{ error: error }" ref="input">
        <slot />
    </select>
</template>

<script>
export default {
    props: {
        value: [String, Number, Boolean],
        error: String,
    },
    data() {
        return {
            selected: this.value,
        }
    },
    watch: {
        selected(selected) {
            this.$emit('input', selected)
        },
    },
    methods: {
        focus() {
            this.$refs.input.focus()
        },
    },
}
</script>
