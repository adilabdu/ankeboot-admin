<template>

  <div :class="[size === 'sm' ? 'min-h-[2.5rem]' : 'min-h-[3rem]']" class="border-b border-border-light last:border-none group items-center list-row grid grid-cols-12 relative">

    <label :class="[size === 'sm' ? 'col-span-5' : label_size]" class="px-8 text-subtitle font-medium capitalize">
      {{ label }}
    </label>

    <div :class="[size === 'sm' ? 'col-span-7' : description_size]" class="relative">
      <p ref="text">
        <slot>
          <span v-if="!description" class="text-black/25">
            Pass the description as a <span class="font-mono">`&lt;slot&gt;`</span>
          </span>
          <template v-else>
            {{ description }}
          </template>
        </slot>
      </p>
    </div>

    <svg @click="copyText" :class="[clicked ? 'fill-white bg-subtitle opacity-100' : 'fill-subtitle bg-white']" class="copy-button transition-all duration-300 cursor-copy opacity-0 group-hover:opacity-100 transition-opacity duration-150 m-1 p-2 absolute right-0 rounded-md shadow-sm border-border-dark border-[0.5px] top-0" width="32" height="32" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
      <path d="M18 0H8C6.897 0 6 0.897 6 2V6H2C0.897 6 0 6.897 0 8V18C0 19.103 0.897 20 2 20H12C13.103 20 14 19.103 14 18V14H18C19.103 14 20 13.103 20 12V2C20 0.897 19.103 0 18 0ZM2 18V8H12L12.002 18H2ZM18 12H14V8C14 6.897 13.103 6 12 6H8V2H18V12Z" />
      <path d="M4 10H10V12H4V10ZM4 14H10V16H4V14Z" />
    </svg>

  </div>

</template>

<script setup>

  import { ref, onMounted } from "vue";

  const props = defineProps({
    label: {
      type: String,
      default: 'index'
    },
    description: {
      type: String,
      default: null
    },
    size: {
      type: String,
      default: 'md',
    },
    ratio: {
        type: String,
        default: '1:2'
    }
  })

  const text = ref(null)
  const clicked = ref(false)

  function copyText() {

    clicked.value = true
    navigator.clipboard.writeText(text.value.innerText).then().catch().finally(() => {
      setTimeout(() => {
        clicked.value = false
      }, 500)
    })

  }

  const label_size = ref(null)
  const description_size = ref(null)
  onMounted(() => {

      switch(props.ratio) {

          case '1:1':

              label_size.value = 'col-span-6'
              description_size.value = 'col-span-6'

              break;

          case '5:7':

              label_size.value = 'col-span-5'
              description_size.value = 'col-span-7'

              break;

          case '1:2':
          default:

              label_size.value = 'col-span-4'
              description_size.value = 'col-span-8'

              break;

      }

  })

</script>

<style scoped>

  .list-row:nth-child(odd) {
    @apply bg-border-light/25;
  }

</style>
