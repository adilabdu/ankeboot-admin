<template>
  <div class="flex flex-col gap-1">
    <label class="text-subtitle font-medium">
      {{ label }}
      <span v-if="required" class="text-red-600">*</span>
    </label>
    <textarea
      rows="4"
      :disabled="disabled"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :required="required"
      type="textarea"
      :placeholder="placeholder"
      class="focus:outline-brand-primary sm:focus:outline-none focus:outline focus:outline-2 focus:outline-offset-2 w-full p-2 border border-border-light rounded-md"
    />
  </div>
</template>

<script setup>
const props = defineProps({
  label: {
    type: String,
    required: true
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
    type: String
  },
  disabled: {
    type: Boolean,
    default: false
  }
})
defineEmits(['update:modelValue'])
</script>

<style scoped></style>
