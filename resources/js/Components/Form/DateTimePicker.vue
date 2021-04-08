<template>
  <ctk-date-time-picker dusk="date-time-picker" :id="id" v-model="proxyValue" :minute-interval="minuteInterval" :format="format">
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
      default: "YYYY-MM-DD HH:mm",
    },
    noClearButton: {
      default: false,
    },
    noHeader: {
      default: false,
    },
    noLabel: {
      default: false,
    },
    autoClose: {
      default: false,
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
