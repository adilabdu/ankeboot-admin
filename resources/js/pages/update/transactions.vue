<template>

    <Form :mounting="!! ! transactionData.book_title" title-layout="panel" title="Register new Transaction" :submit="updateTransaction">

        <template #subtitle>
            Search through the book records and select the item to register a new transaction for.
            Do not forget to specify the transaction type (purchase / sale).
        </template>

        <TextInput disabled v-model="transactionData.book_title" class="w-full" placeholder="Book title for Transaction" label="Book for Transaction" />

        <div class="flex gap-8 w-full">
            <TextInput required v-model="transactionData.invoice" class="w-full" placeholder="Transaction receipt invoice number" label="Transaction Invoice" />
            <DatePicker required v-model="transactionData.date" class="w-full" label="Transaction date" />
        </div>

        <div class="flex items-center gap-8 w-full">
            <TextInput v-model="transactionData.quantity" required class="w-full" placeholder="Quantity of items in transaction" label="Item Quantity" />
            <SwitchInput class="px-3" label-location="top" v-model="transactionType.type" label="Purchase"/>
        </div>

    </Form>

</template>

<script setup>

    import { onMounted, ref } from "vue";
    import Form from "../../components/Form/Form.vue";
    import TextInput from "../../components/Form/TextInput.vue";
    import SwitchInput from "../../components/Form/SwitchInput.vue";
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

    onMounted(() => {

        getTransaction(useRoute().params.id).then(() => {

            const transaction = store.state.transaction.transaction.transaction

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

    })

</script>

<style scoped>

</style>
