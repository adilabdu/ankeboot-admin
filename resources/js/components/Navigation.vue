<template>

  <nav class="z-20 h-screen min-w-[16rem] bg-white border-r border-border-light xl:absolute xl:top-0 xl:left-0 flex flex-col">

      <div class="w-full h-[4rem] px-4 flex items-center justify-start gap-2">

          <div class="leading-none text-left">
              <h1 class="font-semibold text-base text-brand-primary">Ankeboot</h1>
              <h1 class="text-xs text-subtitle">Management System</h1>
          </div>

      </div>

      <ul class="flex flex-col grow py-2 px-2">

          <slot />

      </ul>

  </nav>

</template>

<script setup>

</script>

<style scoped>

</style>
