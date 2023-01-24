<template>

    <div class="scrollbar-brand w-full h-screen overflow-y-auto py-12 sm:py-0 sm:overflow-hidden fixed bg-black/80 flex items-start sm:items-end justify-center z-[100]">

        <slot />

    </div>

</template>

<script setup>

    import { onBeforeUnmount, onMounted } from "vue";

    onMounted(() => {
        document.getElementById("contentPage").style.overflow = "hidden";
    })

    onBeforeUnmount(() => {
        document.getElementById("contentPage").style.overflow = "auto";
    })

</script>

<style scoped>

</style>
