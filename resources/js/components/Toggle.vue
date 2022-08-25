<template>

    <div :class="colors" class="w-64 h-8 text-subtitle rounded-xl relative bg-wallpaper flex items-center">
        <div :class="[modelValue ? 'translate-x-0' : 'translate-x-full']" class="transition-transform duration-300 absolute h-full border-4 w-1/2 rounded-xl bg-border-dark"></div>
        <button @click="toggle(true)" :class="[modelValue ? 'text-black font-medium' : 'text-subtitle']" class="transition-colors duration-300 relative focus:outline-none w-1/2 text-xs rounded-lg h-full">
            {{ labels[0] }}
        </button>
        <button @click="toggle(false)" :class="[modelValue ? 'text-subtitle' : 'text-black font-medium']" class="transition-colors duration-300 relative focus:outline-none w-1/2 rounded-lg h-full text-xs">
            {{ labels[1] }}
        </button>
    </div>

</template>

<script setup>

    const props = defineProps({
        modelValue: {
            type: Boolean
        },
        labels: {
            type: Array,
            required: true
        },
        colors: {
            type: String,
            default: 'wallpaper'
        }
    })

    const emit = defineEmits(['update:modelValue'])

    function toggle(option) {
        emit('update:modelValue', option)
    }

</script>

<style scoped>

    .white {
        @apply bg-white;
    }

    .white > div {
        @apply border-white;
    }

    .wallpaper {
        @apply bg-wallpaper;
    }

    .wallpaper > div {
        @apply border-wallpaper;
    }

</style>
