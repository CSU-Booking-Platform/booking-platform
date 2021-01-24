<template>
  <div class="py-2 text-md">
    <slot> </slot>
  </div>
</template>

<script>
import JetApplicationLogo from "@src/Jetstream/ApplicationLogo";
import JetApplicationMark from "@src/Components/ApplicationMark";
import JetDropdown from "@src/Jetstream/Dropdown";
import JetDropdownLink from "@src/Jetstream/DropdownLink";
import JetNavLink from "@src/Components/Navbar/SubNavLink";
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
