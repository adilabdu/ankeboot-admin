<template>

  <!--  TODO: remove min-w-[16rem] or not?-->
  <nav :class="[navigationOpened ? 'xl:-translate-x-0' : 'xl:-translate-x-full']" class="z-20 h-screen min-w-[16rem] bg-white border-r border-border-light xl:absolute xl:top-0 xl:left-0 flex flex-col transition-transform duration-300">

      <div class="w-full h-[4rem] px-4 flex items-center justify-between">

          <div class="leading-none text-left">
              <h1 class="font-semibold text-base text-brand-primary">Ankeboot</h1>
              <h1 class="text-xs text-subtitle">Management System</h1>
          </div>

          <svg @click="toggleNavigation" class="hidden xl:inline" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M2.70902 0.505398L0.163434 3.05098L5.2546 8.14215L0.163434 13.2333L2.70902 15.7789L7.80019 10.6877L12.8914 15.7789L15.4369 13.2333L10.3458 8.14215L15.4369 3.05098L12.8914 0.505398L7.80019 5.59657L2.70902 0.505398Z" fill="#6A727F"/>
          </svg>

      </div>

      <ul class="flex flex-col grow py-2 px-2">

          <slot />

      </ul>

  </nav>

</template>

<script setup>

    import store from "../store"
    import { computed } from "vue"

    const navigationOpened = computed(() => store.state.ui.navigationOpened)

    function toggleNavigation() {
        store.dispatch('toggleNavigation')
    }

</script>

<style scoped>

</style>
