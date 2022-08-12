<template>

    <div ref="container">
        <slot />
    </div>

</template>

<script setup>

    import { ref } from "vue"

    const emit = defineEmits(['resize'])

    const container = ref(null)

    addEventListener('resize', () => {

        emit('resize', {
            width: container.value.clientWidth,
            height: container.value.clientHeight
        })

    })

</script>

<style scoped>

</style>
