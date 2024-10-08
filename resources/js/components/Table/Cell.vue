<template>
  <td
    :class="[main ? 'text-black' : 'text-subtitle']"
    v-if="!hide.includes(name)"
    class="px-4 whitespace-nowrap"
  >
    <slot />
  </td>
</template>

<script setup>
defineProps({
  hide: {
    type: Array,
    default: []
  },
  name: {
    type: String,
    required: true
  },
  main: {
    type: Boolean,
    default: false
  }
})
</script>

<style scoped></style>
