<template>

    <div class="flex w-full gap-4">

        <File class="w-1/2" :date="formatDate(form.document_date)" :no="form.ref_no" :title="form.title">

            <Markdown :source="form.content" />

        </File>

        <Form :submit="submitFileForm" class="w-1/2" title="Fill file information" title-layout="contained">

            <template #subtitle>
                Add in the missing information to complete the file
            </template>

            <div class="flex w-full gap-2">
                <DatePicker class="grow" v-model="form.document_date" label="Document Dated" />
                <TextInput class="grow" v-model="form.ref_no" label="Document No." placeholder="Document Reference No." />
            </div>

            <TextInput v-model="form.title" label="Title of Document" placeholder="Provide the title of the new document" />
            <TextAreaInput v-model="form.content" label="Content of Document" placeholder="Provide the content of the new document" />

        </Form>

    </div>

</template>

<script setup>

    import { ref, computed } from "vue"
    import { formatDate } from "../../utils"
    import File from "../../layouts/file.vue"
    import Form from "../../components/Form/Form.vue"
    import TextInput from "../../components/Form/TextInput.vue"
    import TextAreaInput from "../../components/Form/TextAreaInput.vue"
    import DatePicker from "../../components/Form/DatePicker.vue"
    import Markdown from 'vue3-markdown-it'

    const form = ref({
        title: "",
        content: "",
        document_date: {
            date: '',
            month: '',
            year: ''
        },
        ref_no: null,
    })

    function submitFileForm() {

    }

</script>