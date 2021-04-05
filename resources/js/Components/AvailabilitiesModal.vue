<template>
  <jet-dialog-modal :show="room" @close="closeModal" max-width="">
    <template #title> Availabilities </template>

    <template #content>
      <Availabilities :room="room" />
    </template>
  </jet-dialog-modal>
</template>

<script>
import JetDialogModal from "@src/Jetstream/DialogModal";
import Availabilities from "@src/Components/Availabilities";

export default {
  components: {
    JetDialogModal,
    Availabilities
  },

  props: {
    room: {
      type: Object,
      required: false
    }
  },
  methods: {
    closeModal() {
      this.$emit("close");
    }
  }
};
</script>
