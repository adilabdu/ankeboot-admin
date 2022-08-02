<template>

    <div class="flex items-center w-full gap-4">
        <label class="text-subtitle font-medium">{{ label }}</label>
        <div tabindex="0" @keydown.enter="$emit('update:modelValue', toggleRadio())" @click="$emit('update:modelValue', toggleRadio())" :class="[radioState ? 'bg-brand-primary border-brand-primary' : 'bg-wallpaper border-border-dark']" class="transition-colors duration-500 focus:outline-brand-primary focus:outline focus:outline-2 focus:outline-offset-2 cursor-pointer h-6 w-12 flex rounded-full relative border items-center">
            <div :class="[radioState ? 'translate-x-full border-white' : '-translate-x-0.5 border-border-dark']" class="bg-white m-1 transition-all duration-500 w-5 h-5 border rounded-full"></div>
        </div>
    </div>

</template>

<script setup>

    import { computed } from "vue";

    const props = defineProps({
        label: {
            type: String,
            required: true,
        },
        modelValue: {
            type: Boolean
        }
    })
    defineEmits(['update:modelValue'])

    const radioState = computed(() => props.modelValue)

    function toggleRadio() {

        return !radioState.value;

    }

</script>

<style scoped>

</style>
