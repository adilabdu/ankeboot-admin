<template>

    <div class="lg:w-full w-1/2 flex flex-col gap-6 bg-white rounded-xl shadow-sm bor min-h-[12rem] p-6">

        <div class="flex flex-col">
            <h3 class="font-medium text-base">Quick Transfer</h3>
            <h5 class="text-subtitle">Transfer a book title from warehouse to a primary store</h5>
        </div>

        <form class="flex flex-col gap-5 w-full" @submit.prevent="submitTransfer" title="Quick transfer">

            <ItemSearchInput
                required
                :action="false"
                v-model:form-data="formData.book_id"
                v-model:selected="book_title"
                selectable="id"
                placeholder="Search for book item with (code, title)"
                label="Book for Transaction"
                :search-logic="searchBooks"
            />

            <Dropdown
                class="!w-full h-16"
                :hide-label-on-mobile=false
                required
                v-model="formData.from"
                :list="stores"
                :resource_list="{ display: 'name', value: 'id' }"
                placeholder="Choose Warehouse"
                label-direction="top"
                label="Warehouse"
            />

            <TextInput
                required
                :caption="caption"
                v-model="formData.quantity"
                class="w-full"
                placeholder="Set the amount of items to transfer"
                label="Transfer Amount"
            />

            <div class="w-full flex justify-between items-center mt-2">
                <p class="text-subtitle text-xs px-2">
                    <span class="text-red-600 text-sm">*</span>
                    Required fields
                </p>
                <div class="flex gap-4">
                    <button
                        type="button"
                        @click="clearForm()"
                        class="px-4 text-brand-primary w-fit rounded-md py-2"
                    >
                        Clear
                    </button>
                    <button
                        :disabled="! formFilled"
                        type="submit"
                        class="disabled:opacity-50 px-4 bg-brand-primary w-fit text-white rounded-md py-2"
                    >
                        Transfer
                    </button>
                </div>
            </div>

        </form>

    </div>

</template>

<script setup>

    import { ref, onMounted, watch, computed } from "vue";
    import ItemSearchInput from "../components/Form/ItemSearchInput.vue"
    import Dropdown from "../components/Dropdown.vue"
    import TextInput from "../components/Form/TextInput.vue"
    import store from "../store"

    const formData = ref({
        from: '',
        book_id: '',
        quantity: ''
    })
    const book_title = ref('')
    const stores = ref([])
    const loadingStore = ref(true)
    async function getStores() {

        return await axios.get('/api/stores')
            .then(response => {
                stores.value = response.data.data.filter(store => { return ! store.primary })
            })
            .catch(error => {
                alert('Error fetching Stores list ', error.data.message)
            })
            .finally(() => {
                loadingStore.value = false
            })
    }

    async function searchBooks(payload) {

        return await axios.get('/api/books/search', {
            params: {
                query: payload
            }
        }).then(response => {

            if (response.data.message === 'ok') {

                return response.data.data

            } else {

                alert(response.data.message)

            }

        })
    }

    async function getMaximumTransfer() {

        console.log(formData.value)

        return await axios.get('/api/stores/balance', {
            params: {
                book_id: formData.value.book_id,
                store_id: formData.value.from
            }
        })
            .then(response => {

                return response.data.data

            })
            .catch(error => {

                alert(`Error while fetching store book balance: ${error}`)

            })

    }

    const caption = ref('')
    watch(formData, () => {

        // alert('hello world from watcher')

        if (formData.value.from && formData.value.book_id) {
            getMaximumTransfer()
                .then(response => {

                    caption.value = `Possible transfer amount is ${response} amount(s).`

                })
        } else {

            caption.value = ''

        }

    }, {
        deep: true
    })

    function submitTransfer() {

        console.log(formData.value)

        axios.post('/api/stores/transfer', formData.value)
            .then(response => {

                if (response.data.message === 'ok') {
                    store.dispatch('pushAlert', {
                        type: 'success',
                        message: `Items successfully transferred to primary store`,
                    })
                }

            })
            .catch(error => {

                alert(`Error while processing transfer to primary store: ${error}`)

            })
            .finally(() => {

                clearForm()

            })

    }

    function clearForm() {
        formData.value = {
            from: '',
            book_id: '',
            quantity: ''
        }
        book_title.value = ''
    }

    const formFilled = computed(() => {

        return !! formData.value.book_id &&
            !! formData.value.quantity &&
            !! formData.value.from

    })

    onMounted(() => {
        getStores()
    })

</script>
