<template>
  <inertia-link :href="href" :class="classes">
    <slot></slot>
  </inertia-link>
</template>

<script>
export default {
  props: ['href', 'active'],

  computed: {
    classes() {
      let common = "inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-black transition duration-150 ease-in-out hover:font-bold ";
      return this.active
        ? common + 'underline text-yellow-300'
        : common
    }
  }
}
</script>
