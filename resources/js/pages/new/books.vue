<template>

    <Form title="Register new Book" :submit="createNewBook">

        <TextInput required label="Book title" placeholder="Insert new book title" v-model="bookData.title"/>
        <TextInput label="አማርኛ አርእስት" placeholder="የአማርኛ አርእስቱን ያስገቡ" v-model="bookData.alternate_title"/>

        <div class="flex gap-8 w-full">
            <TextInput v-model="bookData.author" class="w-full" placeholder="Book author" label="Book author" />
            <TextSearchInput
                :search-list="categories"
                v-model="bookData.category"
                required
                class="w-full"
                placeholder="Book category"
                label="Category"
            />
        </div>

        <div class="flex items-center gap-8 w-full">
            <TextInput v-model="bookData.code" required class="w-full" placeholder="Unique item code" label="Item code" />
            <SwitchInput v-model="bookData.type" label="Consignment"/>
        </div>

        <Collapsable open @open="toggleTransactionRegistration" :label="['Register first transaction', 'Register first transaction later']">

            <div class="border-b border-border-light w-full py-1 w-[95%] m-auto"></div>

            <div class="flex gap-8 w-full">
                <TextInput required v-model="transactionData.transaction_invoice" class="w-full" placeholder="Transaction receipt invoice number" label="Transaction Invoice" />
                <DatePicker required v-model="transactionData.transaction_on" class="w-full" label="Transaction date" />
            </div>

            <div class="flex items-center gap-8 w-full">
                <TextInput v-model="transactionData.transaction_quantity" required class="w-full" placeholder="Quantity of items in transaction" label="Item Quantity" />
                <SwitchInput v-model="transaction_type.transaction_type" label="Purchase"/>
            </div>

        </Collapsable>
        <Collapsable :open="false" @open="toggleSupplierRegistration" :label="['Register item supplier', 'Register item supplier later']">

            <div class="bg-wallpaper rounded-md text-subtitle font-medium flex w-full h-48 items-center justify-center">
                Supplier registration form
            </div>

        </Collapsable>

    </Form>
<!--    <Form-->
<!--        :show-submit="fileUploaded"-->
<!--        submit-title="Upload"-->
<!--        subtitle="Upload an excel or comma separated value (CSV) file to register multiple book records at once. Make sure to include all the necessary columns and follow the template to insert the data successfully."-->
<!--        title="Insert multiple Book records from CSV"-->
<!--        :submit="insertMultipleBooks"-->
<!--    >-->

    <form @submit.prevent="insertMultipleBooks" title="Form" submit="" class="w-full grid grid-cols-12 gap-2">
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
            <div class="w-full flex justify-end items-center">
                <button type="submit" class="px-4 bg-brand-primary w-fit text-white rounded-md py-2">Upload</button>
            </div>
        </div>
    </form>

<!--</Form>-->

</template>

<script setup>

    import { onMounted, ref, watch } from "vue";
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
    import { useRoute } from "vue-router";

    const fileUploaded = ref(false)
    const uploadFile = ref(null)

    watch(uploadFile, () => {
        fileUploaded.value = !!uploadFile.value;
    })

    const bookData = ref({
        title: '',
        alternate_title: '',
        author: '',
        category: '',
        type: true,
        code: '',
    })

    const transaction_type = { transaction_type: true }
    const transactionData = ref({
        transaction_invoice: '',
        transaction_quantity: '',
        transaction_on: {
            date: '',
            month: '',
            year: ''
        }
    })

    const categories = ref([])
    async function getCategories() {

        const response = await axios.get('/api/books/categories')

        if (response.data.message === 'ok') {
            categories.value = response.data.data
        } else {
            categories.value = []
        }

    }

    const registerTransaction = ref(true)
    function toggleTransactionRegistration(state) {
        registerTransaction.value = state
    }

    const registerSupplier = ref(false)
    function toggleSupplierRegistration(state) {
        registerSupplier.value = state
    }

    function createNewBook() {

        const payload = ref({})

        // TODO: find better implementation
        if (registerTransaction.value) {
            payload.value = {
                ...transaction_type,
                ...transactionData.value,
                ...bookData.value
            }
        } else {
            payload.value = {
                ...bookData.value
            }
        }

        console.table(payload.value)
        store.dispatch('postBook', payload.value)
            .then((response) => {

                if (response === 'ok') {

                    // TODO: Handle with a Notification
                    router.push({ name: 'Book', params: { id: store.state.book.book.id } })

                } else {

                    // TODO: Handle with a Notification
                    alert(response)

                }

            })
            .finally(() => {
            })

    }

    function insertMultipleBooks() {

        store.dispatch('postMultipleBooks', {
            file: uploadFile.value
        }).then((response) => {

            if (response === 'ok') {

                router.push({ name: 'Books' })

            } else {

                alert(response)

            }

        })
            .finally(() => {})
    }

    onMounted(() => {

        getCategories()

    })

</script>

<style scoped>

</style>
