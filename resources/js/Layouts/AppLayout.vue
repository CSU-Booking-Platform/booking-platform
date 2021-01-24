<template>
    <div class="min-h-screen bg-gray-100">
        <Navbar/>

        <!-- Page Heading -->
        <header class="bg-white shadow" v-if="!!this.$slots[ 'header' ] || !!this.$scopedSlots[ 'header' ]">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header"></slot>
            </div>
        </header>

        <!-- Page Content -->
        <main>
            <slot></slot>
        </main>

        <!-- Modal Portal -->
        <portal-target name="modal" multiple>
        </portal-target>

        <!-- Footer -->
        <footer class="bg-black text-white text-xs font-bold pb-48">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex">
                    <div class="flex flex-col flex-1">
                        <div class="flex-1 py-3 font-black text-sm">
                            CONTACT US
                        </div>
                        <div class="flex-1 py-3">
                            contactus@csu.qc.ca
                        </div>
                        <div class="flex-1 py-3">
                            514 848-7474
                        </div>
                    </div>
                    <div class="flex flex-col flex-1">
                        <div class="flex-1 py-3 font-black text-sm">
                            CSU
                        </div>
                        <div class="flex-1 py-3">
                            Our Mission
                        </div>
                        <div class="flex-1 py-3">
                            Contact
                        </div>
                        <div class="flex-1 py-3">
                            Jobs
                        </div>
                    </div>
                    <div class="flex flex-col flex-1">
                        <div class="flex-1 py-3 font-black text-sm">
                            SOCIAL MEDIA
                        </div>
                        <div class="flex-1 py-3">
                            <a href="https://www.facebook.com">
                                <i class="fa"></i>
                                Facebook
                            </a>
                        </div>
                        <div class="flex-1 py-3">
                            <a href="https://www.instagram.com">
                                <i class="fa"></i>
                                Instagram
                            </a>
                        </div>
                        <div class="flex-1 py-3">
                            <a href="https://www.twitter.com">
                                <i class=""></i>
                                Twitter
                            </a>
                        </div>
                        <div class="flex-1 py-3">
                            <a href="https://www.youtube.com">
                                <i class=""></i>
                                YouTube
                            </a>
                        </div>
                    </div>
                    <div class="flex flex-col flex-1">
                        <div class="flex-1 py-3 font-black text-sm">
                            SUBSCRIBE TO OUR NEWSLETTER
                        </div>
                        <div class="flex-1 py-3">
                            <div class="mt-1 relative rounded-sm shadow-sm">
                                <input type="text" name="email" id="email"
                                       class="focus:ring-indigo-500 focus:border-indigo-500 block w-full py-2 pl-1 pr-12 sm:text-sm border-gray-300 rounded-md bg-black border-2 border-white"
                                       placeholder="Email Address">
                            </div>
                        </div>
                        <div class="flex-1 py-3">
                            <div class="mt-1 relative rounded-sm shadow-sm">
                                <button type="button"
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full py-2 pl-1 pr-12 sm:text-sm border-gray-300 rounded-md bg-white text-black">
                                    SUBSCRIBE
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
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
import axios from 'axios'

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

    methods: {
        logout() {
            axios.post('/logout').then(response => {
                window.location = '/';
            })
        },
    },

    computed: {
        path() {
            return window.location.pathname
        }
    }
}
</script>
