<template>
  <div>
    <jet-dialog-modal :show="room">
      <template #title>
        Detailed View of <span v-if="room">{{room.name}}</span>
      </template>

      <template #content>
        <div class="overflow-y-auto h-96">
          <div class="flex flex-wrap grid grid-cols-3 gap-4" v-if="room">
          <div v-for="(attribute, key) in room.attributes" :key="attribute.id">
            <jet-label :for="convertVarToText(key)" :value="convertVarToText(key)+':'"></jet-label>
            <div v-if="typeof(attribute) == 'boolean'">
              <div v-if="attribute" class="text-center rounded-full py-2 px-4 bg-green-300">Yes</div>
              <div v-else class="text-center rounded-full py-2 px-4 bg-red-400">No</div>
            </div>
            <div v-if="typeof(attribute) == 'number' || Number.isInteger(attribute)">
              <div class="text-center rounded-full py-2 px-4 bg-gray-300">{{attribute}}</div>
            </div>
          </div>
          <div>
            <jet-label :for="'room_type'" :value="'Room Type:'"></jet-label>
            <div class="text-center rounded-full py-2 px-4 bg-gray-300">{{room.room_type}}</div>
          </div>
        </div>
        </div>
      </template>

      <template #footer>
        <jet-secondary-button @click.native="closeModal">
          Dismiss
        </jet-secondary-button>
      </template>
    </jet-dialog-modal>
  </div>
</template>

<script>

import JetSecondaryButton from "@src/Jetstream/SecondaryButton";
import JetDialogModal from "@src/Jetstream/DialogModal";
import JetLabel from "@src/Jetstream/Label";

export default {
  props: {
    room: {
      type: Object,
      required: false
    }
  },
  data(){
    return{

    }
  },
  components: {
    JetSecondaryButton,
    JetDialogModal,
    JetLabel
  },
  methods: {
    closeModal() {
      this.$emit("close");
    },
    convertVarToText(variable){
      const no_spaces = variable.replace(/_/g, ' ');
      return no_spaces.toLowerCase()
        .split(' ')
        .map((s) => s.charAt(0).toUpperCase() + s.substring(1))
        .join(' ');
    }
  }
}
</script>
