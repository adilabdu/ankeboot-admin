<template>

    <div class="w-full grid grid-cols-12 gap-2">

        <div class="col-span-4 py-2 flex flex-col gap-1">
            <h1 class="text-lg">{{ title }}</h1>
            <slot name="subtitle">
                <h2 class="text-subtitle">
                    {{ subtitle }}
                </h2>
            </slot>
        </div>

        <form @submit.prevent="submit" class="p-6 col-span-8 bg-white rounded-lg shadow-sm border border-border-light flex flex-col gap-6" title="Form" submit="">

            <slot />

            <div class="w-full flex justify-end items-center">
                <button type="submit" class="px-4 bg-brand-primary w-fit text-white rounded-md py-2">Save</button>
            </div>

        </form>

    </div>

</template>

<script setup>

    defineProps({
        title: {
            type: String,
            required: true,
        },
        subtitle: {
            type: String,
            default: 'Fill in the information of the new book to be registered. Check book titles thoroughly to make sure it is not a duplicate entry.'
        },
        submit: {
            type: Function,
            required: true
        }
    })

</script>

<style scoped>

</style>
