<template>
  <div>

    <jet-dropdown width="full" :content-classes="['bg-white', 'max-h-64', 'overflow-y-auto']"
                  :keep-open="true" @closed="$emit('change', selected)" @opened="$emit('opened')"
    >
      <template #trigger>

        <div class="my-2 p-1 flex border border-gray-200 bg-white rounded">
          <div v-if="Object.entries(selected).length === 0" class="flex-1">
            <input placeholder="Select an option" disabled
                   class="bg-transparent p-1 px-2 appearance-none outline-none h-full w-full text-gray-800"
            >
          </div>
          <!-- tags like input field -->
          <div v-else class="flex flex-auto flex-wrap">
            <div v-for="(option, index) in selected" :key="index">
              <div class="flex justify-center items-center m-1 py-1 px-2 space-x-2 rounded-full bg-yellow-300 border border-yellow-500">
                <img v-if="option instanceof Object && 'icon' in option" class="h-8 w-8 rounded-full object-cover " :src="option.icon" :alt="option.text"/>
                <div class="text-xs font-normal leading-none max-w-full flex-initial">{{ option instanceof String ? option : option.text }}</div>
                <svg :id="'remove-'+index" @click="remove(index)" class="h-6 w-6 cursor-pointer"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </div>
            </div>
          </div>

          <!-- make it obvious this is a dropdown -->
          <div class="cursor-pointer text-gray-300 w-8 py-1 pl-2 pr-1 border-l flex items-center border-gray-200">
            <button type="button" class="w-6 h-6 text-gray-600 outline-none focus:outline-none" dusk="select-dropdown">
              <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                <path d="M17.418,6.109c0.272-0.268,0.709-0.268,0.979,0s0.271,0.701,0,0.969l-7.908,7.83
                          c-0.27,0.268-0.707,0.268-0.979,0l-7.908-7.83c-0.27-0.268-0.27-0.701,0-0.969c0.271-0.268,0.709-0.268,0.979,0L10,13.25 L17.418,6.109z"
                />
              </svg>
            </button>
          </div>
        </div>
      </template>

      <template #content>
        <div v-for="(option, index) in options" :key="index" class="cursor-pointer border-gray-100 rounded-t border-b hover:bg-yellow-100">
            <div :id="'select-'+index"  @click="select(index)"
                 :class="{'border-orange-800 ': index in selected}"
                 class="flex space-x-2 items-center p-2 border-transparent border-l-4">
              <span class="mx-2 leading-6">
                {{ option instanceof String ? option : option.text }}
              </span>
          </div>
        </div>
      </template>
    </jet-dropdown>

  </div>
</template>

<script>
import JetDropdown from "@src/Jetstream/Dropdown";

export default {

  components: {
    JetDropdown
  },

  props: {
    selectedOnInit: {
      type: Object,
      default: () => {return {}},
    },
    options: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      selected: {},
    }
  },

  created() {
    this.selected = {...this.selectedOnInit}; // deep clone
  },

  methods: {
    select(key) {
      if (key in this.selected) {
        this.remove(key);
      } else {
        this.selected[key] = this.options[key];
        this.$forceUpdate();
      }
    },

    remove(key) {
      delete this.selected[key];
      this.$forceUpdate();
    },
  },

  watch: {
    selectedOnInit(values) {
      this.selected = {...values}; // deep clone
    }
  }
}
</script>
