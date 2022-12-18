<template>

  <Cell :name="name" :hide="hide" class="px-4 whitespace-nowrap tabular-nums text-right text-xs font-semibold">
    {{ value.toFixed(2) }}
  </Cell>

</template>

<script setup>

  import Cell from "../Table/Cell.vue"

  defineProps({

    hide: {
      type: Array,
      default: [],
    },
    name: {
      type: String,
      required: true,
    },
    value: {
      type: Number,
      required: true,
    }

  })

</script>

<style scoped>

</style>