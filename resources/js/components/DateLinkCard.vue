<template>

    <RouterLink
        :to="'/daily-sales/' + dailySale.id"
        tabindex="0"
        :class="[
            dailySale.submitted ? 'border' : 'border',
        ]"
        class="bg-white focus:outline-none focus-visible:outline-2
         focus-visible:outline-brand-primary focus-visible:my-1 focus-visible:first:ml-1
         focus-visible:last:mr-1 transition-colors transition-opacity duration-500
         relative flex flex-col justify-between shrink-0 flex-1
         aspect-square max-w-[212px] max-h-[212px] rounded-xl shadow-sm"
        v-slot="{ isActive }"
    >

        <!-- `View records` on hover -->
        <div
            class="cursor-pointer
             opacity-0 transition-opacity duration-300
             top-0 left-0 rounded-xl absolute w-full
             h-full flex flex-col items-center z-10
             justify-center gap-2 backdrop-blur"
            :class="[ isActive ? 'hover:opacity-0' : 'hover:opacity-100' ]"
        >

            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 2C18 0.897 17.103 0 16 0H2C0.897 0 0 0.897 0 2V16C0 17.103 0.897 18 2 18H16C17.103 18 18 17.103 18 16V2ZM2 16V2H16L16.002 16H2Z" fill="#FF8400"/>
                <path d="M4 4H5.998V6H4V4ZM8 4H14V6H8V4ZM4 8H5.998V10H4V8ZM8 8H14V10H8V8ZM4 12H5.998V14H4V12ZM8 12H14V14H8V12Z" fill="#FF8400"/>
            </svg>

            <p class="text-subtitle font-medium">View record</p>

        </div>

        <!-- Date card content -->
        <div
            :class="[
                useRoute().name === 'DailySales' ? 'opacity-100' : 'opacity-25',
                isActive ? '!opacity-100' : ''
             ]"
            class="flex flex-col justify-between rounded-xl p-4 h-full w-full">
            <div class="flex flex-row justify-between items-start">
                <div class="flex flex-col leading-tight">
                    <p class="text-subtitle text">{{ months[dailySale.date.getMonth()] }}</p>
                    <p class="font-medium text-2xl text-subtitle">{{ formatNumberToTwoIntegerPlaces(dailySale.date.getDate()) }}</p>
                    <p class="text-subtitle text">{{ days[dailySale.date.getDay()] }}</p>
                </div>
                <svg
                    :class="[
                    dailySale.date.toDateString() === new Date(new Date().setDate(new Date().getDate() - 1)).toDateString() ||
                    dailySale.submitted ? 'hidden' : 'inline'
                ]"
                    width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M24 12C24 18.6274 18.6274 24 12 24C5.37258 24 0 18.6274 0 12C0 5.37258 5.37258 0 12 0C18.6274 0 24 5.37258 24 12ZM2.68586 12C2.68586 17.1441 6.85594 21.3141 12 21.3141C17.1441 21.3141 21.3141 17.1441 21.3141 12C21.3141 6.85594 17.1441 2.68586 12 2.68586C6.85594 2.68586 2.68586 6.85594 2.68586 12Z" fill="#EF4444"/>
                    <rect x="10.666" y="6.66669" width="2.66667" height="6.66667" fill="#EF4444"/>
                    <rect x="10.666" y="14.6667" width="2.66667" height="2.66667" fill="#EF4444"/>
                </svg>
            </div>

            <div class="flex flex-col">
                <!-- TODO: add info for deposited amount -->
                <div class="flex flex-col leading-tight text-right">
                    <p class="text-subtitle text-xs">Difference</p>
                    <p
                        :class="[
                        dailySale.difference &&
                        Math.abs(dailySale.difference) > 5.00 ?
                        'text-red-500' : 'text-subtitle',
                        dailySale.submitted ?
                        'text-black' : ''
                    ]"
                        class="font-medium text-2xl proportional-nums">
                        <sup v-if="!! dailySale.difference" class="text-xs">ETB</sup>
                        {{ dailySale.submitted ? parseFloat(dailySale.difference).toFixed(2) : 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

    </RouterLink>

</template>

<script setup>

    import { months, days, formatNumberToTwoIntegerPlaces } from "../utils";
    import { useRoute } from "vue-router"

    const props = defineProps({

        dailySale: {
            type: Object,
            required: true
        }

    })

</script>

<style scoped></style>
