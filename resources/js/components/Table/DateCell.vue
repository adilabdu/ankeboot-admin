<template>

    <td :class="[ main ? 'text-black' : 'text-subtitle' ]" v-if="!hide.includes(name)" class="px-4 whitespace-nowrap">
      {{ value.toLocaleDateString() }}
    </td>

</template>

<script setup>

  defineProps({

    hide: {
      type: Array,
      default: [],
    },
    name: {
      type: String,
      required: true,
    },
    main: {
      type: Boolean,
      default: false,
    },
    value: {
      type: Object,
      required: true,
    },

  })

</script>

<style scoped>

</style>