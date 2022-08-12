<template>

    <ContentPage>

        <div class="flex flex-col h-fit w-full gap-8">

            <container
                ref="date_cards"
                @resize="updateGridSize"
                class="flex flex-row items-center justify-evenly gap-3 h-fit overflow-auto relative"
            >

                <template v-if="dailySales" v-for="dailySale in dailySales">

                    <div :class="[dailySale.submitted ? 'border bg-white' : 'border-dashed border-2']"
                         class="relative flex flex-col justify-between p-4 shrink-0 w-52 h-52 rounded-xl shadow-sm">

                        <div
                            v-if="! dailySale.submitted"
                            @click="openDailySale(dailySale.date)"
                            class="cursor-pointer hover:opacity-100
                         opacity-0 transition-opacity duration-300
                         top-0 left-0 rounded-xl absolute w-full
                         h-full bg-white/50 flex flex-col
                         items-center justify-center z-10 backdrop-blur"
                        >

                            <svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24.2746 17.1137H22.0118V21.6392H17.4863V23.9019H22.0118V28.4274H24.2746V23.9019H28.8V21.6392H24.2746V17.1137Z" fill="#FF8100"/>
                                <path d="M23.1418 11.4559C16.9035 11.4559 11.8281 16.5312 11.8281 22.7696C11.8281 29.008 16.9035 34.0833 23.1418 34.0833C29.3802 34.0833 34.4555 29.008 34.4555 22.7696C34.4555 16.5312 29.3802 11.4559 23.1418 11.4559ZM23.1418 31.8205C18.1514 31.8205 14.0909 27.7601 14.0909 22.7696C14.0909 17.7791 18.1514 13.7186 23.1418 13.7186C28.1323 13.7186 32.1928 17.7791 32.1928 22.7696C32.1928 27.7601 28.1323 31.8205 23.1418 31.8205Z" fill="#FF8100"/>
                            </svg>

                            <p class="text-subtitle font-medium">Input Daily Sale</p>

                        </div>

                        <div
                            v-if="dailySale.submitted"
                            @click="get({ id: dailySale.id })"
                            class="cursor-pointer hover:opacity-100
                             opacity-0 transition-opacity duration-300
                             top-0 left-0 rounded-xl absolute w-full
                             h-full flex flex-col items-center z-10
                             justify-center gap-2 backdrop-blur"
                        >

                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 2C18 0.897 17.103 0 16 0H2C0.897 0 0 0.897 0 2V16C0 17.103 0.897 18 2 18H16C17.103 18 18 17.103 18 16V2ZM2 16V2H16L16.002 16H2Z" fill="#FF8400"/>
                                <path d="M4 4H5.998V6H4V4ZM8 4H14V6H8V4ZM4 8H5.998V10H4V8ZM8 8H14V10H8V8ZM4 12H5.998V14H4V12ZM8 12H14V14H8V12Z" fill="#FF8400"/>
                            </svg>

                            <p class="text-subtitle font-medium">View record</p>

                        </div>

                        <div class="flex flex-row justify-between items-start">
                            <div class="flex flex-col leading-tight">
                                <p class="text-subtitle text">{{ months[dailySale.date.getMonth()] }}</p>
                                <p class="font-medium text-2xl text-subtitle">{{ formatNumberToTwoIntegerPlaces(dailySale.date.getDate() ) }}</p>
                                <p class="text-subtitle text">{{ days[dailySale.date.getDay()] }}</p>
                            </div>
                            <svg
                                :class="[
                                dailySale.date.toDateString() === new Date(new Date().setDate(new Date().getDate() - 1)).toDateString() ||
                                dailySale.submitted ? 'hidden' : 'inline']"
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
                                dailySale.difference && Math.abs(dailySale.difference) > 5.00 ? 'text-red-500' : 'text-subtitle' ]"
                                    class="font-medium text-2xl proportional-nums">
                                    <sup v-if="!! dailySale.difference" class="text-xs">ETB</sup>
                                    {{ !! dailySale.difference ? parseFloat(dailySale.difference).toFixed(2) : 'N/A' }}
                                </p>
                            </div>
                        </div>

                    </div>

                </template>

            </container>

            <div v-if="chosenDate" class="w-full flex flex-col items-center gap-2">

                <p class="text-subtitle font-medium py-2">
                    Daily sales report for
                    <span class="text-brand-primary">
                            {{ new Date(chosenDate['date_of']).toDateString() }}
                        </span>
                </p>

                <div class="w-full rounded-xl bg-white flex flex-col gap-3 h-fit p-4 border border-border-light shadow-sm">

                    <div class="grid grid-cols-4 gap-2 w-full p-2 grow">

                        <div class="flex flex-col items-center py-2 gap-1">
                            <label class="text-subtitle font-medium">Date</label>
                            <label class="font-medium">{{ new Date(chosenDate['date_of']).toDateString() }}</label>
                        </div>

                        <div class="flex flex-col items-center py-2 gap-1">
                            <label class="text-subtitle font-medium">Submitted Date</label>
                            <label class="font-medium">{{ new Date(chosenDate['created_at']).toDateString() }}</label>
                        </div>

                        <div class="flex flex-col items-center py-2 gap-1">
                            <label class="text-subtitle font-medium">Cashier</label>
                            <label class="font-medium">{{ chosenDate['cashier'] }}</label>
                        </div>

                        <div class="flex flex-col items-center py-2 gap-1">
                            <label class="text-subtitle font-medium">Status</label>
                            <label class="px-2 py-0.5 rounded-full bg-brand-secondary text-brand-primary text-xs">
                                {{ chosenDate['is_submitted'] ? 'submitted' : 'not submitted' }}
                            </label>
                        </div>

                        <div class="flex flex-col items-center py-2 gap-1">
                            <label class="text-subtitle font-medium">Sales receipts</label>
                            <label class="font-medium">
                                ETB {{ (chosenDate['sales_receipts'].reduce((old, value) => {  return old + value['amount'] }, 0)).toFixed(2) }}
                                ({{ chosenDate['sales_receipts'].length }})
                            </label>
                        </div>

                        <div class="flex flex-col items-center py-2 gap-1">
                            <label class="text-subtitle font-medium">Deposit receipts</label>
                            <label class="font-medium">
                                ETB {{ (chosenDate['deposits'].reduce((old, value) => {  return old + value['amount'] }, 0)).toFixed(2) }}
                                ({{ chosenDate['deposits'].length }})
                            </label>
                        </div>

                        <div class="flex flex-col items-center py-2 gap-1">
                            <label class="text-subtitle font-medium">Expenses</label>
                            <label class="font-medium">
                                ETB {{ (chosenDate['expenses'].reduce((old, value) => {  return old + value['amount'] }, 0)).toFixed(2) }}
                                ({{ chosenDate['expenses'].length }})
                            </label>
                        </div>

                        <div class="flex flex-col items-center py-2 gap-1">
                            <label class="text-subtitle font-medium">Difference</label>
                            <label :class="chosenDate['difference'] > 5 ? 'text-red-500' : ''" class="font-medium">
                                <span v-if="chosenDate['difference'] > 0">+</span>
                                <span v-else>-</span>
                                {{ chosenDate['difference'] }}
                                ETB
                            </label>
                        </div>

                    </div>

                </div>

            </div>

            <div v-if="chosenDate" class="grid grid-cols-4 w-full gap-2">

                <div class="col-span-1 flex flex-col gap-2">

                    <p class="text-center font-medium text-subtitle w-full">Sales Receipts</p>

                    <template v-for="sales_receipt in chosenDate['sales_receipts']">
                        <div class="flex w-full border border-border-light rounded-xl shadow-sm h-fit bg-white/75">

                            <div class="flex flex-col leading-tight items-start p-4 text-lg justify-center grow">
                                <p class="text-subtitle">{{ sales_receipt['receipt'] }}</p>
                            </div>
                            <p class="p-4 gap-1 w-fit h-full flex items-center justify-center text-lg font-medium">
                                <sup class="text-xs">ETB</sup>
                                {{ sales_receipt['amount'].toFixed(2) }}
                            </p>

                        </div>
                    </template>

                </div>

                <div class="col-span-1 flex flex-col gap-2">

                    <p class="text-center font-medium text-subtitle w-full">Deposits</p>
                    <template v-for="deposit in chosenDate['deposits']">
                        <div class="flex w-full border border-border-light rounded-xl shadow-sm h-fit bg-white/75">

                            <div class="flex flex-col leading-tight items-start p-4 text-lg justify-center grow">
                                <p class="font-medium text-xs">{{ deposit['type'] }}</p>
                                <p class="text-subtitle text-xs">{{ new Date(deposit['deposited_on']).toDateString() }}</p>
                            </div>
                            <p class="p-4 gap-1 w-fit h-full flex items-center justify-center text-lg font-medium">
                                <sup class="text-xs">ETB</sup>
                                {{ deposit['amount'].toFixed(2) }}
                            </p>

                        </div>
                    </template>

                </div>

                <div class="col-span-1 flex flex-col gap-2">

                    <p class="text-center font-medium text-subtitle w-full">Expenses</p>
                    <template v-for="expense in chosenDate['expenses']">
                        <div class="flex w-full border border-border-light rounded-xl shadow-sm h-fit bg-white/75">

                            <div class="flex flex-col leading-tight items-start p-4 text-lg justify-center grow">
                                <p class="font-medium">
                                    <span class="text-subtitle">PV</span>
                                    {{ expense['receipt'] }}
                                </p>
                                <p class="text-subtitle text-xs">{{ expense['description'] }}</p>
                            </div>
                            <p class="p-4 gap-1 w-fit h-full flex items-center justify-center text-lg font-medium">
                                <sup class="text-xs">ETB</sup>
                                {{ expense['amount'].toFixed(2) }}
                            </p>

                        </div>
                    </template>

                </div>

                <div class="col-span-1 flex flex-col gap-2">

                    <p class="text-center font-medium text-subtitle w-full">Expenses</p>
                    <template v-for="expense in chosenDate['expenses']">
                        <div class="flex w-full border border-border-light rounded-xl shadow-sm h-fit bg-white/75">

                            <div class="flex flex-col leading-tight items-start p-4 text-lg justify-center grow">
                                <p class="font-medium">
                                    <span class="text-subtitle">PV</span>
                                    {{ expense['receipt'] }}
                                </p>
                                <p class="text-subtitle text-xs">{{ expense['description'] }}</p>
                            </div>
                            <p class="p-4 gap-1 w-fit h-full flex items-center justify-center text-lg font-medium">
                                <sup class="text-xs">ETB</sup>
                                {{ expense['amount'].toFixed(2) }}
                            </p>

                        </div>
                    </template>

                </div>

            </div>

        </div>

    </ContentPage>

