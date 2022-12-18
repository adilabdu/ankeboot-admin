<template>

    <router-link
        :to="'/' + route"
        v-slot="{ isActive }"
        @click="navigate"
        active-class="!hover:bg-brand-primary !bg-brand-primary"
        class="relative bg-white hover:bg-brand-secondary focus:outline-none cursor-pointer grid grid-cols-12 px-2 py-3 rounded-md capitalize flex items-center gap-4"
    >

        <div class="col-span-2">
            <slot :active="isActive" />
        </div>

        <div class="col-span-8">
            <label :class="[isActive ? 'text-white font-medium' : 'text-black']" class="">{{ label }}</label>
        </div>

        <div :class="[isActive ? 'bg-white' : 'bg-brand-secondary']" v-if="number" class="absolute w-7 h-7 rounded-full right-2 flex items-center justify-center">
            <p class="text-xs text-brand-primary font-medium">{{ number }}</p>
        </div>

<!--        <div class="flex items-center justify-center rounded-full w-3/4 aspect-square bg-white col-span-2">-->
<!--            <p class="text-xs text-brand-primary">3</p>-->
<!--        </div>-->

    </router-link>

</template>

<script setup>

    import {computed, onMounted} from "vue";
    import router from "../router"
    import store from "../store"

    const props = defineProps({

        label: {
            type: String,
            required: true,
        },
        route: {
            type: String,
            default: (props) => props.label
        },
        number: {
            type: [Number, String]
        }

    })

    const active = computed(() => router)

    function navigate() {
        store.dispatch('toggleNavigation', false)
    }

    onMounted(() => {
    })

</script>

<style scoped>

</style>
