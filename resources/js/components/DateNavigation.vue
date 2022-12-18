<template>

    <div class="relative">
        <div
            ref="date_cards"
            class="date-card-container grid grid-cols-2 justify-items-center auto-rows-[0] gap-x-3 grid-rows-1 overflow-hidden h-fit justify-start overflow-auto relative transition-all duration-500 group"
            :class="[
                paginating ? 'opacity-25' : ''
            ]"
                >
                    <!-- Date Cards -->
                    <template v-if="! dataLoading" v-for="i in 6">

                        <DateLinkCard :daily-sale="dailySales[i-1]"/>

                    </template>

                    <!-- Date Cards loading -->
                    <template v-else v-for="i in 6">

                        <LoadingCard />

                    </template>

        <!-- TODO: find better pagination implementation -->
        <!--            <button class="h-12 w-12 m-1 hover:bg-black/10 transition-all flex items-center justify-center group-hover:opacity-100 opacity-0 transition-opacity duration-300 focus:outline-none active:scale-75 absolute left-0 z-20 rounded-full" v-if="previous" @click="previousPage">-->
        <!--                <svg width="12" height="19" viewBox="0 0 12 19" class="rotate-180" fill="none" xmlns="http://www.w3.org/2000/svg">-->
        <!--                    <path d="M2.52286 18.293L11.5229 9.29303L2.52286 0.29303L0.292969 2.52292L7.06308 9.29303L0.292969 16.0631L2.52286 18.293Z" fill="#6A727F"/>-->
        <!--                </svg>-->
        <!--            </button>-->
        <!--            <button class="h-12 w-12 m-1 hover:bg-black/10 transition-all flex items-center justify-center group-hover:opacity-100 opacity-0 transition-opacity duration-300 focus:outline-none active:scale-75 absolute right-0 z-20 rounded-full" v-if="next" @click="nextPage">-->
        <!--                <svg width="12" height="19" viewBox="0 0 12 19" fill="none" xmlns="http://www.w3.org/2000/svg">-->
        <!--                    <path d="M2.52286 18.293L11.5229 9.29303L2.52286 0.29303L0.292969 2.52292L7.06308 9.29303L0.292969 16.0631L2.52286 18.293Z" fill="#6A727F"/>-->
        <!--                </svg>-->
        <!--            </button>-->

        </div>
        <!-- Loading indicator -->
        <div v-if="paginating" class="absolute top-0 bottom-0 flex w-full items-center justify-center gap-2">

            <div class="flex gap-2 bg-white rounded-full px-4 py-2">
                <svg class="animate-rotate" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.4706 5.94533C18.1987 5.55015 18.4769 4.63079 17.9774 3.96992C17.0157 2.69771 15.7589 1.66786 14.3065 0.974827C12.4006 0.0653761 10.2557 -0.217352 8.1792 0.167163C6.10271 0.551679 4.20125 1.5837 2.74734 3.11531C1.29344 4.64692 0.361732 6.59949 0.0857576 8.69317C-0.190217 10.7869 0.203708 12.9142 1.21108 14.7702C2.21844 16.6262 3.78753 18.1157 5.69346 19.0252C7.59939 19.9346 9.74431 20.2174 11.8208 19.8328C13.4032 19.5398 14.8839 18.8708 16.1424 17.8912C16.7961 17.3824 16.7656 16.4223 16.1648 15.852V15.852C15.5639 15.2816 14.621 15.3229 13.9359 15.7887C13.1386 16.3308 12.2332 16.7055 11.2746 16.883C9.82102 17.1521 8.31958 16.9542 6.98542 16.3176C5.65127 15.681 4.55291 14.6384 3.84775 13.3391C3.1426 12.0399 2.86685 10.5508 3.06003 9.08522C3.25321 7.61964 3.90541 6.25284 4.92314 5.18072C5.94087 4.10859 7.27189 3.38618 8.72544 3.11701C10.179 2.84785 11.6804 3.04576 13.0146 3.68238C13.8945 4.10224 14.6718 4.69872 15.3015 5.42894C15.8424 6.05634 16.7425 6.34051 17.4706 5.94533V5.94533Z" fill="#FF8100"/>
                </svg>

                <span class="text-subtitle font-medium">
                    Loading
                </span>
            </div>

        </div>
    </div>

</template>

<script setup>

    import { onMounted, ref, watch, computed } from "vue";
    import { onBeforeRouteUpdate } from "vue-router";
    import DateLinkCard from "./DateLinkCard.vue"
    import LoadingCard from "./LoadingCard.vue"

    const props = defineProps({
        loading: {
            type: Boolean,
            default: false
        }
    })

    const dataLoading = computed(() => props.loading)
    const paginating = ref(false)
    const next = ref(null)
    const previous = ref(null)

    async function getDailySales(url = '/api/daily-sales?page=1', limit = 7) {

        await axios.get(url, {
            params: {
                limit: limit
            }
        }).then((response) => {

            if (response.data.message === 'ok') {

                dailySales.value = response.data.data.data.map((dailySale) => {

                    return {
                        id: dailySale.id,
                        date: new Date(dailySale['date_of']),
                        submitted: dailySale['is_submitted'],
                        difference: dailySale.difference
                    }

                })

                next.value = response.data.data['next_page_url']
                previous.value = response.data.data['prev_page_url']

            }

        })

    }

    const dailySales = ref([])

    onMounted(() => {

        getDailySales()

    })

    onBeforeRouteUpdate((to, from, next) => {

        if (!! ! to.params.id) {
            console.log('route updated but not to a specific daily sale')
            getDailySales()
        } else {
            console.log('going to a specific daily sale')
        }

        next()

    })

</script>

<style scoped>

@media only screen and (max-width: 320px) {

        .date-card-container {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }
}

@media only screen and (min-width: 505px) {

    .date-card-container {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

@media only screen and (min-width: 830px) {

    .date-card-container {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
}

@media only screen and (min-width: 1100px) {

    .date-card-container {
        grid-template-columns: repeat(5, minmax(0, 1fr));
    }
}

@media only screen and (min-width: 1500px) {

    .date-card-container {
        grid-template-columns: repeat(6, minmax(0, 1fr));
    }
}

</style>
