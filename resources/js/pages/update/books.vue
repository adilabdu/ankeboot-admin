<template>

    <Form title="Register new Book" :submit="updateBook">

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
            <SwitchInput class="px-3" label-location="top" v-model="bookData.type" label="Consignment"/>
        </div>

        <Collapsable :open="false" @open="toggleSupplierRegistration" :label="['Register item supplier', 'Register item supplier later']">

            <div class="bg-wallpaper rounded-md text-subtitle font-medium flex w-full h-48 items-center justify-center">
                Supplier registration form
            </div>

        </Collapsable>

    </Form>

</template>

<script setup>

    import { computed, onMounted, ref } from "vue";
    import Form from "../../components/Form/Form.vue";
    import TextInput from "../../components/Form/TextInput.vue";
    import TextSearchInput from "../../components/Form/TextSearchInput.vue";
    import SwitchInput from "../../components/Form/SwitchInput.vue";
    import store from "../../store"
    import router from "../../router"
    import Collapsable from "../../components/Collapsable.vue";
    import DatePicker from "../../components/Form/DatePicker.vue";
    import { useRoute } from "vue-router";

    const loading = computed(() => store.state.book.loading)

    const bookData = ref({
        id: '',
        title: '',
        alternate_title: '',
        author: '',
        category: '',
        type: true,
        code: '',
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

    const registerSupplier = ref(false)
    function toggleSupplierRegistration(state) {
        registerSupplier.value = state
    }

    async function getBook(id) {

        await store.dispatch('getBook', id)

    }

    function updateBook() {

        const payload = ref({})

        // TODO: find better implementation
        payload.value = {
            ...bookData.value
        }

        console.table(payload.value)
        store.dispatch('updateBook', payload.value)
            .then((response) => {

                if (response.message === 'ok') {

                    // TODO: Handle with a Notification
                    store.dispatch('pushAlert', {
                        type: 'success',
                        message: `Item ${response.data.title} updated successfully`,
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

    onMounted(() => {

        getCategories()
        getBook(useRoute().params.id).then(() => {

            console.log(store.state.book.book)
            const book = store.state.book.book

            bookData.value.id = book.id
            bookData.value.title = book.title
            bookData.value.alternate_title = book.alternate_title
            bookData.value.author = book.author
            bookData.value.category = book.category
            bookData.value.type = book.type === 'cash' ? false : true
            bookData.value.code = book.code

        })

    })

</script>

<style scoped>

</style>
