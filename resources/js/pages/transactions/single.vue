<template>

    <Transactions />

    <Teleport to="#top-view">

        <Modal>

            <div v-if="record" ref="detailModal" class="bg-white w-[42rem] min-h-fit rounded-xl sm:rounded-b-none p-8 flex flex-col gap-4 sm:animate-slide-up-modal animate-scale-up">

                <div class="flex justify-between">

                    <div class="flex flex-col">
                        <h3 class="text-lg font-medium leading-tight">{{ record.transaction.book.title }}</h3>
                        <div class="flex flex-col">
                            <ul class="">
                                <li class="font-medium text-xs">
                                    <span class="font-normal text-subtitle">Invoice No.</span>
                                    {{ record.transaction.invoice }}
                                </li>
                                <li class="font-medium text-xs">
                                    <span class="font-normal text-subtitle text-xs">Dated</span>
                                    {{ new Date(record.transaction.transaction_on).toLocaleString() }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex flex-col items-center gap-1 leading-none w-fit">
                        <p class="text-xl font-medium">{{ record.transaction.quantity }}</p>
                        <label v-if="record.transaction.type === 'purchase'" class="text-2xs font-semibold text-center whitespace-nowrap inline py-0.5 px-1.5 rounded-md bg-green-200 text-green-800">purchase</label>
                        <label v-else class="text-2xs font-semibold text-center whitespace-nowrap inline py-0.5 px-1.5 rounded-md bg-red-200 text-red-800">sale</label>
                    </div>

                </div>

                <div class="flex flex-col gap-2">
                    <h4 class="text-xs text-subtitle">Stores</h4>
                    <div v-for="store in record['stores']" class="flex flex-col gap-3">

                        <div class="min-h-fit flex justify-between items-center px-4 py-3 border border-border-dark rounded-lg w-full">

                            <div class="flex flex-col">
                                <h3 class="font-medium">{{ store.store.name }}</h3>
                                <label class="text-subtitle text-xs">{{ store.store.primary ? 'Primary' : 'Warehouse' }}</label>
                            </div>

                            <h2 class="text-xl font-medium">{{ store.quantity }}</h2>

                        </div>

                    </div>
                </div>

            </div>

            <LoadingIndicator v-else />

        </Modal>

    </Teleport>

</template>

<script setup>

    import { ref, onMounted } from "vue"
    import { onClickOutside } from "@vueuse/core";
    import Transactions from "./all.vue"
    import Modal from "../../components/Modal.vue"
    import router from "../../router"
    import { useRoute } from "vue-router";
    import LoadingIndicator from "../../components/LoadingIndicator.vue";

    const record = ref(null)
    function getTransaction() {

        axios.get('/api/transactions', {
            params: {
                id: useRoute().params.id
            }
        })
            .then(response => {

                record.value = response.data.data

            })
            .catch(error => {

                alert(`Error while fetching transaction ${error}`)

            })

    }

    onMounted(() => {

        getTransaction()

    })

    const detailModal = ref(null)
    onClickOutside(detailModal, () => {

        router.push({ name: 'Transactions' })

    })

</script>

<style scoped>

</style>
