<template>

    <div class="w-full h-fit flex flex-col flex-col-reverse gap-2 items-end">
        <DropDown
            v-model="range"
            class="text-sm font-normal min-w-[133px] w-fit h-8"
            :list="['Last week', 'Last month', 'All time']"
        ></DropDown>
        <div class="w-full grid grid-cols-4 gap-2">
            <InfoCard class="group col-span-1" label="Unsubmitted Daily Sales">
                <div v-if="! loading" class="w-full flex items-center justify-between">
                    <div class="flex items-center gap-2">
                    <span :class="[statistics['daily_sale']['unsubmitted'] > 1 ? 'text-red-500' : '']">
                        {{ statistics['daily_sale']['unsubmitted'] }}
                    </span>
                        <span class="text-base text-subtitle">
                        / {{ statistics['daily_sale']['count'] }}
                    </span>
                    </div>
                </div>
                <svg v-else class="animate-rotate my-2" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.4706 5.94533C18.1987 5.55015 18.4769 4.63079 17.9774 3.96992C17.0157 2.69771 15.7589 1.66786 14.3065 0.974827C12.4006 0.0653761 10.2557 -0.217352 8.1792 0.167163C6.10271 0.551679 4.20125 1.5837 2.74734 3.11531C1.29344 4.64692 0.361732 6.59949 0.0857576 8.69317C-0.190217 10.7869 0.203708 12.9142 1.21108 14.7702C2.21844 16.6262 3.78753 18.1157 5.69346 19.0252C7.59939 19.9346 9.74431 20.2174 11.8208 19.8328C13.4032 19.5398 14.8839 18.8708 16.1424 17.8912C16.7961 17.3824 16.7656 16.4223 16.1648 15.852V15.852C15.5639 15.2816 14.621 15.3229 13.9359 15.7887C13.1386 16.3308 12.2332 16.7055 11.2746 16.883C9.82102 17.1521 8.31958 16.9542 6.98542 16.3176C5.65127 15.681 4.55291 14.6384 3.84775 13.3391C3.1426 12.0399 2.86685 10.5508 3.06003 9.08522C3.25321 7.61964 3.90541 6.25284 4.92314 5.18072C5.94087 4.10859 7.27189 3.38618 8.72544 3.11701C10.179 2.84785 11.6804 3.04576 13.0146 3.68238C13.8945 4.10224 14.6718 4.69872 15.3015 5.42894C15.8424 6.05634 16.7425 6.34051 17.4706 5.94533V5.94533Z" fill="#FF8100"/>
                </svg>
            </InfoCard>
            <InfoCard class="col-span-1" label="Deposit / Report difference">
                <div class="flex justify-between items-center gap-2 w-full h-full">
                    <div v-if="! loading" class="items-center gap-1 flex">
                        <span class="text-base">({{ statistics['aggregate'].difference.all > 0 ? '+' : '-' }})</span>
                        <template v-if="range === 'Last week'">
                            <span class="">{{ formatPrice(Math.abs(statistics['aggregate']['difference']['last_week'])) }}</span>
                        </template>
                        <template v-else-if="range === 'Last month'">
                            <span class="">{{ formatPrice(Math.abs(statistics['aggregate']['difference']['last_month'])) }}</span>
                        </template>
                        <template v-else>
                            <span class="">{{ formatPrice(Math.abs(statistics['aggregate']['difference'].all)) }}</span>
                        </template>
                        <sup class="text-xs">ETB</sup>
                    </div>
                    <svg v-else class="animate-rotate my-2" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.4706 5.94533C18.1987 5.55015 18.4769 4.63079 17.9774 3.96992C17.0157 2.69771 15.7589 1.66786 14.3065 0.974827C12.4006 0.0653761 10.2557 -0.217352 8.1792 0.167163C6.10271 0.551679 4.20125 1.5837 2.74734 3.11531C1.29344 4.64692 0.361732 6.59949 0.0857576 8.69317C-0.190217 10.7869 0.203708 12.9142 1.21108 14.7702C2.21844 16.6262 3.78753 18.1157 5.69346 19.0252C7.59939 19.9346 9.74431 20.2174 11.8208 19.8328C13.4032 19.5398 14.8839 18.8708 16.1424 17.8912C16.7961 17.3824 16.7656 16.4223 16.1648 15.852V15.852C15.5639 15.2816 14.621 15.3229 13.9359 15.7887C13.1386 16.3308 12.2332 16.7055 11.2746 16.883C9.82102 17.1521 8.31958 16.9542 6.98542 16.3176C5.65127 15.681 4.55291 14.6384 3.84775 13.3391C3.1426 12.0399 2.86685 10.5508 3.06003 9.08522C3.25321 7.61964 3.90541 6.25284 4.92314 5.18072C5.94087 4.10859 7.27189 3.38618 8.72544 3.11701C10.179 2.84785 11.6804 3.04576 13.0146 3.68238C13.8945 4.10224 14.6718 4.69872 15.3015 5.42894C15.8424 6.05634 16.7425 6.34051 17.4706 5.94533V5.94533Z" fill="#FF8100"/>
                    </svg>
                </div>
            </InfoCard>
            <InfoCard class="col-span-1" label="Sales Aggregate">
                <div class="flex justify-between items-center gap-2 w-full h-full">
                    <div v-if="! loading" class="items-center gap-1 flex">
                        <template v-if="range === 'Last week'">
                            <span class="">{{ (formatPrice(statistics['aggregate'].sales['last_week'])) }}</span>
                        </template>
                        <template v-else-if="range === 'Last month'">
                            <span class="">{{ (formatPrice(statistics['aggregate'].sales['last_month'])) }}</span>
                        </template>
                        <template v-else>
                            <span class="">{{ (formatPrice(statistics['aggregate'].sales.all)) }}</span>
                        </template>
                        <sup class="text-xs">ETB</sup>
                    </div>
                    <svg v-else class="animate-rotate my-2" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.4706 5.94533C18.1987 5.55015 18.4769 4.63079 17.9774 3.96992C17.0157 2.69771 15.7589 1.66786 14.3065 0.974827C12.4006 0.0653761 10.2557 -0.217352 8.1792 0.167163C6.10271 0.551679 4.20125 1.5837 2.74734 3.11531C1.29344 4.64692 0.361732 6.59949 0.0857576 8.69317C-0.190217 10.7869 0.203708 12.9142 1.21108 14.7702C2.21844 16.6262 3.78753 18.1157 5.69346 19.0252C7.59939 19.9346 9.74431 20.2174 11.8208 19.8328C13.4032 19.5398 14.8839 18.8708 16.1424 17.8912C16.7961 17.3824 16.7656 16.4223 16.1648 15.852V15.852C15.5639 15.2816 14.621 15.3229 13.9359 15.7887C13.1386 16.3308 12.2332 16.7055 11.2746 16.883C9.82102 17.1521 8.31958 16.9542 6.98542 16.3176C5.65127 15.681 4.55291 14.6384 3.84775 13.3391C3.1426 12.0399 2.86685 10.5508 3.06003 9.08522C3.25321 7.61964 3.90541 6.25284 4.92314 5.18072C5.94087 4.10859 7.27189 3.38618 8.72544 3.11701C10.179 2.84785 11.6804 3.04576 13.0146 3.68238C13.8945 4.10224 14.6718 4.69872 15.3015 5.42894C15.8424 6.05634 16.7425 6.34051 17.4706 5.94533V5.94533Z" fill="#FF8100"/>
                    </svg>
                </div>
            </InfoCard>
            <InfoCard class="col-span-1" label="Unsettled Credit Sales">
                <div class="flex justify-between items-center gap-2 w-full h-full">
                    <div v-if="! loading" class="items-center gap-1 flex">
                        <template v-if="range === 'Last week'">
                            {{ (formatPrice(statistics['aggregate'].credits['last_week'])) }}
                        </template>
                        <template v-else-if="range === 'Last month'">
                            {{ (formatPrice(statistics['aggregate'].credits['last_month'])) }}
                        </template>
                        <template v-else>
                            {{ (formatPrice(statistics['aggregate'].credits.all)) }}
                        </template>
                        <sup class="text-xs">ETB</sup>
                    </div>
                    <svg v-else class="animate-rotate my-2" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.4706 5.94533C18.1987 5.55015 18.4769 4.63079 17.9774 3.96992C17.0157 2.69771 15.7589 1.66786 14.3065 0.974827C12.4006 0.0653761 10.2557 -0.217352 8.1792 0.167163C6.10271 0.551679 4.20125 1.5837 2.74734 3.11531C1.29344 4.64692 0.361732 6.59949 0.0857576 8.69317C-0.190217 10.7869 0.203708 12.9142 1.21108 14.7702C2.21844 16.6262 3.78753 18.1157 5.69346 19.0252C7.59939 19.9346 9.74431 20.2174 11.8208 19.8328C13.4032 19.5398 14.8839 18.8708 16.1424 17.8912C16.7961 17.3824 16.7656 16.4223 16.1648 15.852V15.852C15.5639 15.2816 14.621 15.3229 13.9359 15.7887C13.1386 16.3308 12.2332 16.7055 11.2746 16.883C9.82102 17.1521 8.31958 16.9542 6.98542 16.3176C5.65127 15.681 4.55291 14.6384 3.84775 13.3391C3.1426 12.0399 2.86685 10.5508 3.06003 9.08522C3.25321 7.61964 3.90541 6.25284 4.92314 5.18072C5.94087 4.10859 7.27189 3.38618 8.72544 3.11701C10.179 2.84785 11.6804 3.04576 13.0146 3.68238C13.8945 4.10224 14.6718 4.69872 15.3015 5.42894C15.8424 6.05634 16.7425 6.34051 17.4706 5.94533V5.94533Z" fill="#FF8100"/>
                    </svg>
                </div>
            </InfoCard>
        </div>
    </div>

</template>

<script setup>

    import { ref, onMounted, onBeforeUnmount } from "vue"
    import { formatPrice } from "../../utils";
    import InfoCard from "../../components/InfoCard.vue"
    import DropDown from "../../components/Dropdown.vue"
    import axios from "axios";
    import store from "../../store";

    const loading = ref(true)
    const statistics = ref({})
    const range = ref('All time')
    const controller = new AbortController()

    async function getStatistics() {

        await axios.get('/api/daily-sales/statistics', {
            signal: controller.signal
        })
            .then((response) => {

                statistics.value = response.data.data

            }).catch((error) => {

                if (error.code !== 'ERR_CANCELED') {

                    store.dispatch('pushAlert', {
                        type: 'error',
                        message: 'Something wrong. Please try again later'
                    })

                }

            })

    }

    onMounted(() => {

        getStatistics().then(() => {
            loading.value = false
        })

    })

    onBeforeUnmount(() => {
      
        // Abort the request
        controller.abort()
    
    })

</script>

<style scoped>

</style>
