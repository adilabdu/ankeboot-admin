<template>

    <p class="text-subtitle font-medium">Update an existing Transaction Form</p>

</template>

<script setup>

    import { onMounted, ref } from "vue";
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

    const bookData = ref({
        title: 'A Book',
        alternate_title: '',
        author: 'An Author',
        category: 'Fake',
        type: true,
        code: 'fake2000',
    })

    const transaction_type = { transaction_type: true }
    const transactionData = ref({
        transaction_invoice: 'fake2000',
        transaction_quantity: '2000',
        transaction_on: {
            date: '20',
            month: '11',
            year: '2022'
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

    onMounted(() => {

        getCategories()

    })

</script>

<style scoped>

</style>