</template>

<script setup>

    import { ref, onMounted, watch } from "vue";
    import { formatNumberToTwoIntegerPlaces } from "../../utils"
    import Container from "../../components/container.vue"
    import ContentPage from "../../layouts/content-page.vue"
    import store from "../../store"

    const dailySales = ref([])
    const months = [
        'January', 'February', 'March',
        'April', 'May', 'June', 'July',
        'August', 'September', 'October',
        'November', 'December'
    ]
    const days = [
        'Sun', 'Mon', 'Tue', 'Wed',
        'Thu', 'Fri', 'Sat'
    ]
    const page = ref(0)
    const chosenDate = ref(null)

    async function getDailySales(direction = 'forward', limit = 7) {

        await axios.get('/api/daily-sales', {
            params: {
                limit: limit,
                page: page.value,
            }
        }).then((response) => {

            if (response.data.message === 'ok') {

                dailySales.value = response.data.data.map((dailySale) => {

                    return {
                        id: dailySale.id,
                        date: new Date(dailySale['date_of']),
                        submitted: dailySale['is_submitted'],
                        difference: dailySale.difference
                    }

                })

            }

        })

    }

    const date_cards = ref(null)

    const display = ref(0)
    watch(display, () => {

        getDailySales('forward', display.value)

    })

    function updateGridSize(size) {

        display.value = Math.floor((size.width) / 216);
        console.log({
            size: size,
            display: display.value,
        })

    }

    async function get(parameters) {

        await axios.get('/api/daily-sales', {

            params: parameters

        }).then((response) => {

            console.log('get daily sale ', response.data.data)
            chosenDate.value = response.data.data

        }).catch(($error) => {

            alert($error.data.data)

        })

    }

    async function post() {

        await axios.post('/api/daily-sales').then((response) => {

            if (response.data.message === 'ok') {

                store.dispatch('setUnsubmitted', response.data.data['unsubmitted_count'])

            }

        })

    }

    onMounted(() => {

        post().then(() => {

            getDailySales().then(() => {

            })

        })

    })

</script>

<style scoped></style>
