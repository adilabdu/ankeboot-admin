<template>

    <div class="w-3/5 gap-8 flex flex-col">
        <ItemCard
            v-if="!loading"
            class=""
            :title="book.title"
            heading
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

<!--            <template #summary="data">-->
<!--                <div class="flex flex-col border-border-light border-[1px] sm:w-full w-1/2 bg-white shadow-sm rounded-md">-->
<!--                    <ItemDescription label="transactions" size="sm">-->
<!--                        {{ data.data.length }}-->
<!--                    </ItemDescription>-->
<!--                    <ItemDescription label="Date Range" size="sm">-->
<!--                        <span v-if="data['dateQuery']['start']">{{ data['dateQuery']['start']['date'] }}/{{ data['dateQuery']['start']['month'] + 1 }}/{{ data['dateQuery']['start']['year'] }}</span>-->
<!--                        <span v-if="data['dateQuery']['start'] || data['dateQuery']['end']"> - </span>-->
<!--                        <span v-if="data['dateQuery']['end']">{{ data['dateQuery']['end']['date'] }}/{{ data['dateQuery']['end']['month'] + 1 }}/{{ data['dateQuery']['end']['year'] }}</span>-->
<!--                        <span v-if="(!data['dateQuery']['start'] && !data['dateQuery']['end'])" class="">All</span>-->
<!--                    </ItemDescription>-->
<!--                    <ItemDescription label="Balance" size="sm">-->
<!--                        {{ data.data.reduce((prev, next) => { let sign; next['type'] === 'purchase' ? sign = 1 : sign = -1; return (prev) + (sign * next['quantity'])  }, 0) + ' copies' }}-->
<!--                    </ItemDescription>-->
<!--                </div>-->
<!--            </template>-->

        </Table>
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
                console.log('fetchBook finally ', book.value.title)
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
