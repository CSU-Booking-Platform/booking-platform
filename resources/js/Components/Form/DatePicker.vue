<template>
  <ctk-date-time-picker dusk="date-time-picker" :id="id"
                        v-model="proxyValue"
                        :only-date="true"
                        :format="format"
                        :formatted="format"
                        :noClearButton="noClearButton"
                        :noHeader="noHeader"
                        :noLabel="noLabel"
                        :autoClose="autoClose"
  >
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
    format: {
      default: "YYYY-MM-DD",
    },
    noClearButton: {
      default: false,
    },
    noHeader: {
      default: false,
    },
    noLabel: {
      default: true,
    },
    autoClose: {
      default: true,
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
