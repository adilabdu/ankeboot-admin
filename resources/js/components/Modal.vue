<template>

    <div class="modal w-full h-screen overflow-y-auto py-12 fixed bg-black/80 flex items-start justify-center z-[100]">

        <slot />

    </div>

</template>

<script setup>

</script>

<style scoped>

    .modal::-webkit-scrollbar {
        width: 16px;
    }
    .modal::-webkit-scrollbar-corner {
        background: rgba(0,0,0,0);
    }
    .modal::-webkit-scrollbar-thumb {
        background-color: rgba(116,116,116,1);
        border-radius: 6px;
        border: 4px solid rgba(0,0,0,0);
        background-clip: content-box;
        transition: all;
        transition-duration: 1s;
    }

    .modal::-webkit-scrollbar-track {
        background-color: rgba(0,0,0,0);
    }

    .modal:hover .modal::-webkit-scrollbar-thumb {
        background-color: rgba(116,116,116,1) !important;
    }

</style>
