<template>

    <div class="grow px-12 pt-8 sm:px-4 xs:px-2 gap-8 flex flex-col w-full max-w-[1920px]">
        <div class="print:hidden px-4 gap-3 -mb-3 bg-white w-fit h-8 flex items-center justify-center border border-border-light shadow-sm rounded-lg">
            <template v-for="(route, index) in routes">
                <RouterLink class="text-brand-primary focus:outline-none" v-if="route === '' && index !== routes.length - 1" :to="{ name: 'Dashboard' }">Home</RouterLink>
                <p v-else class="capitalize">{{ route === '' ? index === routes.length - 1 ? 'Dashboard' : 'Home' : route.replace('-' , ' ') }}</p>
                <p v-if="index + 1 !== routes.length" class="font-semibold text-subtitle">/</p>
            </template>
        </div>
        
        <slot />

    </div>

</template>

<script setup>

    import { computed, ref } from "vue"
    import { useRoute } from "vue-router"

    const routes = computed(() => {

        return useRoute().fullPath.split('/')

    })



</script>

<style scoped>

</style>
