<template>
  <div class="min-h-screen flex flex-col justify-between">
    <Navbar/>

    <!-- Page Heading -->
    <header class="bg-white shadow" v-if="!!this.$slots[ 'header' ] || !!this.$scopedSlots[ 'header' ]">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <slot name="header"></slot>
      </div>
    </header>

    <!-- Page Content -->
    <main class="flex-grow">
      <slot></slot>
    </main>

    <!-- Footer -->
    <footer class="bg-black text-white text-sm font-bold pb-12">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex">
          <div class="flex flex-col flex-1">
            <div class="flex-1 py-3 font-black text-sm">
              CONTACT US
            </div>
            <div class="flex-1 py-3">
              <a href="mailto:contactus@csu.qc.ca">contactus@csu.qc.ca</a>
            </div>
            <div class="flex-1 py-3">
              <a href="tel:5148487474">514 848-7474</a>
            </div>
          </div>
          <div class="flex flex-col flex-1">
            <div class="flex-1 py-3 font-black text-sm">
              CSU
            </div>
            <div class="flex-1 py-3">
              <a href="https://www.csu.qc.ca/about-us/our-mission/">Our Mission</a>
            </div>
            <div class="flex-1 py-3">
              <a href="https://www.csu.qc.ca/about-us/contact/">Contact</a>
            </div>
            <div class="flex-1 py-3">
              <a href="https://www.csu.qc.ca/about-us/jobs/">Jobs</a>
            </div>
          </div>
          <div class="flex flex-col flex-1">
            <div class="flex-1 py-3 font-black text-sm">
              SOCIAL MEDIA
            </div>
            <div class="flex-1 py-3">
              <a href="https://www.facebook.com/csumtl/">
                <em class="fab fa-facebook pr-2"></em>
                Facebook
              </a>
            </div>
            <div class="flex-1 py-3">
              <a href="https://www.instagram.com/csumtl/">
                <em class="fab fa-instagram pr-2"></em>
                Instagram
              </a>
            </div>
            <div class="flex-1 py-3">
              <a href="https://twitter.com/csumtl">
                <em class="fab fa-twitter pr-2"></em>
                Twitter
              </a>
            </div>
            <div class="flex-1 py-3">
              <a href="https://www.youtube.com/channel/UC14BOYjvdSqaCoCJfede9SA">
                <em class="fab fa-youtube pr-2"></em>
                YouTube
              </a>
            </div>
          </div>
        </div>
      </div>
    </footer>

    <!-- Modal Portal -->
    <portal-target name="modal" multiple>
    </portal-target>
  </div>
</template>

<script>
import JetApplicationLogo from '@src/Jetstream/ApplicationLogo'
import JetApplicationMark from '@src/Components/ApplicationMark'
import JetDropdown from '@src/Jetstream/Dropdown'
import JetDropdownLink from '@src/Jetstream/DropdownLink'
import JetNavLink from '@src/Components/Navbar/NavLink'
import JetResponsiveNavLink from '@src/Jetstream/ResponsiveNavLink'
import Navbar from '@src/Components/Navbar/Navbar'

export default {
  components: {
    JetApplicationLogo,
    JetApplicationMark,
    JetDropdown,
    JetDropdownLink,
    JetNavLink,
    JetResponsiveNavLink,
    Navbar
  },

  data() {
    return {
      showingNavigationDropdown: false,
    }
  },
  computed: {
    path() {
      return window.location.pathname
    }
  }
}
</script>
