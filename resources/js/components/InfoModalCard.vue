<template>

    <Teleport v-if="open" to="#top-view">

        <Modal>

            <div ref="infoModal" class="bg-white w-[42rem] min-h-fit rounded-xl sm:rounded-b-none p-8 flex flex-col gap-4 sm:animate-slide-up-modal animate-scale-up">

                <div class="flex flex-col gap-4">

                    <slot name="title">
                        <h1 class="text-lg font-medium">{{ title }}</h1>
                    </slot>

                    <slot name="description">
                        <p class="leading-3">{{ description }}</p>
                    </slot>

                </div>

            </div>

        </Modal>

    </Teleport>

</template>

<script setup>

    import { ref } from "vue"
    import { onClickOutside } from "@vueuse/core";
    import Modal from "./Modal.vue"

    const props = defineProps({
        title: {
            type: String
        },
        description: {
            type: String
        },
        open: {
            type: Boolean,
            default: false
        }
    })

    const emits = defineEmits(['close'])

    const infoModal = ref(null)
    onClickOutside(infoModal, () => {
        emits('close')
    })

</script>

<style scoped></style>
