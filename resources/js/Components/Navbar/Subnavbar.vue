<template>
  <div>
    <slot> </slot>
  </div>
</template>

<script>
import JetApplicationLogo from "@src/Jetstream/ApplicationLogo";
import JetApplicationMark from "@src/Jetstream/ApplicationMark";
import JetDropdown from "@src/Jetstream/Dropdown";
import JetDropdownLink from "@src/Jetstream/DropdownLink";
import JetNavLink from "@src/Jetstream/SubNavLink";
import JetResponsiveNavLink from "@src/Jetstream/ResponsiveNavLink";

export default {
  components: {
    JetApplicationLogo,
    JetApplicationMark,
    JetDropdown,
    JetDropdownLink,
    JetNavLink,
    JetResponsiveNavLink,
  },
  data() {
    return {};
  },
  computed: {
    path() {
      return window.location.pathname;
    },
  },
};
</script>