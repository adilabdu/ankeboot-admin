<template>

  <nav ref="navigation" :class="[navigationOpened ? 'xl:-translate-x-0' : 'xl:-translate-x-full']" class="z-20 h-screen min-w-[16rem] bg-white sm:border-t border-r border-border-light xl:absolute xl:top-0 xl:left-0 flex flex-col transition-transform duration-300">

      <div class="w-full min-h-[4rem] px-4 flex items-center justify-between">

          <div class="leading-none text-left">
              <h1 class="font-semibold text-base text-brand-primary">Ankeboot</h1>
              <h1 class="text-xs text-subtitle">Management System</h1>
          </div>

          <svg @click="toggleNavigation" class="hidden xl:inline w-4 h-4 stroke-[2.5] stroke-subtitle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>

      </div>

      <ul class="flex flex-col grow py-2 px-2 overflow-y-auto">

          <slot />

      </ul>

  </nav>

</template>

<script setup>

    import store from "../store"
    import { onClickOutside } from "@vueuse/core";
    import { computed, ref } from "vue"

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

</script>

<style scoped>

</style>
