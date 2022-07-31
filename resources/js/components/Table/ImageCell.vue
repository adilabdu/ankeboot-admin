<script setup>

  defineProps({
    src: {
      type: String,
      default: '',
    },
    size: {
      type: Number,
      default: 25,
    }
  })

</script>

<template>

  <td>
    <img :width="size" :height="size" :src="src" alt="avatar" />
  </td>

</template>

<style scoped>

</style>