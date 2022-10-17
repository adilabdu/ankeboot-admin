<template>

  <th class="px-4 whitespace-nowrap">
    <div  @click="sortable ? sortClick() : ''" class="flex items-center relative" :class="[center ? 'justify-center' : right ? 'justify-end' : 'justify-start', sortable ? 'cursor-pointer' : '', right && sortable ? 'mr-4' : '']">
      <slot />
      <button v-if="sortable" :class="[right ? 'translate-x-full absolute right-0' : '']" class="focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" :class="[descending ? '' : 'rotate-180']" class="transition duration-300 scale-[.4] fill-subtitle"><path d="M11.178 19.569a.998.998 0 0 0 1.644 0l9-13A.999.999 0 0 0 21 5H3a1.002 1.002 0 0 0-.822 1.569l9 13z"></path></svg>
      </button>
    </div>
  </th>

</template>

<script setup>

  import { ref } from "vue"

  const emit = defineEmits([
    'sort'
  ])

  const props = defineProps({
    header: {
      type: String,
      required: true,
    },
    sortable: {
      type: Boolean,
      default: true,
    },
    center: {
      type: Boolean,
      default: false,
    },
    right: {
      type: Boolean,
      default: false,
    }
  })

  const descending = ref(true)

  function sortClick() {
    emit('sort', { header: props.header, descending: descending.value })
    descending.value = !descending.value
  }

</script>

<style scoped>

</style>