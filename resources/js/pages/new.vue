<template>

    <ContentPage>

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

            <Collapsable />

        </Form>

    </ContentPage>

</template>

<script setup>

    import { onMounted, ref } from "vue";
    import ContentPage from "../layouts/content-page.vue";
    import Form from "../components/Form/Form.vue";
    import TextInput from "../components/Form/TextInput.vue";
    import TextSearchInput from "../components/Form/TextSearchInput.vue";
    import SwitchInput from "../components/Form/SwitchInput.vue";
    import store from "../store"
    import Collapsable from "../components/Collapsable.vue";

    const bookData = ref({
        title: '',
        alternate_title: '',
        author: '',
        category: '',
        type: false,
        code: '',
        balance: 0
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

    function createNewBook() {

        console.table(bookData.value)
        store.dispatch('postBook', bookData.value)

    }

    onMounted(() => {

        getCategories()

    })

</script>

<style scoped>

</style>
