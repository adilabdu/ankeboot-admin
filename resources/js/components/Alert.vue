<template>

    <div ref="alertBox" :class="[type, persist ? 'animate-slide-in-from-left' : 'animate-slide-in']" class="relative flex items-center justify-center w-96 py-2 px-4 shadow-md border min-h-[3rem] rounded-md">
        <p class="font-medium text-center">{{ message }}</p>
        <svg @click="closeAlert" width="10" height="10" viewBox="0 0 12 12" class="absolute right-0 top-0 m-2.5" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.192 0.343994L5.94903 4.58599L1.70703 0.343994L0.29303 1.75799L4.53503 5.99999L0.29303 10.242L1.70703 11.656L5.94903 7.41399L10.192 11.656L11.606 10.242L7.36403 5.99999L11.606 1.75799L10.192 0.343994Z" />
        </svg>
    </div>

</template>

<script setup>

    import { onBeforeUnmount, ref } from "vue";

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

    const alertBox = ref(null)
    onBeforeUnmount(() => {

        if (props.persist) {
            alertBox.value.classList.add('animate-slide-in')
        } else {
            alertBox.value.classList.add('animate-slide-out')
        }
    })

</script>

<style scoped>

    .default {
        @apply border-brand-primary bg-[#FFEFDF] text-brand-primary fill-brand-primary;
    }

    .success {
        @apply border-green-700 bg-green-100 text-green-700 fill-green-700;
    }

    .error {
        @apply border-red-700 bg-red-100 text-red-700 fill-red-700;
    }

    .cancel {
        @apply border-black bg-stone-200 text-black fill-black;
    }

</style>
