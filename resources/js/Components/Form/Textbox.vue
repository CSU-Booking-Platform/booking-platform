<template>
    <textarea class="form-input rounded-md shadow-sm p-3 h-60 outline-none"
              :value="value" @input="$emit('input', $event.target.value)"
              ref="input" :placeholder="placeholder"
    />
</template>

<script>
export default {
    props: {
        value: {
            required: true,
        },
        placeholder: {
            default: '',
        }
    },

    methods: {
        focus() {
            this.$refs.input.focus()
        }
    }
}
</script>
