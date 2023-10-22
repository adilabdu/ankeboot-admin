<template>

  <div :class="[navigationOpened ? 'xl:-translate-x-0' : 'xl:-translate-x-full', mini ? 'w-16' : 'min-w-[16rem]']" class="h-full xl:absolute" />

  <nav ref="navigation" :class="[navigationOpened ? 'xl:-translate-x-0' : 'xl:-translate-x-full', mini ? 'w-16' : 'min-w-[16rem]']" class="z-20 fixed top-0 left-0 bottom-0 h-full bg-white border-r border-border-light xl:fixed xl:top-0 xl:left-0 flex flex-col transition-transform duration-300">

      <div class="w-full min-h-[4rem] flex items-center" :class="[mini ? 'justify-center' : 'px-4 justify-between']">

          <div @click="minimizeNavigation" :class="{ 'hidden': mini }" class="leading-none text-left">
              <h1 class="font-semibold text-base text-brand-primary">Ankeboot</h1>
              <h1 class="text-xs text-subtitle">Management System</h1>
          </div>

          <div @click="minimizeNavigation" :class="{ 'hidden': !mini }" class="group flex translate-x-1.5">
              <div class="w-6 h-6 rounded-[0.2rem] bg-brand-primary/50 rotate-45" />
              <div class="w-6 h-6 rounded-[0.2rem] bg-[#E2F7BB]/75 rotate-45 -translate-x-3" />
          </div>

          <svg @click="toggleNavigation" :class="[ mini ? '' : 'xl:inline' ]" class="hidden w-4 h-4 stroke-[2.5] stroke-subtitle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>

      </div>

      <ul class="flex flex-col grow py-2 px-2 overflow-y-auto scrollbar-brand">

          <slot />

      </ul>

  </nav>

</template>

<script setup>

    import store from "../store"
    import { onClickOutside } from "@vueuse/core";
    import { computed, ref } from "vue"

    const props = defineProps({
        minimize: {
            type: Boolean,
            default: true
        }
    })

    const mini = computed(() => props.minimize)

    const navigation = ref(null)
    onClickOutside(navigation, () => {

        if (navigationOpened.value) {
            store.dispatch('toggleNavigation', false)
        }

    })

    const navigationOpened = computed(() => store.state.ui.navigationOpened)

    function toggleNavigation() {
        store.dispatch('toggleNavigation')
    }

    function minimizeNavigation() {
        store.dispatch('toggleNavigationMinimize')
    }

</script>

<style scoped>

</style>
