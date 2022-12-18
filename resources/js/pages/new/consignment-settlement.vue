<template>

    <Teleport v-if="show" to="#top-view">

        <Modal>

            <div ref="settlementModal" class="form-modal w-[45%] sm:w-full sm:rounded-t-lg sm:overflow-auto sm:max-h-[75%] sm:animate-slide-up-modal animate-scale-up">

                <Form modal @cancel="$emit('close')" :submit="submitNewSettlement" class="h-full w-full rounded-b-none" title="Make a new settlement" title-layout="contained">

                    <div class="w-full flex sm:flex-col gap-6">
                        <DatePicker class="sm:w-full w-1/2" label="Settlement date" required v-model="newSettlement.transaction_on" />
                        <TextInput class="sm:w-full w-1/2" v-model="book['title']" placeholder="" label="Book title" disabled />
                    </div>

                    <TextInput v-model="newSettlement.quantity" placeholder="Quantity of items to settle" label="Quantity" required />
                    <TextInput v-model="newSettlement.receipt" placeholder="Settlement receipt (PV****)" label="Receipt No" required />

                </Form>

            </div>

        </Modal>

    </Teleport>

</template>

<script setup>

    import { ref } from "vue";
    import { onClickOutside } from "@vueuse/core";
    import Modal from "../../components/Modal.vue"
    import Form from "../../components/Form/Form.vue"
    import TextInput from "../../components/Form/TextInput.vue"
    import DatePicker from "../../components/Form/DatePicker.vue"
    import { formatDate } from "../../utils"
    import store from "../../store"

    const props = defineProps({
        book: {
            type: Object,
            required: true
        },
        show: {
            type: Boolean,
            default: false
        }
    })

    const emits = defineEmits(['close'])

    const settlementModal = ref(null)
    onClickOutside(settlementModal, () => {
        emits('close')
    })

    const newSettlement = ref({
        transaction_on: {
            date: '',
            month: '',
            year: ''
        },
        receipt: null,
        quantity: null,
        book_id: props.book.id
    })

    function submitNewSettlement() {

        axios.post('/api/consignments', {
            receipt: newSettlement.value.receipt,
            quantity: newSettlement.value.quantity,
            book_id: newSettlement.value.book_id,
            transaction_on: formatDate(newSettlement.value.transaction_on),
        })
            .then(_ => {

                store.dispatch('pushAlert', {
                    type: 'success',
                    message: `Settlement registered successfully`,
                })
                emits('close')

            })
            .catch(error => {

                alert(`Error while registering settlement: ${error.response.data.data}`)

            })

    }

</script>

<style scoped>

</style>
