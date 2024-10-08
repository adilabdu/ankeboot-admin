<template>
  <div class="flex flex-col bg-white rounded-md border border-border-light shadow-sm min-h-[16rem]">
    <div class="leading-none flex justify-between items-center px-6 pt-6">
      <div>
        <h1 class="text-base font-medium">{{ title }}</h1>
        <h2 class="text-subtitle text-xs">{{ subtitle }}</h2>
      </div>
    </div>

    <div ref="canvasWrapper" class="grow w-full p-4 flex items-center justify-start gap-5 sm:gap-0">
      <div class="grid place-items-center relative w-[150px] h-[150px] chart">
        <svg class="w-[150px] h-[150px]">
          <circle class="fill-border-dark" cx="75" cy="75" r="70"></circle>
          <path
            class="fill-brand-primary"
            :d="`M75,75 L75,5 A70,70 1 0,1 ${75 + 70 * Math.cos(74)},${75 + 70 * Math.sin(74)} z`"
          ></path>
        </svg>
      </div>

      <div class="grow h-full p-4 flex flex-col gap-4">
        <div class="sm:hidden flex gap-3">
          <div class="min-w-[1rem] h-4 mt-1 rounded-md bg-border-dark" />

          <p class="text-subtitle text-xs">{{ legends['first'] }}</p>
        </div>

        <div class="flex gap-3">
          <div class="min-w-[1rem] h-4 mt-1 rounded-md bg-brand-primary" />

          <p class="text-subtitle text-xs">{{ legends['second'] }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { delay } from '../../utils'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  subtitle: {
    type: String,
    required: true
  },
  ratio: {
    type: Number,
    required: true
  },
  loading: {
    type: Boolean,
    default: true
  },
  legends: {
    type: Object,
    required: true
  }
})

const data = computed(() => props.ratio)
const fetching = computed(() => props.loading)
const endAngle = ref(0)

const start = ref(0)
const startPercent = computed(() => start.value + '%')
const secondPercent = computed(() => 100 - start.value + '%')
function riseToNumber(end) {
  delay(10).then(() => {
    if (start.value < end) {
      start.value += 1
      riseToNumber(end, start)
    }
  })
}

riseToNumber(26)
</script>

<style scoped>
.pie {
  background-image: linear-gradient(0deg, transparent 50%, green 50%),
    linear-gradient(90deg, green 50%, transparent 50%);
}
</style>
