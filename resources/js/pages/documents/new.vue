<template>

    <div class="flex w-full gap-4">

        <File class="w-1/2" :date="formatDate(form.document_date)" :no="form.ref_no" :title="form.title">

            <Markdown v-if="form.content" :source="form.content" />
            <div class="flex flex-col" v-else>
                <div :class="[focused.content ? 'animate-pulse bg-gray-400/75' : '']" class="w-full bg-gray-400/25 inline-block h-56" />
                <div :class="[focused.content ? 'animate-pulse bg-gray-400/75' : '']" class="w-3/4 bg-gray-400/25 inline-block h-5" />
            </div>

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
            <TextAreaInput @focusout="toggleFocus('content')" @focusin="toggleFocus('content')" v-model="form.content" label="Content of Document" placeholder="Provide the content of the new document" />

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
            date: new Date().getDate(),
            month: new Date().getMonth(),
            year: new Date().getFullYear()
        },
        ref_no: null,
    })

    const focused = ref({
        content: false
    })
    function toggleFocus(field) {
        
        switch (field) {

            case 'content':
                focused.value.content = !focused.value.content
                break;

            default:
                break;

        }

    }

    function submitFileForm() {

    }

</script>