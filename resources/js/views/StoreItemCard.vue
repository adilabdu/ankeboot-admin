<template>
  <RouterLink
    class="hover:shadow-md duration-150 transition-shadow shadow-sm flex flex-col items-center justify-center gap-4 h-64 bg-white rounded-xl border-border-light p-4 sm:max-w-[248px]"
    :to="`stores/${store['id']}`"
  >
    <div
      class="shade h-14 w-14 rounded-full grid place-items-center"
      :class="[store['primary'] === 1 ? 'icon-primary' : 'icon-warehouse']"
    >
      <div class="icon-bg h-12 w-12 rounded-full grid place-items-center">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          class="w-6 h-6"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"
          />
        </svg>
      </div>
    </div>
    <div class="flex flex-col items-center justify-center gap-1">
      <h3 class="font-medium">{{ store['name'] }}</h3>
      <div class="flex flex-col items-center justify-center">
        <p v-if="store['address'] === null" class="text-subtitle/50 text-center">
          Update the store address in Store details page
        </p>
        <template v-else>
          <p class="text-subtitle text-center">
            {{ store['address']['city'] }},
            {{ store['address']['sub_city'] }}
          </p>
          <p class="text-subtitle text-center">
            {{ store['address']['kebele'] }}, House No.
            {{ store['address']['house_number'] }}
          </p>
        </template>
      </div>
    </div>
    <label
      class="text-xs px-2 font-medium rounded-full"
      :class="[store['primary'] === 1 ? 'primary' : 'warehouse']"
    >
      {{ store['primary'] === 1 ? 'Branch' : 'Warehouse' }}
    </label>

    <label class="text-xs text-center grow flex items-end gap-1 leading-snug text-subtitle">
      <span class="text-subtitle/75">Updated</span>
      <span class="font-medium">{{ new Date(store['updated_at']).toLocaleString() }}</span>
    </label>
  </RouterLink>
</template>

<script setup>
const props = defineProps({
  store: {
    type: Object,
    required: true
  }
})
</script>

<style scoped>
.primary {
  @apply bg-green-100 text-green-800;
}

.warehouse {
  @apply bg-yellow-100 text-yellow-800;
}

.icon-primary {
  @apply bg-green-50;
}

.icon-warehouse {
  @apply bg-yellow-50;
}

.icon-primary .icon-bg {
  @apply bg-green-100;
}

.icon-warehouse .icon-bg {
  @apply bg-yellow-100;
}

.icon-primary .icon-bg svg {
  @apply stroke-green-700;
}

.icon-warehouse .icon-bg svg {
  @apply stroke-yellow-700;
}
</style>
