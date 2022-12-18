<template>

    <Form title="Register new Book" :submit="createNewBook">

        <TextInput required label="Book title" placeholder="Insert new book title" v-model="bookData.title"/>
        <TextInput label="አማርኛ አርእስት" placeholder="የአማርኛ አርእስቱን ያስገቡ" v-model="bookData.alternate_title"/>

        <div class="flex sm:flex-col gap-8 w-full">
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
            <SwitchInput class="px-3" label-location="top" v-model="bookData.type" label="Consignment"/>
        </div>

        <Collapsable :open="false" collapse-direction="down" @open="toggleTransactionRegistration" :label="['Register first transaction', 'Register first transaction']">

            <div class="bg-wallpaper rounded-md text-subtitle font-medium flex gap-2 items-center w-full h-48 items-center justify-center">
                <p>First transaction registration form</p>
                <label class="text-2xs leading-none px-1.5 py-0.5 mt-0.5 font-semibold rounded-md text-red-600 bg-red-200">beta</label>
            </div>

        </Collapsable>
        <Collapsable :open="false" collapse-direction="down" @open="toggleSupplierRegistration" :label="['Register item supplier', 'Register item supplier later']">

            <div class="bg-wallpaper rounded-md text-subtitle font-medium flex w-full h-48 items-center justify-center">
                Supplier registration form
            </div>

            <div class="bg-wallpaper rounded-md text-subtitle font-medium flex gap-2 items-center w-full h-48 items-center justify-center">
                <p>First transaction registration form</p>
                <label class="text-2xs leading-none px-1.5 py-0.5 mt-0.5 font-semibold rounded-md text-red-600 bg-red-200">beta</label>
            </div>

        </Collapsable>

    </Form>

    <form @submit.prevent="insertMultipleBooks" title="Insert Multiple Book " submit="" class="w-full sm:flex sm:flex-col grid grid-cols-12 gap-2">
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
                            Populating books record.

                            <span class="font-normal">This might take a few seconds</span>

                        </span>
                    </div>

                </div>

            </div>

        </Modal>

    </Teleport>

</template>

<script setup>

    import { onMounted, ref, watch, computed } from "vue";
    import Form from "../../components/Form/Form.vue";
    import TextInput from "../../components/Form/TextInput.vue";
    import TextSearchInput from "../../components/Form/TextSearchInput.vue";
    import SwitchInput from "../../components/Form/SwitchInput.vue";
    import Collapsable from "../../components/Collapsable.vue";
    import DatePicker from "../../components/Form/DatePicker.vue";
    import FileInput from "../../components/Form/FileInput.vue";
    import store from "../../store"
    import router from "../../router"
    import Modal from "../../components/Modal.vue";

    const loading = computed(() => store.state.book.loading)
    const uploadFile = ref(null)

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

                if (response.message === 'ok') {

                    // TODO: Handle with a Notification
                    store.dispatch('pushAlert', {
                        type: 'success',
                        message: `Item ${response.data.title} registered successfully`,
                    })
                    store.dispatch('getBooks')
                        .then(() => {
                            router.push({ name: 'Book', params: { id: response.data.id } })
                        })

                } else {

                    // TODO: Handle with a Notification
                    store.dispatch('pushAlert', {
                        type: 'error',
                        message: response,
                    })

                }

            })
            .finally(() => {
            })

    }

    function insertMultipleBooks() {

        store.dispatch('postMultipleBooks', {
            file: uploadFile.value
        }).then((response) => {

            if (response.message === 'ok') {

                // TODO: Handle with a Notification
                store.dispatch('getBooks')
                    .then(() => {
                        router.push({ name: 'Books' })
                        store.dispatch('pushAlert', {
                            type: 'success',
                            message: response.data
                        })
                    })

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
