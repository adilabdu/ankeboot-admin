<script setup>

  import { computed } from "vue";

  import Cell from "./Cell.vue"

  const props = defineProps({
    options: {
      type: Array,
      default: '',
    },
    colors: {
      type: Array,
      default: ['red', 'green']
    },
    value: {
      type: String,
      required: true,
    },
    hide: {
      type: Array,
      default: [],
    },
    name: {
      type: String,
      required: true,
    },
  })

  const badgeColor = computed(() => {
    return props.colors[props.options.indexOf(props.value)];
  })

</script>

<template>

  <Cell :name="name" :hide="hide" class="text-2xs font-semibold text-center whitespace-nowrap">
    <p ref="cell" :class="[badgeColor]" class="inline py-0.5 px-1.5 rounded-md">
      {{ value }}
    </p>
  </Cell>

</template>

<style scoped>

  .blue {
    @apply bg-blue-100 text-blue-900;
  }

  .red {
    @apply bg-red-200 text-red-800;
  }

  .green {
    @apply bg-green-100 text-green-900;
  }

  .yellow {
    @apply bg-yellow-100 text-yellow-900;
  }

  .blue {
    @apply bg-blue-100 text-blue-900;
  }

  .gray {
    @apply bg-gray-100 text-gray-900;
  }

  .black {
    @apply bg-black text-white;
  }

  .stone {
    @apply bg-stone-200 text-stone-700;
  }

  .amber {
    @apply bg-amber-100 text-amber-800;
  }

  .emerald {
    @apply bg-emerald-100 text-emerald-700;
  }

  .lime {
    @apply bg-lime-100 text-lime-700;
  }

  .rose {
    @apply bg-rose-200 text-rose-800;
  }

  .fuchsia {
    @apply bg-fuchsia-200 text-fuchsia-800;
  }

  .pink {
    @apply bg-pink-100 text-pink-900;
  }

</style>
