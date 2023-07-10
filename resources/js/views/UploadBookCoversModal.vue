<template>

    <Modal>

        <Form class="container max-w-4xl mx-auto" modal title-layout="contained" :submit="submitCovers" title="Upload Book Covers" subtitle="Upload the front and back covers of the book">

            <div class="flex gap-4 w-full">

                <div class="flex flex-col w-full gap-2">
                    <h3 class="font-semibold text-subtitle">Front cover</h3>
                    <template v-if="!!!covers['front_cover'] || !!replaceFrontCover">
                        <FileInput v-model="frontCover" accept="image/png,image/jpeg,image/jpg" class="h-[28rem]" />
                    </template>
                    <template v-else>
                        <div class="group bg-wallpaper border-2 border-dashed grid place-items-center relative h-[28rem] border-border-light rounded-md p-4">

                            <button @click="replaceFrontCover=true" type="button" class="absolute grid place-items-center right-4 top-4 bg-brand-secondary opacity-0 group-hover:opacity-100 hover:bg-brand-primary rounded-full w-5 h-5">
                                <span class="w-[2px] h-3 bg-white rotate-45 absolute" />
                                <span class="w-[2px] h-3 bg-white -rotate-45 absolute" />
                            </button>

                            <img :src="covers['front_cover']" class="object-contain" alt="Front cover">
                        </div>
                    </template>
                </div>

                <div class="flex flex-col w-full gap-2">
                    <h3 class="font-semibold text-subtitle">Back cover</h3>
                    <template v-if="!!!covers['back_cover'] || !!replaceBackCover">
                        <FileInput v-model="backCover" accept="image/png,image/jpeg,image/jpg" class="h-[28rem]" />
                    </template>
                    <template v-else>
                        <div class="group bg-wallpaper border-2 border-dashed grid place-items-center relative h-[28rem] border-border-light rounded-md p-4">

                            <button @click="replaceBackCover=true" type="button" class="absolute grid place-items-center right-4 top-4 bg-brand-secondary opacity-0 group-hover:opacity-100 hover:bg-brand-primary rounded-full w-5 h-5">
                                <span class="w-[2px] h-3 bg-white rotate-45 absolute" />
                                <span class="w-[2px] h-3 bg-white -rotate-45 absolute" />
                            </button>

                            <img :src="covers['back_cover']" class="object-contain" alt="Back cover">
                        </div>
                    </template>
                </div>
            </div>

        </Form>

    </Modal>

</template>

<script setup>
import Modal from "../components/NewModal.vue";
import Form from "../components/Form/Form.vue";
import FileInput from "../components/Form/FileInput.vue";
import {computed, ref} from "vue";
import { useRoute } from "vue-router";
import store from "../store/index.js";

const props = defineProps({
    covers: {
        type: Object,
        default: () => ({
            front_cover: null,
            back_cover: null,
        })
    }
})

const emits = defineEmits(['success'])

const route = useRoute();
const bookId = computed(() => route.params.id);

const frontCover = ref(null);
const backCover = ref(null);

const replaceFrontCover = ref(false)
const replaceBackCover = ref(false)

function submitCovers() {

    axios.post('/api/books/covers', {
        book_id: bookId.value,
        front_cover: frontCover.value,
        back_cover: backCover.value,
    }, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then(() => {

        emits('success')
        store.dispatch('pushAlert', {
            type: 'success',
            message: `Book covers updated successfully`,
        })

    })

}
</script>

<style scoped>

</style>
