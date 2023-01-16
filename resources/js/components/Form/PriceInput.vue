<template>

    <div class="flex flex-col gap-1">
        <label class="text-subtitle font-medium">
            {{ label }}
            <span v-if="required" class="text-red-600">*</span>
        </label>
        <div class="flex w-full h-fit group focus-within:outline focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-brand-primary rounded-md">
            <div class="focus:outline-none h-10 px-2.5 rounded-l-md w-fit grid place-items-center border-y border-l border-border-light text-subtitle text-xs font-medium">ETB</div>
            <input :disabled="disabled" :pattern="pattern" :value="modelValue" @input="validate($event.target.value)" :required="required" type="text" :placeholder="placeholder" class="focus:outline-none text-right peer h-10 w-full p-2 border-r border-y border-border-light rounded-r-md" />
        </div>
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
    placeholder: {
        type: String,
        required: true
    },
    required: {
        type: Boolean,
        default: false
    },
    modelValue: {
        type: [String, Number]
    },
    disabled: {
        type: Boolean,
        default: false
    },
    pattern: {
        type: String,
        default: '^[0-9]+.?[0-9]{0,2}$'
    }
})

const emits = defineEmits(['update:modelValue'])

const input = computed(() => props.modelValue)
const patternType = computed(() => {

    switch (props.pattern) {

        case '^[0-9]+.?[0-9]{0,2}$':
            return 'price'

        case '^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$':
            return 'date'

        default:
            return 'text'

    }

})
const error = ref('')
const captionLabel = computed(() => props.caption)

function validate(input) {

    if (input === '') {

        emits('update:modelValue', '')

    } else if (props.pattern !== '' && ! new RegExp(props.pattern).test(input)) {

        console.log(`Please input a valid ${patternType.value}`)
        console.log(`pattern is ${props.pattern}`)
        error.value = `Please input a valid ${patternType.value}`

    } else {

        error.value = ''
        console.log('valid')
        emits('update:modelValue', input)

    }

}

</script>

<style scoped>

</style>
