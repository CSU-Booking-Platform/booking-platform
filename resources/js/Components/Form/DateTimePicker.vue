<template>
  <ctk-date-time-picker v-model="proxyValue" :minute-interval="minuteInterval" :format="format">
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
    minuteInterval: {
      default: 15,
    },
    format: {
      default: "YYYY-MM-DD HH:mm",
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
