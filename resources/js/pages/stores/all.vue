<template>

    <div class="grid sm:grid-cols-1 sm:w-full sm:place-items-center md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 grid-cols-6 gap-4">

        <template v-for="store in stores">
            <StoreItemCard :store="store" />
        </template>

        <button @click="toggleModal" class="hover:bg-white hover:backdrop-blur transition-all duration-150 h-64 rounded-xl border-dashed border-2 border-subtitle flex flex-col gap-4 justify-center items-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="fill-subtitle w-12 h-12">
                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 9a.75.75 0 00-1.5 0v2.25H9a.75.75 0 000 1.5h2.25V15a.75.75 0 001.5 0v-2.25H15a.75.75 0 000-1.5h-2.25V9z" clip-rule="evenodd" />
            </svg>
            <span class="flex flex-col items-center justify-center gap-1">
                <span class="font-medium text-subtitle">Add New</span>
            </span>

        </button>

    </div>

    <QuickTransferToShop />

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
    import router from "../../router";
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
            .then(response => {

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
