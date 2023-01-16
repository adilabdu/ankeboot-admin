<template>

    <div class="flex flex-col gap-1">
        <label class="text-subtitle font-medium">
            {{ label }}
            <span v-if="required" class="text-red-600">*</span>
        </label>
        <span class="bg-[#FAFAFA] peer focus:outline-brand-primary sm:focus:outline-none focus:outline focus:outline-2 focus:outline-offset-2 h-10 w-full p-2 border border-border-light rounded-md flex items-center">
            {{ value }}
        </span>
        <p v-if="error !== ''" class="leading-snug px-2 peer-focus:inline hidden text-xs text-red-600">{{ error }}</p>
        <p v-if="captionLabel" class="leading-snug px-2 text-xs text-brand-primary">{{ captionLabel }}</p>
    </div>

</template>

<script setup>

    import { ref, computed } from "vue"

    const props = defineProps({
        label: {
            type: String,
            required: true
        },
        caption: {
            type: String
        },
        required: {
            type: Boolean,
            default: false
        },
        value: {
            type: [String, Number]
        },
        disabled: {
            type: Boolean,
            default: false
        }
    })

    const error = ref('')
    const captionLabel = computed(() => props.caption)

</script>
