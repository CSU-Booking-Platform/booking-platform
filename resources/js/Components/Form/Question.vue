<template>
    <div class="flex-column">
        <!-- Question -->
        <div class="flex items-center space-x-2">
            <slot name="header"></slot>

            <jet-checkbox :id="id" :value="value" v-model="proxyChecked"/>

            <slot name="after">
                <span v-if="proxyChecked" class="text-md text-black">Yes</span>
            </slot>
        </div>

        <div v-if="proxyChecked">
            <slot></slot>
        </div>
    </div>
</template>

<script>
import JetCheckbox from '@src/Jetstream/Checkbox';

export default {
    components: {
        JetCheckbox,
    },

    model: {
        prop: "checked",
        event: "change",
    },

    props: {
        checked: {
            type: [Array, Boolean],
            default: false,
        },
        value: {
            default: null,
        },
        placeholder: {
            default: null,
        },
        id: {
          default: null
        }
    },

    data() {
        return {
            actualChecked: false,
        }
    },

    watch: {
        checked(val) {
            this.proxyChecked = val;
        }
    },

    computed: {
        proxyChecked: {
            get() {
                return this.actualChecked;
            },
            set(val) {
                this.actualChecked = val;
                this.$emit("change", val);
            },
        },
    },
}
</script>
