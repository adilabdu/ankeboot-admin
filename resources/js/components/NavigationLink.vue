<template>

    <li
        @click="navigate"
        :class="[isActive ? 'bg-brand-primary' : 'bg-white hover:bg-brand-secondary']"
        class="cursor-pointer grid grid-cols-12 px-2 py-3 rounded-md capitalize flex items-center gap-4"
    >

        <div class="col-span-2">
            <slot :active="isActive" />
        </div>

        <div class="col-span-8">
            <label :class="[isActive ? 'text-white font-medium' : 'text-black']" class="">{{ label }}</label>
        </div>

    </li>

</template>

<script setup>

    import { computed } from "vue";

    const props = defineProps({

        label: {
            type: String,
            required: true,
        }

    })

    const isActive = computed(() => props.label === 'books')

    function navigate() {

    }

</script>

<style scoped>

</style>
