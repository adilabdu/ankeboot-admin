<template>

    <div :class="[ labelPosition === 'left' ? 'gap-4 items-center' : 'flex-col gap-1' ]" class="flex">
        <label class="text-subtitle font-medium">
            {{ label }}
            <span v-if="required" class="text-red-600">*</span>
        </label>
        <div>
            <input :disabled="disabled" v-if="pattern === ''" :value="modelValue" @input="validate($event.target.value)" :required="required" type="text" :placeholder="placeholder" class="peer focus:outline-brand-primary sm:focus:outline-none focus:outline focus:outline-2 focus:outline-offset-2 h-10 w-full p-2 border border-border-light rounded-md" />
            <input :disabled="disabled" v-else :pattern="pattern" :value="modelValue" @input="validate($event.target.value)" :required="required" type="text" :placeholder="placeholder" class="peer focus:outline-brand-primary sm:focus:outline-none focus:outline focus:outline-2 focus:outline-offset-2 h-10 w-full p-2 border border-border-light rounded-md" />
            <p v-if="error !== ''" class="leading-snug px-2 peer-focus:inline hidden text-xs text-red-600">{{ error }}</p>
            <p v-if="captionLabel" class="leading-snug px-2 text-xs text-brand-primary">{{ captionLabel }}</p>
        </div>
    </div>

</template>

<script setup>

    import { ref, computed } from "vue"

    const props = defineProps({
        label: {
            type: String,
            required: true
        },
        labelPosition: {
            type: String,
            default: 'top'
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
            default: ''
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
