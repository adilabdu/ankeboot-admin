<template>

    <transition name="slide-fade">
        <div v-if="display" :class="[type]" class="animate-slide-in-from-left relative flex items-center justify-center w-fit min-w-[32rem] py-2 px-8 shadow-md border min-h-[3rem] rounded-md">
            <p class="font-medium text-center">{{ message }}</p>
            <svg @click="closeAlert" width="10" height="10" viewBox="0 0 12 12" class="absolute right-4 top-0 bottom-0 my-auto" xmlns="http://www.w3.org/2000/svg">
                <path d="M10.192 0.343994L5.94903 4.58599L1.70703 0.343994L0.29303 1.75799L4.53503 5.99999L0.29303 10.242L1.70703 11.656L5.94903 7.41399L10.192 11.656L11.606 10.242L7.36403 5.99999L11.606 1.75799L10.192 0.343994Z" />
            </svg>
        </div>
    </transition>


</template>

<script setup>

    import { ref, watch } from "vue";
    import store from "../store";

    const props = defineProps({
        type: {
            type: String,
            default: 'default'
        },
        message: {
            type: String,
            required: true
        },
        persist: {
            type: Boolean,
            default: false
        }
    })

    function closeAlert() {

        // TODO: remove alert message from alert stack

    }

    const display = ref(true)
    setTimeout(() => {
        display.value = false
    }, 5000)

    watch(display, () => {

        setTimeout(() => {
            store.dispatch('popAlert')
        }, 5010)

    })

</script>

<style scoped>

    .default {
        @apply border-brand-primary bg-white text-brand-primary fill-brand-primary;
    }

    .success {
        @apply border-green-700 bg-white text-green-700 fill-green-700;
    }

    .info {
        @apply border-blue-700 bg-white text-blue-700 fill-blue-700;
    }

    .error {
        @apply border-red-500 bg-white text-red-500 fill-red-500;
    }

    .cancel {
        @apply border-black bg-white text-black fill-black;
    }

    .slide-fade-enter-active {
        transition: all .3s ease;
    }
    .slide-fade-leave-active {
        transition: all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .slide-fade-enter, .slide-fade-leave-to
        /* .slide-fade-leave-active below version 2.1.8 */ {
        transform: translateX(10px);
        opacity: 0;
    }

</style>
