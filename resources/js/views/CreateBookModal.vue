<template>

    <Teleport v-if="show" to="#top-view">

        <Modal>

            <div ref="newBookForm" class="w-[45%] sm:w-full sm:rounded-t-lg sm:overflow-auto sm:max-h-[75%] sm:animate-slide-up-modal animate-scale-up">
                <Form modal class="h-full w-full rounded-b-none" :submit="submitNewBook" @cancel="$emit('close')" title-layout="contained" title="Register a new Book">

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


                </Form>
            </div>

        </Modal>

    </Teleport>

</template>

<script setup>

    import { ref, onMounted } from "vue";
    import { onClickOutside } from "@vueuse/core";
    import Modal from "../components/Modal.vue"
    import Form from "../components/Form/Form.vue"
    import TextInput from "../components/Form/TextInput.vue"
    import TextSearchInput from "../components/Form/TextSearchInput.vue"
    import SwitchInput from "../components/Form/SwitchInput.vue"
    import store from "../store"

    const props = defineProps({
        show: {
            type: Boolean,
            default: false
        }
    })

    const emits = defineEmits(['close', 'data'])

    const newBookForm = ref(null)
    onClickOutside(newBookForm, () => {
        emits('close')
    })

    const bookData = ref({
        title: '',
        alternate_title: '',
        author: '',
        category: '',
        type: true,
        code: '',
    })
    function submitNewBook() {

        store.dispatch('postBook', bookData.value)
            .then((response) => {

                if (response.message === 'ok') {

                    // TODO: Handle with a Notification
                    store.dispatch('pushAlert', {
                        type: 'success',
                        message: `Item ${response.data.title} registered successfully`,
                    })
                    emits('data', response.data)

                } else {

                    // TODO: Handle with a Notification
                    store.dispatch('pushAlert', {
                        type: 'error',
                        message: response,
                    })

                }

            })
            .finally(() => {
                emits('close')
            })

    }

    const categories = ref([])
    async function getCategories() {

        const response = await axios.get('/api/books/categories')

        if (response.data.message === 'ok') {
            categories.value = response.data.data
        } else {
            categories.value = []
        }

    }

    onMounted(() => {

        getCategories()

    })

</script>

<style scoped></style>
