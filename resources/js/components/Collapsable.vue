<template>
  <div
    :class="[collapseDirection === 'down' ? 'flex-col-reverse' : '']"
    class="flex flex-col w-full gap-6"
  >
    <slot v-if="openCollapsable" />

    <div class="flex items-center justify-start w-full gap-2 px-2">
      <button
        :class="[!openCollapsable ? 'bg-brand-secondary' : '']"
        @click="toggleSection"
        @keydown.enter="toggleSection"
        type="button"
        class="whitespace-nowrap text-subtitle w-fit rounded-full px-2 font-medium text-xs justify-start flex items-center gap-1"
      >
        <svg
          class="transition-transform duration-300"
          :class="[
            !openCollapsable
              ? 'fill-brand-primary'
              : collapseDirection === 'down'
                ? 'rotate-90 fill-subtitle'
                : '-rotate-90 fill-subtitle'
          ]"
          width="10"
          height="7.5"
          viewBox="0 0 15 20"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M0.536 19.886C0.697765 19.9702 0.879391 20.0087 1.06138 19.9974C1.24337 19.9861 1.41886 19.9255 1.569 19.822L14.569 10.822C14.7018 10.73 14.8104 10.6071 14.8854 10.464C14.9603 10.3208 14.9995 10.1616 14.9995 10C14.9995 9.83843 14.9603 9.67923 14.8854 9.53607C14.8104 9.39291 14.7018 9.27006 14.569 9.17803L1.569 0.178029C1.41897 0.0742216 1.24341 0.013434 1.06131 0.00224798C0.879219 -0.00893801 0.697533 0.029904 0.53593 0.114568C0.374326 0.199232 0.238963 0.326492 0.144497 0.482569C0.0500308 0.638645 6.15833e-05 0.817591 1.35831e-08 1.00003V19C-3.00674e-05 19.1826 0.0499031 19.3616 0.144387 19.5178C0.238871 19.674 0.374302 19.8013 0.536 19.886Z"
          />
        </svg>
        <span class="text-brand-primary" v-if="!openCollapsable">{{ label[0] }}</span>
        <span v-if="openCollapsable">{{ label[1] }}</span>
      </button>
      <div class="border-b border-border-light w-full py-1 mb-2 w-full m-auto"></div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  label: {
    type: Array,
    default: ['Open section', 'Close section']
  },
  open: {
    type: Boolean,
    default: false
  },
  collapseDirection: {
    type: String,
    default: 'up'
  }
})
const emit = defineEmits(['open'])

const openCollapsable = ref(props.open)
function toggleSection() {
  openCollapsable.value = !openCollapsable.value
  emit('open', openCollapsable.value)
}
</script>

<style scoped></style>
