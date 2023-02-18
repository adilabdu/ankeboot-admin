<template>

    <Form :mounting="!! ! transactionData.book_title" title-layout="panel" title="Register new Transaction" :submit="updateTransaction">

        <template #subtitle>
            Search through the book records and select the item to register a new transaction for.
            Do not forget to specify the transaction type (purchase / sale).
        </template>

        <div class="flex gap-8 w-full">
            <TextInput disabled v-model="transactionData.book_title" class="w-full" placeholder="Book title for Transaction" label="Book for Transaction" />
            <SwitchInput class="px-3" label-location="top" v-model="transactionType.type" label="Purchase"/>
        </div>

        <div class="flex gap-8 w-full">
            <TextInput required v-model="transactionData.invoice" class="w-full" placeholder="Transaction receipt invoice number" label="Transaction Invoice" />
            <DatePicker required v-model="transactionData.date" class="w-full" label="Transaction date" />
        </div>

<!--        <div class="flex items-center gap-8 w-full">-->
<!--            <TextInput v-model="transactionData.quantity" required class="w-full" placeholder="Quantity of items in transaction" label="Item Quantity" />-->
<!--        </div>-->

        <!-- Store Transactions -->
        <div class="gap-6 flex flex-col">

            <div class="flex items-center justify-start w-full gap-2 px-2">
                <p class="text-xs text-subtitle font-medium whitespace-nowrap">Store Transactions</p>
                <div class="border-b border-border-light w-full py-1 mb-2 w-full m-auto"></div>
            </div>

            <template v-for="(store, index) in storeData" :key="index">

                <div class="flex gap-8 w-full">
                    <Dropdown
                        class="!w-full h-16"
                        :hide-label-on-mobile=false
                        required
                        v-model="store.id"
                        :selected="store.name"
                        :list="stores"
                        :resource_list="{ display: 'name', value: 'id' }"
                        placeholder="Choose Warehouse"
                        label-direction="top"
                        label="Warehouse"
                    />
                    <TextInput required v-model="store.quantity" class="w-full" placeholder="Transaction quantity" label="Transaction Quantity" />
                </div>

            </template>

        </div>

    </Form>

</template>

<script setup>

    import { onMounted, ref } from "vue";
    import Form from "../../components/Form/Form.vue";
    import TextInput from "../../components/Form/TextInput.vue";
    import SwitchInput from "../../components/Form/SwitchInput.vue";
    import Dropdown from "../../components/Dropdown.vue"
    import store from "../../store"
    import DatePicker from "../../components/Form/DatePicker.vue";
    import { useRoute } from "vue-router";
    import router from "../../router";

    const transactionType = ref({
        type: false
    })
    const transactionData = ref({
        id: '',
        book_id: '',
        date: {
            date: '',
            month: '',
            year: ''
        },
        quantity: '',
        invoice: ''
    })
    const storeData = ref([])

    async function getTransaction(id) {

        await store.dispatch('getTransaction', id)

    }

    function updateTransaction() {

        const payload = {
            ...transactionData.value,
            ...transactionType.value
        }

        store.dispatch('updateTransaction', payload)
            .then((response) => {

                if (response.message === 'ok') {

                    // TODO: Handle with a Notification
                    store.dispatch('pushAlert', {
                        type: 'success',
                        message: `Transaction for ${response.data.book.title} updated successfully`,
                    })
                    store.dispatch('getBooks')
                        .then(() => {
                            router.push({ name: 'Book', params: { id: response.data.book.id } })
                        })

                } else {

                    // TODO: Handle with a Notification
                    store.dispatch('pushAlert', {
                        type: 'error',
                        message: response.data,
                    })

                }

                })
            .finally(() => {
            })

    }

    const stores = ref([])
    const loadingStore = ref(true)
    async function getStores() {

        return await axios.get('/api/stores')
            .then(response => {
                stores.value = response.data.data
            })
            .catch(error => {
                alert('Error fetching Stores list ', error.data.message)
            })
            .finally(() => {
                loadingStore.value = false
            })
    }

    onMounted(() => {

        getTransaction(useRoute().params.id).then(() => {

            const transaction = store.state.transaction.transaction.transaction
            storeData.value = store.state.transaction.transaction.stores.map(store => {

                return {
                    id: store.id,
                    name: store.store.name,
                    quantity: store.quantity,
                }

            })

            transactionData.value.id = transaction.id
            transactionType.value.type = transaction.type === 'purchase'
            transactionData.value.book_title = transaction.book.title
            transactionData.value.book_id = transaction.id
            transactionData.value.quantity = transaction.quantity
            transactionData.value.invoice = transaction.invoice
            transactionData.value.date = {
                date: new Date(transaction.transaction_on).getDate(),
                month: new Date(transaction.transaction_on).getMonth(),
                year: new Date(transaction.transaction_on).getFullYear(),
            }

        })

        getStores()

    })

</script>

<style scoped>

</style>
