<template>

  <div class="min-w-[275px] flex flex-col gap-0.5 justify-start p-6 sm:w-full h-28 rounded-2xl bg-white border-[0.5px] border-border-light drop-shadow-sm">

    <label class="font-semibold text-subtitle uppercase text-xs">{{ label }}</label>
    <div class="flex gap-2 items-center justify-start">
        <div class="text-3xl font-medium flex gap-2 items-center w-full">
          <slot>
            {{ content }}
          </slot>
        </div>
    </div>
    <p class="text-subtitle">{{ footnote }}</p>

  </div>

</template>

<script setup>

  defineProps({
    label: {
      type: String,
      required: true,
    },
    content: {
      type: String,
      default: '',
    },
    footnote: {
      type: String,
      default: '',
    }
  })

</script>

<style scoped>

</style>
