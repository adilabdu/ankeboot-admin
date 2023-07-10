<template>

    <Teleport v-if="show" to="#top-view">

        <div :class="[ appearFrom === 'bottom' ? 'sm:items-end' : 'sm:items-start' ]" class="scrollbar-brand w-full h-screen overflow-y-auto py-12 sm:py-0 sm:overflow-hidden fixed bg-black/80 flex items-start justify-center z-[100]">

            <div ref="modalContent" class="sm:animate-slide-up-modal animate-scale-up w-fit" :class="[ appearFrom === 'bottom' ? 'sm:items-end' : 'sm:items-start' ]">
                <slot />
            </div>

        </div>

    </Teleport>

</template>

<script setup>

import {onBeforeUnmount, onMounted, ref} from "vue";
import {onClickOutside} from "@vueuse/core";

const props = defineProps({
    appearFrom: {
        type: String,
        default: 'bottom'
    },
    show: {
        type: Boolean,
        default: false
    }
})

const emits = defineEmits(['update:show'])

const modalContent = ref(null)
onClickOutside(modalContent, () => {
    emits('update:show', false)
})

onMounted(() => {
    document.getElementById("contentPage").style.overflow = "hidden";
})

onBeforeUnmount(() => {
    document.getElementById("contentPage").style.overflow = "auto";
})

</script>

<style scoped>

</style>
