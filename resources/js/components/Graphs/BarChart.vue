<template>

  <div class="flex flex-col gap-4 bg-white rounded-md border border-border-light shadow-sm">

    <div class="leading-none flex justify-between items-center px-6 pt-6">
      <div>
        <h1 class="text-base font-medium">{{ title }}</h1>
        <h2 class="text-subtitle text-xs">{{ subtitle }}</h2>
      </div>

    </div>

    <div class="chart grid grid-cols-12 grow p-3 ">

      <template v-for="i in data">

        <div class="group flex flex-col gap-1 items-center justify-end text-center w-10">

          <div class="h-32 w-[20%] bg-border-dark rounded-full relative">
            <div :style="'height: ' + i[2] + '%'" class="absolute w-full bottom-0 rounded-full bg-brand-primary z-10 animate-grow-up origin-bottom"></div>
          </div>
          <label :class="[i[2] === 100 ? 'text-brand-primary' : 'text-subtitle']" class="text-xs">{{ i[0] }}</label>

        </div>

      </template>

    </div>

  </div>

</template>

<script setup>

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
      default: [['Jan', 6909, 54], ['Feb', 5900, 47], ['Mar', 7110, 57], ['Apr', 8322, 67], ['May', 7221, 59], ['Jun', 8122, 58], ['Jul', 7210, 55], ['Aug', 9101, 74], ['Sep', 10111, 82], ['Oct', 12307, 100], ['Nov', 11721, 95], ['Dec', 9122, 74]]
    }
  })

</script>

<style scoped>

</style>
