<template>
  <jet-confirmation-modal :show="user != null" @close="closeModal">
    <template #title>
      Generate reset link for {{ user ? user.name : "" }}
    </template>

    <template #content>
      <!-- If the form is processing, display the loading screen -->
      <div v-show="isProcessing">
        <p>Loading...</p>
      </div>
      <!-- Else, Display the resetLink, if one is set-->
      <div v-show="resetLink != null && !isProcessing">
        <p>Reset link generated:</p>
        <br/>
        <pre class="text-sm overflow-x-auto whitespace-pre-wrap break-all">{{ resetLink }}</pre>
      </div>
      <!-- Else, Display the warning text before continuing -->
      <div v-show="resetLink == null && !isProcessing">
        <p>Warning: This will invalidate any existing password reset tokens of the user.</p>
      </div>
    </template>

    <template #footer>
      <jet-secondary-button @click.native="closeModal" :disabled="isProcessing">
        {{ resetLink != null ? "I've copied the link to a safe place, close this window" : "Nevermind" }}
      </jet-secondary-button>

      <jet-danger-button v-if="resetLink == null" class="ml-2" @click.native="getNewLink"
                  :class="{ 'opacity-25':isProcessing }"
                  :disabled="isProcessing">
        Generate link
      </jet-danger-button>
    </template>
  </jet-confirmation-modal>
</template>

<script>

import JetConfirmationModal from '@src/Jetstream/ConfirmationModal'
import JetSecondaryButton from '@src/Jetstream/SecondaryButton'
import JetDangerButton from '@src/Jetstream/DangerButton'
import JetButton from '@src/Jetstream/Button'
import JetModal from '@src/Jetstream/Modal'
import axios from "axios"

export default {
  components: {
    JetButton,
    JetDangerButton,
    JetSecondaryButton,
    JetConfirmationModal,
    JetModal,
  },
  name: "ResetLinkModal",
  props: {
    user: {
      type: Object,
      default: null,
    }
  },
  data() {
    return {
      resetLink: null,
      isProcessing: false
    }
  },
  methods: {
    closeModal() {
      this.resetLink = null;
      this.$emit('close')
    },
    getNewLink() {
      this.isProcessing = true;
      axios.post(`/api/admin/users/${this.user.id}/reset-token`).then((response) => {
        if (response.data.hasOwnProperty("link"))
          this.resetLink = response.data.link
        this.isProcessing = false;
      });
    },
  },

}
</script>
