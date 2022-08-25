<template>

    <div class="grid grid-cols-12 w-full">
        <div class="col-span-8 gap-8 flex flex-col">
            <div class="flex flex-col gap-4">
                <div class="flex justify-between w-full">
                    <div class="flex flex-col">
                        <h1 v-if="!! book" class="font-medium text-lg">{{ book.title }}</h1>
                        <h2 class="text-subtitle">Item Information and Stock Record</h2>
                    </div>
                </div>
                <ItemCard
                    v-if="!loading"
                    class=""
                    :title="book.title"
                    :heading="false"
                >

                    <template #rows>
                        <ItemDescription :description="book.code" label="code" />
                        <ItemDescription :description="book.title" label="title" />
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
                :hide="[]"
                :hide-labels="[]"
                :actions="false"
                @table-button-click="handleTableButton"
                @edit="handleEdit"
                @delete="handleDelete"
            >

                <template #action>

                    <template></template>

                </template>

                <template #description>
                    <p>
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
    import store from "../../store"

    const loading = ref(true)
    const book = computed(() => store.state.book.book)
    const searchable = ref(['invoice', 'date'])

    async function fetchBook() {

        await store.dispatch('getBook', useRoute().params.id)
            .then()
            .finally(() => {
                loading.value = false
            })

    }

    fetchBook()

    function handleTableButton() {

    }

    function handleEdit() {

    }

    function handleDelete() {

    }

    onBeforeUnmount(() => {

        store.dispatch('destroyBook')

    })

</script>

<style scoped>

</style>
