<template>

    <div :class="[ appearFrom === 'bottom' ? 'sm:items-end' : 'sm:items-start' ]" class="scrollbar-brand w-full h-full overflow-y-auto py-12 sm:py-0 sm:overflow-hidden fixed bg-black/80 flex items-start justify-center z-[100]">

        <slot />

    </div>

</template>

<script setup>

    import { onBeforeUnmount, onMounted } from "vue";

    const props = defineProps({
        appearFrom: {
            type: String,
            default: 'bottom'
        }
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
