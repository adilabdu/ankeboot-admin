<template>

    <div
        :class="[
            titleLayout === 'panel' ? 'grid grid-cols-12' : ''
        ]"
        class="w-full gap-2"
    >

        <div
            v-if="titleLayout !== 'contained'"
            :class="[
                titleLayout === 'panel' ? 'col-span-4' : '',
            ]"
            class="py-2 flex flex-col gap-1"
        >

            <h1 class="text-base">{{ title }}</h1>
            <h2 class="text-subtitle">
                <slot name="subtitle">
                    {{ subtitle }}
                </slot>
            </h2>
        </div>

        <form
            :class="[
                titleLayout === 'panel' ? 'col-span-8' : ''
            ]"
            @submit.prevent="submit"
            class="p-6 bg-white rounded-lg shadow-sm border border-border-light flex flex-col gap-6" :title="title" submit="">

            <div v-if="titleLayout === 'contained'" class="flex flex-col gap-1">
                <h1 class="text-base">{{ title }}</h1>
                <h2 class="text-subtitle">
                    <slot name="subtitle">
                        {{ subtitle }}
                    </slot>
                </h2>
                <div class="border-b border-border-light w-full mt-2"></div>
            </div>

            <slot />

            <div v-if="showSubmit" class="w-full flex justify-between items-center">
                <p class="text-subtitle text-xs px-2">
                    <span class="text-red-600 text-sm">*</span>
                    Required fields
                </p>
                <button
                    :disabled="isLoading"
                    type="submit"
                    :class="[isLoading ? 'opacity-50' : '']"
                    class="px-4 bg-brand-primary w-fit text-white rounded-md py-2"
                >
                    <svg v-if="isLoading" class="inline animate-rotate mr-2" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.4706 5.94533C18.1987 5.55015 18.4769 4.63079 17.9774 3.96992C17.0157 2.69771 15.7589 1.66786 14.3065 0.974827C12.4006 0.0653761 10.2557 -0.217352 8.1792 0.167163C6.10271 0.551679 4.20125 1.5837 2.74734 3.11531C1.29344 4.64692 0.361732 6.59949 0.0857576 8.69317C-0.190217 10.7869 0.203708 12.9142 1.21108 14.7702C2.21844 16.6262 3.78753 18.1157 5.69346 19.0252C7.59939 19.9346 9.74431 20.2174 11.8208 19.8328C13.4032 19.5398 14.8839 18.8708 16.1424 17.8912C16.7961 17.3824 16.7656 16.4223 16.1648 15.852V15.852C15.5639 15.2816 14.621 15.3229 13.9359 15.7887C13.1386 16.3308 12.2332 16.7055 11.2746 16.883C9.82102 17.1521 8.31958 16.9542 6.98542 16.3176C5.65127 15.681 4.55291 14.6384 3.84775 13.3391C3.1426 12.0399 2.86685 10.5508 3.06003 9.08522C3.25321 7.61964 3.90541 6.25284 4.92314 5.18072C5.94087 4.10859 7.27189 3.38618 8.72544 3.11701C10.179 2.84785 11.6804 3.04576 13.0146 3.68238C13.8945 4.10224 14.6718 4.69872 15.3015 5.42894C15.8424 6.05634 16.7425 6.34051 17.4706 5.94533V5.94533Z" fill="white"/>
                    </svg>
                    {{ submitTitle }}
                </button>
            </div>

        </form>

    </div>

</template>

<script setup>

    import { computed } from "vue";

    const props = defineProps({
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
        },
        submitTitle: {
            type: String,
            default: 'Submit'
        },
        showSubmit: {
            type: Boolean,
            default: true
        },
        loading: {
            type: Boolean,
            default: false
        },
        titleLayout: {
            type: String,
            default: 'panel'
        }
    })

    const isLoading = computed(() => props.loading)

</script>

<style scoped>

</style>
