<template>

  <!-- Dropdown List -->
  <div
      ref="dropdown"
      :class="[
          labelDirection === 'right' ?
          'gap-4 items-center justify-end' :
          labelDirection === 'top' ?
          'flex-col flex-col-reverse gap-1' :
          ''
      ]"
      class="sm:w-full w-fit flex h-10"
  >

    <div tabindex="0" class="flex flex-col items-end gap-2 relative sm:w-full rounded-md h-full w-full">

      <div @keyup.enter="toggleHideList" @click="toggleHideList" class="border-[0.5px] border-border-light rounded-md sm:w-full w-full bg-white h-full shadow-sm flex items-center justify-between">

        <p v-if="modelValue" class="px-3">{{ modelValue }}</p>
        <p v-else class="px-3 text-[#9CA3AF]">{{ placeholder }}</p>

        <div class="flex items-center justify-center h-full pr-3 gap-1 cursor-pointer">
          <div class="h-full w-1 blur-lg bg-white" />
          <svg width="12" height="8" viewBox="0 0 12 8" class="fill-subtitle rotate-180" xmlns="http://www.w3.org/2000/svg">
            <path d="M0.292969 6.29306L1.70697 7.70706L5.99997 3.41406L10.293 7.70706L11.707 6.29306L5.99997 0.58606L0.292969 6.29306Z" />
          </svg>
        </div>

      </div>

      <!-- List of all Columns -->
      <ul :class="[showList ? '' : 'hidden', dropDirection === 'up' ? '-mt-2 -translate-y-full' : 'mt-12']" class="z-50 overflow-auto absolute max-h-72 w-full bg-white shadow-md rounded-md border-[0.5px] border-border-light">
        <li @click="setChoice(index)" :class="[modelValue && modelValue === list[index] ? 'bg-brand-secondary text-brand-primary' : 'hover:bg-brand-secondary hover:text-brand']" class="whitespace-nowrap flex items-center justify-between gap-2 px-4 group cursor-pointer py-2" v-for="(name, index) in list">
          <p>{{ name }}</p>
        </li>
      </ul>
    </div>

    <label v-if="label" :class="[hideLabelOnMobile ? 'xs:hidden' : '']" class="text-subtitle font-medium whitespace-nowrap">
        {{ label }}
        <span v-if="required" class="text-red-600">*</span>
    </label>

  </div>

</template>

<script setup>

import { ref } from "vue";
import { onClickOutside } from "@vueuse/core";

const props = defineProps({
  modelValue: {
    type: [String, Number]
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
  },
  labelDirection: {
      type: String,
      default: 'right'
  },
  placeholder: {
      type: String,
      default: null
  },
  required: {
      type: Boolean,
      default: false
  },
  hideLabelOnMobile: {
      type: Boolean,
      default: true,
  }
})

const emit = defineEmits(['update:modelValue'])

const showList = ref(false)

function toggleHideList() {
  showList.value = !showList.value
}

function setChoice(index) {
  toggleHideList()
  emit('update:modelValue', props.list[index])
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
