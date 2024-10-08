<template>

    <div class="w-full grid place-items-center" v-if="loadingStores">

        <svg class="animate-rotate" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.4706 5.94533C18.1987 5.55015 18.4769 4.63079 17.9774 3.96992C17.0157 2.69771 15.7589 1.66786 14.3065 0.974827C12.4006 0.0653761 10.2557 -0.217352 8.1792 0.167163C6.10271 0.551679 4.20125 1.5837 2.74734 3.11531C1.29344 4.64692 0.361732 6.59949 0.0857576 8.69317C-0.190217 10.7869 0.203708 12.9142 1.21108 14.7702C2.21844 16.6262 3.78753 18.1157 5.69346 19.0252C7.59939 19.9346 9.74431 20.2174 11.8208 19.8328C13.4032 19.5398 14.8839 18.8708 16.1424 17.8912C16.7961 17.3824 16.7656 16.4223 16.1648 15.852V15.852C15.5639 15.2816 14.621 15.3229 13.9359 15.7887C13.1386 16.3308 12.2332 16.7055 11.2746 16.883C9.82102 17.1521 8.31958 16.9542 6.98542 16.3176C5.65127 15.681 4.55291 14.6384 3.84775 13.3391C3.1426 12.0399 2.86685 10.5508 3.06003 9.08522C3.25321 7.61964 3.90541 6.25284 4.92314 5.18072C5.94087 4.10859 7.27189 3.38618 8.72544 3.11701C10.179 2.84785 11.6804 3.04576 13.0146 3.68238C13.8945 4.10224 14.6718 4.69872 15.3015 5.42894C15.8424 6.05634 16.7425 6.34051 17.4706 5.94533V5.94533Z" fill="#FF8100"/>
        </svg>

    </div>

    <template v-else>

        <div class="grid sm:grid-cols-1 sm:w-full sm:place-items-center md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 grid-cols-6 gap-4">

            <template v-for="store in stores">
                <StoreItemCard :store="store" />
            </template>

            <button @click="toggleModal" class="w-full sm:w-full hover:bg-white hover:backdrop-blur transition-all duration-150 sm:p-4 sm:gap-2 sm:h-fit h-64 rounded-xl border-dashed border-2 border-subtitle flex flex-col gap-4 justify-center items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="fill-subtitle w-12 h-12 sm:w-8 sm:h-8">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 9a.75.75 0 00-1.5 0v2.25H9a.75.75 0 000 1.5h2.25V15a.75.75 0 001.5 0v-2.25H15a.75.75 0 000-1.5h-2.25V9z" clip-rule="evenodd" />
                </svg>
                <span class="flex flex-col items-center justify-center gap-1">
                <span class="font-medium text-subtitle">Add New</span>
            </span>

            </button>

        </div>

        <div class="flex sm:flex-col gap-4">
            <QuickTransferToShop />
            <div class="lg:hidden w-1/2 sm:hidden"></div>
        </div>

        <Teleport v-if="modalState" to="#top-view">

            <Modal>

                <div id="formModal" ref="formModal" class="form-modal w-[45%] sm:w-full sm:rounded-t-lg sm:overflow-auto sm:max-h-[75%] sm:animate-slide-up-modal animate-scale-up">

                    <Form
                        modal
                        @cancel="toggleModal"
                        :submit="submitNewStore"
                        class="h-full w-full rounded-b-none"
                        title="Register new Store (Warehouse)"
                        subtitle="Fill in the information of the new store or warehouse to be registered. Address information is optional but recommended to avoid duplicate entries."
                        title-layout="contained"
                    >

                        <div class="flex w-full gap-8">
                            <TextInput v-model="newStore.name" class="grow" required placeholder="New Store Name" label="Store (Warehouse) name" />
                            <SwitchInput v-model="newStore.primary" label-location="top" label="Primary" />
                        </div>

                        <Collapsable open collapse-direction="down" :label="['Address information', 'Address information']">

                            <div class="w-full flex sm:flex-col gap-8">
                                <TextInput v-model="newStore.address.sub_city" class="sm:w-full w-1/3" required placeholder="Sub-City" label="Sub-City" />
                                <TextInput v-model="newStore.address.kebele" class="sm:w-full w-1/3" required placeholder="Kebele" label="Kebele" />
                                <TextInput v-model="newStore.address.house_number" class="sm:w-full w-1/3" placeholder="House No." label="House No." />
                            </div>

                            <div class="w-full sm:flex-col flex gap-8">
                                <TextInput v-model="newStore.address.country" class="sm:w-full w-1/2" required placeholder="Country" label="Country" />
                                <TextInput v-model="newStore.address.city" class="sm:w-full w-1/2" required placeholder="City" label="City" />
                            </div>

                        </Collapsable>

                    </Form>

                </div>



            </Modal>

        </Teleport>

    </template>

</template>

<script setup>

    import { ref, onMounted } from "vue"
    import StoreItemCard from "../../views/StoreItemCard.vue"
    import Modal from "../../components/Modal.vue"
    import Form from "../../components/Form/Form.vue"
    import axios from "axios";
    import { onClickOutside } from "@vueuse/core";
    import TextInput from "../../components/Form/TextInput.vue";
    import SwitchInput from "../../components/Form/SwitchInput.vue"
    import Collapsable from "../../components/Collapsable.vue"
    import QuickTransferToShop from "../../views/QuickTransferToShop.vue"
    import { getCurrentInstance } from 'vue'

    const stores = ref([])
    const loadingStores = ref(true)

    const modalState = ref(false)
    function toggleModal() {
        modalState.value = !modalState.value
    }

    const formModal = ref(null)
    onClickOutside(formModal, () => {
        toggleModal()
    })

    const newStore = ref({
        name: '',
        primary: false,
        address: {
            country: 'Ethiopia',
            city: 'Addis Ababa',
            sub_city: '',
            kebele: '',
            house_number: ''
        }
    })
    function submitNewStore() {

        axios.post('/api/stores', newStore.value)
            .then(() => {

                const instance = getCurrentInstance();
                instance?.proxy?.$forceUpdate();

            })
            .catch(error => {

                alert(`Error while registering store: ${error}`)

            })

    }

    function getStores() {

        axios.get('/api/stores')
            .then(response => {

                stores.value = response.data.data
                console.log(stores.value)

            })
            .catch(error => {

                alert(`Error fetching stores: ${error}`)

            })
            .finally(() => loadingStores.value = false)

    }

    onMounted(() => {

        getStores()

    })

</script>

<style scoped></style>
