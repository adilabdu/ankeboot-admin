<template>

  <div ref="dropdown" class="sm:w-full w-fit flex gap-4 items-center justify-end">

    <label class="sm:hidden text-subtitle font-medium">Search</label>

    <div class="relative flex flex-col items-end gap-2 relative sm:w-full">

      <input v-model="query" :placeholder="placeholder" type="text" class="icon-placeholder md:focus:outline-none focus:outline-brand-primary sm:focus:outline-none focus:outline-offset-2 px-3 border-[0.5px] border-border-light rounded-md sm:w-full w-64 bg-white h-10 shadow-sm flex items-center justify-between"/>
<!--      <span class="text-subtitle absolute mt-[0.7rem] left-0 ml-[0.75rem]">-->
<!--        <slot>-->
<!--          {{ placeholder }}-->
<!--        </slot>-->
<!--      </span>-->

    </div>

  </div>

</template>

<script setup>

  import { ref, watch } from "vue"

  const props = defineProps({
    minQueryLength: {
      type: Number,
      default: 3,
    },
    placeholder: {
      type: String,
      default: 'Enter your search query',
    },
  })

  const emit = defineEmits([
      'type'
  ])

  const query = ref(null);

  watch(query, () => {
    if (query.value.length >= props.minQueryLength) {
      emit('type', query.value)
    }
  })

</script>

<style scoped>

  .icon-placeholder:not(:placeholder-shown) ~ span {
    display: none;
  }

</style>
