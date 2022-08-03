<template>

  <!-- Dropdown List -->
  <div ref="dropdown" class="sm:w-full w-fit flex gap-4 items-center justify-end">

    <div tabindex="0" class="flex flex-col items-end gap-2 relative sm:w-full rounded-md">

      <div @keyup.enter="toggleHideList" @click="toggleHideList" class="border-[0.5px] border-border-light rounded-md sm:w-full w-fit bg-white h-10 shadow-sm flex items-center justify-between">

        <p class="px-3">{{ list[item] }}</p>

        <div class="flex items-center justify-center h-full pr-3 gap-1 cursor-pointer">
          <div class="h-full w-1 blur-lg bg-white" />
          <svg width="12" height="8" viewBox="0 0 12 8" class="fill-subtitle rotate-180" xmlns="http://www.w3.org/2000/svg">
            <path d="M0.292969 6.29306L1.70697 7.70706L5.99997 3.41406L10.293 7.70706L11.707 6.29306L5.99997 0.58606L0.292969 6.29306Z" />
          </svg>
        </div>

      </div>

      <!-- List of all Columns -->
      <ul :class="[showList ? '' : 'hidden', dropDirection === 'up' ? '-mt-2 -translate-y-full' : 'mt-12']" class="z-50 overflow-auto absolute max-h-72 w-full bg-white shadow-md rounded-md border-[0.5px] border-border-light">
        <li @click="setChoice(index)" :class="[item === index ? 'bg-brand-secondary text-brand-primary' : 'hover:bg-brand-secondary hover:text-brand']" class="flex items-center justify-between gap-2 px-4 group cursor-pointer py-2" v-for="(name, index) in list">
          <p>{{ name }}</p>
        </li>
      </ul>
    </div>

    <label v-if="label" class="xs:hidden text-subtitle font-medium whitespace-nowrap">{{ label }}</label>

  </div>

</template>

<script setup>

import { ref } from "vue";
import { onClickOutside } from "@vueuse/core";

const props = defineProps({
  choice: {
    type: Number,
    default: 1,
  },
  list: {
    type: Array,
    default: [ 5, 10, 25, 'All' ]
  },
  dropDirection: {
    type: String,
    default: 'down',
  },
  label: {
    type: String,
    default: '',
  }
})

const emit = defineEmits([
  'change'
])

const showList = ref(false)

function toggleHideList() {
  showList.value = !showList.value
}

const item = ref(props.choice)

function setChoice(index) {
  item.value = index
  toggleHideList()
  emit('change', props.list[index])
}

const dropdown = ref(null)
onClickOutside(dropdown, (event) => {
  if (showList.value) {
    toggleHideList()
  }
})

</script>

<style scoped>

</style>
