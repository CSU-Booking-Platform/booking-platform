<template>
  <ctk-date-time-picker dusk="time-picker" :id="id" :only-time="true" v-model="proxyValue" :minute-interval="minuteInterval" :format="format">
    <slot></slot>
  </ctk-date-time-picker>
</template>

<script>

export default {

  model: {
    prop: "value",
    event: "change",
  },

  props: {
    value: {
      default: null,
    },
    id: {
      default: undefined,
    },
    minuteInterval: {
      default: 15,
    },
    format: {
      default: "HH:mm",
    },
  },

  computed: {
    proxyValue: {
      get() {
        return this.value;
      },
      set(val) {
        this.$emit("change", val);
      },
    },
  },

}
</script>
