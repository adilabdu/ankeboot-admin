<template>

    <div v-if="statistics" class="grid grid-cols-4 gap-2">
        <InfoCard class="col-span-1" label="Total Books">
            <span class="">{{ formatNumber(statistics.count) ?? 0 }}</span>
        </InfoCard>
        <InfoCard class="group col-span-1" label="Books by Type">
            <div class="w-full flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span v-if="countByType">{{ formatNumber(countByType.count) ?? 0 }}</span>
                    <span v-if="countByType" :class="[countByType.type === 'consignment' ? 'bg-lime-100 text-lime-700' : 'bg-stone-200 text-stone-700' ]" class="mt-0.5 text-sm px-2 rounded-lg font-medium">{{ countByType.type }}</span>
                </div>
                <svg @click="toggleCountType" :class="[countType === 'consignment' ? 'rotate-45' : '-rotate-45']" class="group-hover:opacity-100 opacity-0 transition duration-500 cursor-pointer" width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 5.99999H5.101L5.102 5.99099C5.23257 5.35162 5.48813 4.74434 5.854 4.20399C6.39845 3.4018 7.16215 2.77315 8.054 2.39299C8.356 2.26499 8.671 2.16699 8.992 2.10199C9.65789 1.96698 10.3441 1.96698 11.01 2.10199C11.967 2.29808 12.8451 2.7714 13.535 3.46299L14.951 2.05099C14.3128 1.41262 13.5578 0.903028 12.727 0.549986C12.3033 0.370615 11.8628 0.233939 11.412 0.141986C10.4818 -0.0470031 9.52316 -0.0470031 8.593 0.141986C8.14185 0.23432 7.70101 0.371329 7.277 0.550986C6.02753 1.08109 4.95793 1.96108 4.197 3.08499C3.68489 3.84284 3.32676 4.69398 3.143 5.58999C3.115 5.72499 3.1 5.86299 3.08 5.99999H0L4 9.99999L8 5.99999ZM12 7.99999H14.899L14.898 8.00799C14.6367 9.28975 13.8812 10.4171 12.795 11.146C12.2548 11.5122 11.6475 11.7677 11.008 11.898C10.3424 12.033 9.65656 12.033 8.991 11.898C8.35163 11.7674 7.74435 11.5119 7.204 11.146C6.93862 10.9665 6.69085 10.7622 6.464 10.536L5.05 11.95C5.68851 12.5882 6.44392 13.0974 7.275 13.45C7.699 13.63 8.142 13.767 8.59 13.858C9.51982 14.0471 10.4782 14.0471 11.408 13.858C13.2005 13.4859 14.7773 12.4294 15.803 10.913C16.3146 10.1557 16.6724 9.30525 16.856 8.40999C16.883 8.27499 16.899 8.13699 16.919 7.99999H20L16 3.99999L12 7.99999Z" fill="#6A727F"/>
                </svg>
            </div>
        </InfoCard>
        <InfoCard class="col-span-1" label="Categories" :content="formatNumber(statistics['categories_count']) ?? '0'" />
        <InfoCard class="col-span-1" label="Items Registered in Last 30 days" :content="formatNumber(statistics['count_last_month']) ?? '0'" />
    </div>
    <Table
        v-if="books"
        title="Books"
        :data="books"
        :center="['code', 'balance']"
        :right="[]"
        :sortable="['category', 'code', 'balance', 'title', 'purchase_type']"
        :searchable="['title', 'added_on']"
        :hideable="false"
        :hide="[]"
        :hide-labels="[]"
        :actions="true"
        @table-button-click="handleTableButton"
        @edit="handleEdit"
        @delete="handleDelete"
    >

        <template #description>
            <p>
                Complete list of books in the inventory
            </p>
        </template>

        <template #rows="data">

            <Cell name="code" :hide="data.hide" class="w-[2%] text-xs font-semibold text-subtitle text-center">{{ data['record']['code'].toLowerCase() }} </Cell>
            <Cell class="w-[90%]" name="title" :main="true" :hide="data.hide">{{ data['record'].title }} </Cell>
            <Cell class="w-[2%]" name="category" :hide="data.hide">{{ data['record']['category'] }} </Cell>
            <EnumCell class="w-[2%]" name="purchase_type" :hide="data.hide" :colors="['lime', 'stone']" :options="['consignment', 'cash']" :value="data['record']['purchase_type']" />
            <Cell name="balance" :hide="data.hide" class="w-[2%] text-xs font-semibold text-subtitle text-center">{{ data['record']['balance'] }} </Cell>
            <DateCell class="w-[2%]" name="added_on" :hide="data.hide" :value="data['record']['added_on']" />

        </template>

    </Table>

</template>

<script setup>

    import { onMounted, computed, ref } from "vue";
    import InfoCard from "../../components/InfoCard.vue"
    import Table from "../../components/Table/Table.vue"
    import Cell from "../../components/Table/Cell.vue"
    import EnumCell from "../../components/Table/EnumCell.vue"
    import DateCell from "../../components/Table/DateCell.vue"
    import store from "../../store"
    import { formatNumber } from "../../utils";

    const loading = ref(true)
    const books = computed(() => store.state.book.books)
    const statistics = computed(() => store.state.book.statistics)

    const countType = ref('consignment')
    const countByType = ref(null)
    function toggleCountType() {

        if (countType.value === 'consignment') {

            countByType.value.type = 'cash';
            countByType.value.count = statistics.value['count_by_type']['cash']
            countType.value = 'cash'

        } else {

            countByType.value.type = 'consignment';
            countByType.value.count = statistics.value['count_by_type']['consignment']
            countType.value = 'consignment'

        }

    }

    function handleTableButton() {

    }

    function handleEdit() {

    }

    function handleDelete() {

    }

    async function fetchBooks() {

        if (!! ! store.state.book.books) {
            console.log('Run getBooks dispatch')
            await store.dispatch('getBooks')
                .then()
                .finally(() => {
                    loading.value = false
                })
        } else {

            loading.value = false

        }

    }

    fetchBooks()

    onMounted(() => {

        store.dispatch('getBooksStats').then(() => {
            countByType.value = {
                'type': 'consignment',
                'count': statistics.value['count_by_type']['consignment']
            }
        })

    })

</script>

<style scoped>

</style>
