<template>

  <div class="bg-white w-full rounded-md border-[0.5px] border-border-light shadow-sm flex flex-col">

    <div :class="[headingSize === 'sm' ? 'py-2' : 'py-6']" v-if="heading" class="flex md:flex-col gap-4 justify-between px-8 shadow-sm">

      <div class="flex flex-col">
        <h1 :class="[headingSize === 'sm' ? 'text-base' : 'text-base']" class="font-medium">{{ title }}</h1>
        <h2 class="text-subtitle text-xs">{{ subtitle }}</h2>
      </div>

      <slot name="action">
        <button class="focus:outline-brand-primary sm:focus:outline-none focus:outline-offset-2 h-fit md:w-fit px-6 py-2.5 bg-brand-primary rounded-lg text-white flex justify-between sm:justify-center items-center gap-2 sm:w-full">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="fill-white"><path d="M18.948 11.112C18.511 7.67 15.563 5 12.004 5c-2.756 0-5.15 1.611-6.243 4.15-2.148.642-3.757 2.67-3.757 4.85 0 2.757 2.243 5 5 5h1v-2h-1c-1.654 0-3-1.346-3-3 0-1.404 1.199-2.757 2.673-3.016l.581-.102.192-.558C8.153 8.273 9.898 7 12.004 7c2.757 0 5 2.243 5 5v1h1c1.103 0 2 .897 2 2s-.897 2-2 2h-2v2h2c2.206 0 4-1.794 4-4a4.008 4.008 0 0 0-3.056-3.888z"></path><path d="M13.004 14v-4h-2v4h-3l4 5 4-5z"></path></svg>
          <span class="font-medium whitespace-nowrap">
          Download Stock Record
        </span>
        </button>
      </slot>

    </div>

    <div :class="[headingSize === 'sm' ? 'hidden' : '']" v-if="heading" class="w-full h-0 border-border-dark border-t"></div>


      <slot name="rows">
        <ItemDescription label="Placeholder" />
        <ItemDescription label="Placeholder" />
        <ItemDescription label="Placeholder" />
      </slot>

  </div>

</template>

<script setup>

  import ItemDescription from "./ItemDescription.vue";

  defineProps({

    heading: {
      type: Boolean,
      default: true,
    },
    headingSize: {
      type: String,
      default: 'md',
    },
    title: {
      type: String,
      required: true
    },
    subtitle: {
      type: String,
      default: "Item Information and Stock Record"
    },

  })

</script>

<style scoped>

</style>
