<template>

    <div class="grid grid-cols-12 w-full">
        <div class="col-span-8 gap-8 flex flex-col">
            <div class="flex flex-col gap-4">
                <!-- <div class="flex justify-between w-full">
                    <div class="flex flex-col">
                        <h1 v-if="!! book" class="font-medium text-lg">{{ book.title }}</h1>
                        <h2 class="text-subtitle">Item Information and Stock Record</h2>
                    </div>
                </div> -->
                <ItemCard
                    v-if="!loading"
                    class=""
                    :title="book.title"
                    :heading="true"
                >

                    <template #action>
                        <button @click="goToUpdate" class="focus:outline-brand-primary focus:outline-offset-2 h-fit md:w-fit px-6 py-2.5 bg-brand-primary rounded-lg text-white flex justify-between sm:justify-center items-center gap-2 sm:w-full">
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
                        <ItemDescription :description="book['alternate_title']" label="alternate title" />
                        <ItemDescription :description="book.author" label="author" />
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
                            <p v-else class="text-brand-primary text-xs cursor-pointer">Register a Supplier</p>
                        </ItemDescription>
                        <ItemDescription :description="new Date(book['created_at']).toDateString()" label="registration date" />
                        <ItemDescription :description="new Date(book['updated_at']).toDateString()" label="last updated" />
                    </template>

                </ItemCard>
            </div>
            <Table
                v-if="book"
                title="Stock record"
                :data="book.transactions"
                :center="['type', 'date', 'invoice']"
                :right="[]"
                :sortable="[]"
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
                    <EnumCell class="w-[4%]" name="type" :hide="data.hide" :colors="['red', 'green']" :options="['sale', 'purchase']" :value="data['record']['type']" />
                    <DateCell class="w-[4%]" name="date" :hide="data.hide" :value="data['record']['date']" />

                </template>

            </Table>
        </div>
    </div>

</template>

<script setup>

    import { ref, computed, onMounted, onBeforeUnmount } from "vue";
    import Table from "../../components/Table/Table.vue";
    import Cell from "../../components/Table/Cell.vue";
    import EnumCell from "../../components/Table/EnumCell.vue";
    import DateCell from "../../components/Table/DateCell.vue";
    import ItemCard from "../../components/ItemCard.vue";
    import ItemDescription from "../../components/ItemDescription.vue";
    import { useRoute } from "vue-router"
    import router from "../../router"
    import store from "../../store"

    const loading = ref(true)
    const book = computed(() => store.state.book.book)
    const searchable = ref(['invoice', 'date'])

    async function fetchBook() {

        await store.dispatch('getBook', useRoute().params.id)
            .then(() => {
                console.log('getBook returns ', store.state.book.book)
            })
            .finally(() => {
                loading.value = false
            })

    }

    fetchBook()

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

    onBeforeUnmount(() => {

        store.dispatch('destroyBook')

    })

</script>

<style scoped>

</style>
