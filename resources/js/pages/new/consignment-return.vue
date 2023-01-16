<template>

    <Teleport v-if="show" to="#top-view">

        <Modal>

            <div ref="returnModal" class="form-modal w-[45%] sm:w-full sm:rounded-t-lg sm:overflow-auto sm:max-h-[75%] sm:animate-slide-up-modal animate-scale-up">

                <Form modal @cancel="$emit('close')" :submit="submitNewReturn" class="h-full w-full rounded-b-none" title="Return Items" title-layout="contained">

                    <div class="w-full flex sm:flex-col gap-6">
                        <DatePicker class="sm:w-full w-1/2" label="Return date" required v-model="newReturn.transaction_on" />
                        <TextInput class="sm:w-full w-1/2" v-model="book['title']" placeholder="" label="Book title" disabled />
                    </div>

                    <TextInput v-model="newReturn.receipt" placeholder="Return receipt" label="Receipt No" required />
                    <TextInput disabled v-model="total_return" placeholder="Quantity of items to return" label="Quantity" required />

                    <template v-for="quantity in newReturn.quantity">
                        <div class="w-full flex sm:flex-col gap-6">
                            <TextPlaceholder class="sm:w-full w-1/2" :value="quantity['store_name']" label="Store name" disabled />
                            <TextInput class="sm:w-full w-1/2" v-model="quantity.amount" placeholder="Quantity of items to return" label="Quantity" />
                        </div>
                    </template>

                </Form>

            </div>

        </Modal>

    </Teleport>

</template>

<script setup>

    import { ref, computed, onMounted } from "vue";
    import { onClickOutside } from "@vueuse/core";
    import Modal from "../../components/Modal.vue"
    import Form from "../../components/Form/Form.vue"
    import TextInput from "../../components/Form/TextInput.vue"
    import TextPlaceholder from "../../components/Form/TextPlaceholder.vue"
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

    const returnModal = ref(null)
    onClickOutside(returnModal, () => {
        emits('close')
    })

    const total_return = computed(() => newReturn.value.quantity.reduce((sum, a) => sum + parseInt(a?.amount), 0))

    const newReturn = ref({
        transaction_on: {
            date: '',
            month: '',
            year: ''
        },
        receipt: null,
        quantity: [],
        book_id: props.book.id
    })

    const fetchingStoreBalance = ref(true)
    function getStoreBalance() {

        axios.get('/api/books/stores', {
            params: {
                id: props.book.id
            }
        }).then(response => {

             newReturn.value.quantity = response.data.data.map(store => {

                 return {
                     store_id: store.store_id,
                     store_name: store.store.name,
                     amount: 0
                 }

             })

        }).catch(error => {

            console.log(error.response.data)
            alert(`Error while fetching stores for book: ${error}`)

        }).finally(() => fetchingStoreBalance.value = false)

    }

    function submitNewReturn() {

        axios.post('/api/consignments/return', {
            receipt: newReturn.value.receipt,
            quantity: newReturn.value.quantity,
            book_id: newReturn.value.book_id,
            transaction_on: formatDate(newReturn.value.transaction_on),
        })
            .then(_ => {

                store.dispatch('pushAlert', {
                    type: 'success',
                    message: `Item return registered successfully`,
                })
                emits('close')

            })
            .catch(error => {

                console.log(error.response)

                alert(`Error while registering item return: ${error.response.data.data}`)

            })

    }

    onMounted(() => {

        getStoreBalance()

    })

</script>

<style scoped>

</style>
