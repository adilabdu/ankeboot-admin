<template>

    <Teleport to="#top-view">

        <Modal>

            <Form
                class="w-1/2 sm:w-full sm:animate-slide-up-modal animate-scale-up"
                title="Search and Update Book"
                subtitle="Search the item you want to update by title or code and proceed to update details"
                submit-title="Choose"
                title-layout="contained"
                modal
                :submit="goToBookUpdate"
                @cancel="goBack"
            >

                <ItemSearchInput
                    required
                    :action="false"
                    v-model:form-data="formData"
                    v-model:selected="bookTitle"
                    selectable="id"
                    placeholder="Search for book item with (code, title)"
                    label="Book to Update"
                    :search-logic="searchBooks"
                />

            </Form>

        </Modal>

    </Teleport>

</template>

<script setup>

    import { ref } from "vue"
    import Modal from "../../../components/Modal.vue"
    import Form from "../../../components/Form/Form.vue"
    import ItemSearchInput from "../../../components/Form/ItemSearchInput.vue"
    import router from "../../../router/index"

    function goToBookUpdate() {
        router.push({ name: 'UpdateBook', params: { id: formData.value } })
    }

    function goBack() {
        router.go(-1)
    }

    const formData = ref(null)
    const bookTitle = ref(null)

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

</script>

<style scoped>

</style>
