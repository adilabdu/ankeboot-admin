<template>

    <router-link
        :to="route || label"
        v-slot="{ isActive }"
        @click="navigate"
        active-class="!hover:bg-brand-primary !bg-brand-primary"
        class="bg-white hover:bg-brand-secondary focus:outline-none cursor-pointer grid grid-cols-12 px-2 py-3 rounded-md capitalize flex items-center gap-4"
    >

        <div class="col-span-2">
            <slot :active="isActive" />
        </div>

        <div class="col-span-8">
            <label :class="[isActive ? 'text-white font-medium' : 'text-black']" class="">{{ label }}</label>
        </div>

    </router-link>

</template>

<script setup>

    import {computed, onMounted} from "vue";
    import router from "../router"

    const props = defineProps({

        label: {
            type: String,
            required: true,
        },
        route: {
            type: String,
            default: null
        }

    })

    const active = computed(() => router)

    function navigate() {

    }

    onMounted(() => {
        console.log('has route james? ', active.value)
    })

</script>

<style scoped>

</style>
