<template>

  <!-- Dropdown List -->
  <div ref="dropdown" class="sm:w-full w-fit flex gap-4 items-center justify-end">

    <label class="sm:hidden text-subtitle font-medium">{{ label }}</label>

    <div class="flex flex-col items-end gap-2 relative sm:w-full">

      <div class="border-[0.5px] border-border-light rounded-md sm:w-full w-64 bg-white h-10 shadow-sm flex items-center justify-between">

        <!-- Hidden Columns Labels -->
        <div @click="choices.length < 1 ? toggleHideList() : console.log('name')" :class="[choices.length < 1 ? 'cursor-pointer' : '']" class="custom-scroll overflow-auto px-3 flex gap-2 items-center justify-start">
          <div v-for="name in items" class="px-3 bg-brand-secondary text-brand-primary rounded-xl flex gap-2 items-center justify-between">
            <p class="whitespace-nowrap text-xs inline">{{ name }}</p>
            <p @click.stop="toggleHide(name)" class="cursor-pointer font-medium rotate-45 text-lg">+</p>
          </div>
          <p v-if="items.length < 1" class="text-subtitle">{{ placeholder }}</p>
        </div>

        <div @click="toggleHideList" class="flex items-center justify-center h-full pr-3 gap-1 cursor-pointer">
          <div class="h-full w-1 blur-lg bg-white" />
          <svg width="12" height="8" viewBox="0 0 12 8" class="fill-subtitle rotate-180" xmlns="http://www.w3.org/2000/svg">
            <path d="M0.292969 6.29306L1.70697 7.70706L5.99997 3.41406L10.293 7.70706L11.707 6.29306L5.99997 0.58606L0.292969 6.29306Z" />
          </svg>
        </div>

      </div>

      <!-- List of all Columns -->
      <ul :class="[showList ? '' : 'hidden', dropDirection === 'up' ? '-mt-2 -translate-y-full' : 'mt-12']" class="z-50 overflow-auto absolute max-h-72 w-64 bg-white shadow-md rounded-md border-[0.5px] border-border-light">
        <li @click="toggleHide(name)" class="flex items-center justify-between px-4 group hover:bg-brand-secondary hover:text-brand-primary cursor-pointer py-2" v-for="name in list">
          <p>{{ name }}</p>
          <svg v-if="items.includes(name)" width="15" height="12" viewBox="0 0 15 12" class="fill-brand-primary" xmlns="http://www.w3.org/2000/svg">
            <path d="M4.707 8.293L1.414 5L0 6.414L4.707 11.121L14.414 1.414L13 0L4.707 8.293Z"/>
          </svg>
        </li>
      </ul>
    </div>

  </div>

</template>

<script setup>

  import { ref } from "vue";
  import { onClickOutside } from "@vueuse/core";

  const props = defineProps({
    choices: {
      type: Array,
      default: [ 'First item' ],
    },
    list: {
      type: Array,
      default: [ 'First item', 'Second item' ]
    },
    placeholder: {
      type: String,
      required: true,
    },
    dropDirection: {
      type: String,
      default: 'down',
    },
    label: {
      type: String,
      required: true,
    }
  })

  const emit = defineEmits([
      'change'
  ])

  const showList = ref(false)

  function toggleHideList() {
    showList.value = !showList.value
  }

  const items = ref(props.choices)

  function toggleHide(name) {

    if (items.value.includes(name)) {
      items.value.splice(items.value.indexOf(name), 1)
    } else {
      items.value.push(name)
    }

    emit('change', items)
  }

  const dropdown = ref(null)
  onClickOutside(dropdown, (event) => {
    if (showList.value) {
      toggleHideList()
    }
  })

</script>

<style scoped>

  /* Hide scrollbar for Chrome, Safari and Opera */
  .custom-scroll::-webkit-scrollbar {
    display: none;
  }

  /* Hide scrollbar for IE, Edge and Firefox */
  .custom-scroll {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
  }

</style>