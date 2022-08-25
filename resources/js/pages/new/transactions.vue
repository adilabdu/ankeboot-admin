<template>

    <Form title="Register new Transaction" :submit="createNewTransaction">

        <template #subtitle>
            Search through the book records and select the item to register a new transaction for.
            Do not forget to specify the transaction type (purchase / sale).
        </template>

        <ItemSearchInput
            required
            v-model="transactionData.book_id"
            selectable="id"
            placeholder="Search for book item with (code, title)"
            label="Book for Transaction"
        />

        <div class="flex gap-8 w-full">
            <TextInput required v-model="transactionData.invoice" class="w-full" placeholder="Transaction receipt invoice number" label="Transaction Invoice" />
            <DatePicker required v-model="transactionData.date" class="w-full" label="Transaction date" />
        </div>

        <div class="flex items-center gap-8 w-full">
            <TextInput v-model="transactionData.quantity" required class="w-full" placeholder="Quantity of items in transaction" label="Item Quantity" />
            <SwitchInput class="px-3" label-location="top" v-model="transactionType.type" label="Purchase"/>
        </div>

    </Form>

    <form @submit.prevent="insertMultipleTransactions" title="Form" submit="" class="w-full grid grid-cols-12 gap-2">
        <div class="col-span-4 py-2 flex flex-col gap-1">
            <h1 class="text-base">Insert multiple Book records from CSV</h1>
            <slot name="subtitle">
                <h2 class="text-subtitle">
                    Upload an excel or comma separated value (CSV) file
                    to register multiple book records at once. Make sure to
                    include all the necessary columns and follow the
                    template to insert the data successfully.
                </h2>
            </slot>
        </div>
        <div class="flex flex-col col-span-8 items-end gap-6">
            <FileInput class="w-full " v-model="uploadFile" />
            <div class="w-full flex justify-end items-center px-6">
                <button type="submit" class="px-4 bg-brand-primary w-fit text-white rounded-md py-2">
                    Upload
                </button>
            </div>
        </div>
    </form>

    <Teleport v-if="!! loading" to="#top-view">

        <Modal>

            <div class="w-full h-full flex items-end">

                <!-- Loading indicator -->
                <div class="flex w-full items-center justify-center min-h-[6rem] gap-2">

                    <div class="flex gap-2 bg-white/75 rounded-full px-4 py-2">
                        <svg class="animate-rotate" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.4706 5.94533C18.1987 5.55015 18.4769 4.63079 17.9774 3.96992C17.0157 2.69771 15.7589 1.66786 14.3065 0.974827C12.4006 0.0653761 10.2557 -0.217352 8.1792 0.167163C6.10271 0.551679 4.20125 1.5837 2.74734 3.11531C1.29344 4.64692 0.361732 6.59949 0.0857576 8.69317C-0.190217 10.7869 0.203708 12.9142 1.21108 14.7702C2.21844 16.6262 3.78753 18.1157 5.69346 19.0252C7.59939 19.9346 9.74431 20.2174 11.8208 19.8328C13.4032 19.5398 14.8839 18.8708 16.1424 17.8912C16.7961 17.3824 16.7656 16.4223 16.1648 15.852V15.852C15.5639 15.2816 14.621 15.3229 13.9359 15.7887C13.1386 16.3308 12.2332 16.7055 11.2746 16.883C9.82102 17.1521 8.31958 16.9542 6.98542 16.3176C5.65127 15.681 4.55291 14.6384 3.84775 13.3391C3.1426 12.0399 2.86685 10.5508 3.06003 9.08522C3.25321 7.61964 3.90541 6.25284 4.92314 5.18072C5.94087 4.10859 7.27189 3.38618 8.72544 3.11701C10.179 2.84785 11.6804 3.04576 13.0146 3.68238C13.8945 4.10224 14.6718 4.69872 15.3015 5.42894C15.8424 6.05634 16.7425 6.34051 17.4706 5.94533V5.94533Z" fill="#FF8100"/>
                        </svg>

                        <span class="text-subtitle font-medium">
                            Populating transactions record.

                            <span class="font-normal">This might take a few seconds</span>

                        </span>
                    </div>

                </div>

            </div>

        </Modal>

    </Teleport>

</template>

<script setup>

    import { onMounted, ref, computed } from "vue";
    import ContentPage from "../../layouts/content-page.vue";
    import Form from "../../components/Form/Form.vue";
    import TextInput from "../../components/Form/TextInput.vue";
    import TextSearchInput from "../../components/Form/TextSearchInput.vue";
    import SwitchInput from "../../components/Form/SwitchInput.vue";
    import store from "../../store"
    import router from "../../router"
    import Collapsable from "../../components/Collapsable.vue";
    import DatePicker from "../../components/Form/DatePicker.vue";
    import FileInput from "../../components/Form/FileInput.vue";
    import Modal from "../../components/Modal.vue";
    import ItemSearchInput from "../../components/Form/ItemSearchInput.vue"

    const books = computed(() => store.state.book.books)

    const transactionType = ref({
        type: false
    })
    const transactionData = ref({
        book_id: '',
        date: {
            date: '',
            month: '',
            year: ''
        },
        quantity: '',
        invoice: ''
    })

    const loading = computed(() => store.state.book.loading)
    const uploadFile = ref(null)

    function insertMultipleTransactions() {

        store.dispatch('postMultipleTransactions', {
            file: uploadFile.value
        }).then((response) => {

            if (response.message === 'ok') {

                // TODO: Handle with a Notification
                store.dispatch('getTransactions')
                    .then(() => {
                        router.push({ name: 'Transactions' })
                        store.dispatch('pushAlert', {
                            type: 'success',
                            message: response.data
                        })
                    })

            } else if (response.message === 'error') {

                store.dispatch('pushAlert', {
                    type: 'error',
                    message: response.data
                })

            } else {

                store.dispatch('pushAlert', {
                    type: 'error',
                    message: response.message
                })

            }

        })
            .finally(() => {})
    }

    function createNewTransaction() {

        store.dispatch('postTransaction', {
            ...transactionData.value,
            ...transactionType.value
        }).then(response => {

            if (response.message === 'ok') {

                store.dispatch('pushAlert', {
                    type: 'success',
                    message: `Transaction successfully added for ${response.data.book.title}`
                }).then(() => {

                    store.dispatch('getBooks')
                        .then(() => {
                            router.push({ name: 'Book', params: { id: response.data.book.id } })
                        })

                })

            } else if (response.message === 'error') {

                store.dispatch('pushAlert', {
                    type: 'error',
                    message: response.data
                })

            } else {

                store.dispatch('pushAlert', {
                    type: 'error',
                    message: response
                })

            }

        })

    }

    onMounted(() => {

    })

</script>

<style scoped>

</style>
