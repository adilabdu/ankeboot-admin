<template>

    <div
        :class="[
            labelLocation === 'left' ?
            'items-center gap-4' :
            labelLocation === 'top' ?
            'flex-col items-start gap-1' :
            '',
            isLoading ? 'opacity-50' : ''
        ]" class="flex">
        <label class="text-subtitle font-medium whitespace-nowrap">{{ label }}</label>
        <div :class="[labelLocation === 'top' ? 'h-10 w-full flex justify-start items-center' : '']">
            <div tabindex="0" @keydown.enter="$emit('update:modelValue', toggleRadio())" @click="$emit('update:modelValue', toggleRadio())" :class="[radioState ? 'bg-brand-primary border-brand-primary' : 'bg-wallpaper border-border-dark']" class="transition-colors duration-500 focus:outline-brand-primary sm:focus:outline-none focus:outline focus:outline-2 focus:outline-offset-2 cursor-pointer h-[1.7rem] w-12 flex rounded-full relative border items-center">
                <div :class="[radioState ? 'translate-x-[1.5rem] border-white' : 'translate-x-[0.2rem] border-border-dark']" class="bg-white transition-all duration-500 w-[1.15rem] h-[1.15rem] border rounded-[100%]"></div>
            </div>
        </div>
    </div>

</template>

<script setup>

    import { computed, watch } from "vue";

    const props = defineProps({
        label: {
            type: String,
            required: true,
        },
        modelValue: {
            type: Boolean
        },
        labelLocation: {
            type: String,
            default: 'left'
        },
        loading: {
            type: Boolean,
            default: false
        }
    })
    defineEmits(['update:modelValue'])

    const radioState = computed(() => props.modelValue)
    const isLoading = computed(() => props.loading)

    function toggleRadio() {

        return !radioState.value;

    }

</script>

<style scoped>

</style>
