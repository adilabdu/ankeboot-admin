<template>

  <div class="flex flex-col gap-4 bg-white rounded-md border border-border-light shadow-sm min-h-[16rem]">

    <div class="leading-none flex justify-between items-center px-6 pt-6">
      <div>
        <h1 class="text-base font-medium">{{ title }}</h1>
        <h2 class="text-subtitle text-xs">{{ subtitle }}</h2>
      </div>

    </div>

    <div class="chart flex flex-row-reverse justify-between grow p-3">

      <template v-for="i in 12">

        <div class="group flex flex-col gap-1 items-center justify-end text-center w-full">

          <div class="h-32 w-2 bg-border-dark rounded-full relative">
            <div v-if="values" :style="'height: ' + values[i-1] + '%'" class="absolute w-full bottom-0 rounded-full bg-brand-primary z-10 animate-grow-up origin-bottom"></div>
          </div>
          <label v-if="values" :class="[values[i-1] === 100 ? 'text-brand-primary' : 'text-subtitle']" class="text-xs capitalize">{{ labels[i-1] }}</label>

        </div>

      </template>

    </div>

  </div>

</template>

<script setup>

  import { computed } from "vue"

  const props = defineProps({
    title: {
      type: String,
      default: 'Monthly Sales'
    },
    subtitle: {
      type: String,
      default: 'Sales made within the previous 12 months'
    },
    data: {
      type: Array,
      default: Object
    }
  })

  const data = computed(() => props.data)
  const labels = computed(() => Object.keys(data.value))
  const values = computed(() => {
      if (props.data) {

          return Object.values(data.value).map((value) => {
              return (value / Math.max(...Object.values(data.value))) * 100
          })

      }

      return null
  })

</script>

<style scoped>

</style>
