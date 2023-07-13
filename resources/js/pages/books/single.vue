<template>

    <template v-if="!pageLoading">

        <div class="grid grid-cols-4 md:grid-cols-1 gap-2">
            <InfoCard :loading="!! ! bookStatistics" class="col-span-1" label="Total Quantities Purchased">
                <template v-if="!! bookStatistics">
                    {{ bookStatistics['total_purchased'] }}
                </template>
            </InfoCard>

            <InfoCard :loading="!! ! bookStatistics" class="col-span-1" label="Total Quantities Sold">
                <template v-if="!! bookStatistics">
                    {{ bookStatistics['total_sold'] }}
                </template>
            </InfoCard>

            <InfoCard :loading="!! ! bookStatistics" class="col-span-1" label="Sold this Month">
                <span>{{ bookStatistics['sold_last_month']['quantity'] }}</span>
                <span v-if="bookStatistics['sold_last_month']['change_rate'] === null" />
                <span v-else-if="bookStatistics['sold_last_month']['change_rate'] > 0" class="leading-none flex items-center text-xs px-2 py-1 rounded-lg bg-red-100 text-red-700 font-medium">
                    {{ Math.round(bookStatistics['sold_last_month']['change_rate'] * 100) }}%
                    <ArrowSmallDownIcon class="w-4 h-4" />
                </span>
                <span v-else class="leading-none flex items-center text-xs px-2 py-1 rounded-lg bg-green-100 text-green-700 font-medium">
                    {{ Math.round(Math.abs(bookStatistics['sold_last_month']['change_rate']) * 100) }}%
                    <ArrowSmallUpIcon class="w-4 h-4" />
                </span>
            </InfoCard>

            <InfoCard :loading="!! ! stores" class="col-span-1" label="Balance in store">
                <template v-if="!! stores">
                    {{ stores.filter(store => store.store_primary)[0] ? stores.filter(store => store.store_primary)[0].balance : 0 }}
                </template>
            </InfoCard>
        </div>

        <div class="w-full flex sm:flex-col gap-6">
            <div class="sm:w-full w-1/2 flex flex-col gap-4">
                <ItemCard
                    v-if="!loading"
                    class=""
                    :title="book.title"
                    :heading="true"
                >

                    <template #action>
                        <button @click="goToUpdate" class="focus:outline-brand-primary sm:focus:outline-none focus:outline-offset-2 h-fit md:w-fit px-6 py-2.5 bg-brand-primary rounded-lg text-white flex justify-between sm:justify-center items-center gap-2 sm:w-full">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.1329 5C15.5109 4.622 15.7189 4.12 15.7189 3.586C15.7189 3.052 15.5109 2.55 15.1329 2.172L13.5469 0.586C13.1689 0.208 12.6669 0 12.1329 0C11.5989 0 11.0969 0.208 10.7199 0.585L0.0878906 11.184V15.599H4.50089L15.1329 5ZM12.1329 2L13.7199 3.585L12.1299 5.169L10.5439 3.584L12.1329 2ZM2.08789 13.599V12.014L9.12789 4.996L10.7139 6.582L3.67489 13.599H2.08789Z" fill="white"/>
                            </svg>
                            <span class="font-medium whitespace-nowrap">
                            Update information
                        </span>
                        </button>
                    </template>

                    <template #rows>
                        <ItemDescription :description="book.code" label="code" />
                        <ItemDescription :description="book.title" label="title" />
                        <ItemDescription label="alternate title">
                            <span v-if="book['alternate_title']">{{ book['alternate_title'] }}</span>
                            <span v-else class="text-subtitle">N/A</span>
                        </ItemDescription>
                        <ItemDescription label="author">
                            <span v-if="book.author">{{ book.author }}</span>
                            <span v-else class="text-subtitle">N/A</span>
                        </ItemDescription>
                        <ItemDescription :description="book.category" label="category" />
                        <ItemDescription
                            label="balance"
                            :description="book.balance + ' copies'"
                            class="tabular-nums font-medium"
                        />
                        <ItemDescription :description="book.type" label="purchase type" />
                        <ItemDescription label="supplier">
                            <div v-if="book['supplier']">
                                {{ book['supplier']['name'] }}
                            </div>
                            <button v-else class="text-brand-primary text-xs cursor-pointer">Register a Supplier</button>
                        </ItemDescription>
                        <ItemDescription label="book covers">
                            <template v-if="!!book['front_cover'] || !!book['back_cover']">
                                <button @click="setOpenUploadCoversForm(true)" class="text-brand-primary text-xs cursor-pointer">View Book Covers</button>
                            </template>
                            <template v-else>
                                <button @click="setOpenUploadCoversForm(true)" class="text-brand-primary text-xs cursor-pointer">Upload Book Covers</button>
                            </template>
                        </ItemDescription>
                        <ItemDescription :description="new Date(book['created_at'] * 1000).toDateString()" label="registration date" />
                        <ItemDescription :description="new Date(book['updated_at'] * 1000).toDateString()" label="last updated" />
                    </template>

                </ItemCard>
            </div>

            <div v-if="book && book?.transactions?.length > 0" class="w-1/2 sm:w-full flex flex-col gap-6">
                <div class="flex justify-center w-full">
                    <Toggle :labels="['Stock records', 'Store records']" colors="white" v-model="toggleStock" />
                </div>
                <Table
                    condensed
                    v-if="book && toggleStock"
                    title="Stock record"
                    :data="book.transactions"
                    :center="['type', 'date', 'invoice']"
                    :right="[]"
                    :sortable="['date', 'quantity']"
                    :searchable="[]"
                    :hideable="false"
                    :hide="['id']"
                    :hide-labels="['id']"
                    :actions="true"
                    @table-button-click="handleTableButton"
                    @edit="handleEdit"
                    @delete="handleDelete"
                >

                    <template #action>

                        <!-- <template></template> -->

                    </template>

                    <template #description>
                        <p class="text-xs">
                            <span class="text-subtitle">{{ book.title }}</span>
                        </p>
                    </template>

                    <template #rows="data">

                        <Cell name="invoice" :hide="data.hide" class="w-[4%] text-xs font-semibold text-subtitle text-center">{{ data['record']['invoice'].toLowerCase() }} </Cell>
                        <Cell name="quantity" :main="true" :hide="data.hide" class="w-[88%] text-xs font-semibold text-subtitle">{{ data['record']['quantity'] }} </Cell>
                        <EnumCell class="w-[4%]" name="type" :hide="data.hide" :colors="['red', 'green', 'gray']" :options="['sale', 'purchase', 'return']" :value="data['record']['type']" />
                        <DateCell class="w-[4%]" name="date" :hide="data.hide" :value="data['record']['date']" />

                    </template>

                </Table>
                <Table
                    condensed
                    v-if="stores && ! toggleStock"
                    title="Store Records"
                    :data="stores"
                    :center="[]"
                    :right="[]"
                    :sortable="[]"
                    :searchable="[]"
                    :hideable="false"
                    :hide="['store_id']"
                    :hide-labels="['store_id', 'store_primary']"
                    :actions="false"
                    @table-button-click="handleTableButton"
                    @edit="handleEdit"
                    @delete="handleDelete"
                >

                    <template #action>

                        <!-- <template></template> -->

                    </template>

                    <template #description>
                        <p class="text-xs">
                            <span class="text-subtitle">{{ book.title }}</span>
                        </p>
                    </template>

                    <template #rows="data">

                        <LinkCell class="w-[80%]" name="store" :main="true" :hide="data.hide" :value="data['record']['store']" :to="'/stores/' + data['record']['store_id']">
                            <RouterLink :to="'/stores/' + data['record']['store_id']">
                                {{ data['record']['store'] }}
                            </RouterLink>
                        </LinkCell>
                        <EnumCell class="w-fit" name="store_primary" :hide="data.hide" :colors="['green', 'stone']" :options="['primary', 'warehouse']" :value="data['record']['store_primary']" />
                        <Cell name="balance" :hide="data.hide" class="w-[10%] text-xs font-semibold text-black text-center">{{ data['record']['balance'] }} </Cell>

                    </template>

                </Table>
            </div>

            <div v-else class="min-h-24 flex flex-col items-center justify-center gap-2 font-medium text-subtitle py-8 h-fit w-1/2 sm:w-full">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 my-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                </svg>
                <h3 class="text-black">No transaction history</h3>
                <h5 class="font-normal sm:w-full w-[24rem] text-center">
                    There has not been any transactions made (purchase or sale)
                    for this item. Stock and store records will appear here as transactions are preformed.
                </h5>
            </div>
        </div>

    </template>

    <p v-else class="w-full h-full flex justify-center">
        <svg class="animate-rotate" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.4706 5.94533C18.1987 5.55015 18.4769 4.63079 17.9774 3.96992C17.0157 2.69771 15.7589 1.66786 14.3065 0.974827C12.4006 0.0653761 10.2557 -0.217352 8.1792 0.167163C6.10271 0.551679 4.20125 1.5837 2.74734 3.11531C1.29344 4.64692 0.361732 6.59949 0.0857576 8.69317C-0.190217 10.7869 0.203708 12.9142 1.21108 14.7702C2.21844 16.6262 3.78753 18.1157 5.69346 19.0252C7.59939 19.9346 9.74431 20.2174 11.8208 19.8328C13.4032 19.5398 14.8839 18.8708 16.1424 17.8912C16.7961 17.3824 16.7656 16.4223 16.1648 15.852V15.852C15.5639 15.2816 14.621 15.3229 13.9359 15.7887C13.1386 16.3308 12.2332 16.7055 11.2746 16.883C9.82102 17.1521 8.31958 16.9542 6.98542 16.3176C5.65127 15.681 4.55291 14.6384 3.84775 13.3391C3.1426 12.0399 2.86685 10.5508 3.06003 9.08522C3.25321 7.61964 3.90541 6.25284 4.92314 5.18072C5.94087 4.10859 7.27189 3.38618 8.72544 3.11701C10.179 2.84785 11.6804 3.04576 13.0146 3.68238C13.8945 4.10224 14.6718 4.69872 15.3015 5.42894C15.8424 6.05634 16.7425 6.34051 17.4706 5.94533V5.94533Z" fill="#FF8100"/>
        </svg>
    </p>

    <div v-if="openUploadCoversForm">
        <UploadBookCoversModal
            @success="setOpenUploadCoversForm(false)"
            v-model:show="openUploadCoversForm"
            :covers="covers"
        />
    </div>

