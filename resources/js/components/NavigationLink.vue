<template>

    <router-link
        :to="'/' + route"
        v-slot="{ isActive }"
        @click="navigate"
        active-class="!hover:bg-brand-primary !bg-brand-primary"
        :class="[ mini ? 'w-full aspect-square' : 'grid grid-cols-12 px-2 py-3' ]"
        class="relative bg-white hover:bg-brand-secondary focus:outline-none cursor-pointer rounded-md capitalize flex items-center gap-4"
    >
        <div :class="[ mini ? 'w-full h-full grid place-items-center' : 'col-span-2']">
            <slot :active="isActive" />
        </div>

        <div :class="[ mini ? 'hidden' : 'col-span-8']">
            <label :class="[isActive ? 'text-white font-medium' : 'text-black']" class="">{{ label }}</label>
        </div>

        <div :class="[isActive ? 'bg-white' : 'bg-brand-secondary', mini ? 'hidden' : '']" v-if="number" class="absolute w-7 h-7 rounded-full right-2 flex items-center justify-center">
            <p class="text-xs text-brand-primary font-medium">{{ number }}</p>
        </div>

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
        },
        minimize: {
            type: Boolean,
            default: true
        }

    })

    const mini = computed(() => props.minimize)
    const active = computed(() => router)

    function navigate() {
        store.dispatch('toggleNavigation', false)
    }

    onMounted(() => {
    })

</script>

<style scoped>

</style>
