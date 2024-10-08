<script setup>
import { onMounted, ref } from 'vue'
import Cell from './Cell.vue'

const props = defineProps({
  to: {
    type: String,
    default: ''
  },
  newTab: {
    type: Boolean,
    default: false
  },
  name: {
    type: String,
    required: true
  },
  hide: {
    type: Array,
    default: []
  },
  value: {
    type: String,
    required: true
  }
})

const anchor = ref(null)
const href = ref(props.to)

onMounted(() => {
  if (href.value === '') {
    href.value = anchor.value.innerHTML
  }
})
</script>

<template>
  <Cell :name="name" :hide="hide" class="hover:underline hover:text-brand-primary">
    <slot />
  </Cell>
</template>

<style scoped></style>