</template>

<script setup>

    import { ref, computed, onMounted, onBeforeUnmount } from "vue";
    import Table from "../../components/Table/Table.vue";
    import Cell from "../../components/Table/Cell.vue";
    import EnumCell from "../../components/Table/EnumCell.vue";
    import DateCell from "../../components/Table/DateCell.vue";
    import LinkCell from "../../components/Table/LinkCell.vue";
    import ItemCard from "../../components/ItemCard.vue";
    import ItemDescription from "../../components/ItemDescription.vue";
    import Toggle from "../../components/Toggle.vue"
    import InfoCard from "../../components/InfoCard.vue"
    import { ArrowSmallUpIcon, ArrowSmallDownIcon } from "@heroicons/vue/20/solid";
    import { useRoute } from "vue-router"
    import axios from "axios";
    import router from "../../router"
    import store from "../../store"
    import UploadBookCoversModal from "../../views/UploadBookCoversModal.vue";

    const toggleStock = ref(true)

    const loading = ref(true)
    const book = computed(() => store.state.book.book)
    const covers = computed(() => {
        return {
            front_cover: book.value?.front_cover,
            back_cover: book.value?.back_cover,
        }
    })
    const searchable = ref(['invoice', 'date'])

    const pageLoading = computed(() => loading.value && storesLoading.value)

    const stores = ref(null)
    const storesLoading = ref(true)
    function fetchStores() {

        axios.get('/api/books/stores', {
            params: {
                id: useRoute().params.id
            }
        })
            .then(response => {
                stores.value = response.data.data.map(store => {
                    return {
                        store: store.store.name,
                        store_primary: store.store.primary ? 'primary' : 'warehouse',
                        store_id: store.store_id,
                        balance: store.balance,
                    }
                })
            })
            .catch(error => {
                alert(`Error while fetching store details of book: ${error}`)
            })
            .finally(() => storesLoading.value = false)
    }

    async function fetchBook() {

        await store.dispatch('getBook', useRoute().params.id)
            .then(() => {
                console.log('getBook returns ', store.state.book.book)
            })
            .finally(() => {
                loading.value = false
            })

    }

    function goToUpdate() {
        router.push({ name: 'UpdateBook', params: { id: store.state.book.book.id }})
    }

    function handleTableButton() {

    }

    function handleEdit(object) {

        console.log({object})

        router.push({ name: 'UpdateTransaction', params: { id: object.id } })

    }

    function handleDelete() {

    }

    const bookStatistics = ref(null)
    function fetchBookStatistics() {

        axios.get('/api/books/statistics/book', {
            params: {
                id: useRoute().params.id
            }
        })
            .then(response => {

                bookStatistics.value = response.data.data

            })
            .catch(error => {

                alert(`Error while fetching book statistics: ${error}`)

            })

    }

    onMounted(() => {

        fetchBook()
        fetchStores()
        fetchBookStatistics()

    })

    onBeforeUnmount(() => {

        store.dispatch('destroyBook')

    })

    const openUploadCoversForm = ref(false)
    function setOpenUploadCoversForm(value) {
        openUploadCoversForm.value = value
    }

</script>

<style scoped>

</style>
