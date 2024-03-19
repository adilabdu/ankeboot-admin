<template>
  <Form title="Register new Transaction" :submit="createNewTransaction">
    <template #subtitle>
      Search through the book records and select the item to register a new transaction for. Do not
      forget to specify the transaction type (purchase / sale).
    </template>

    <ItemSearchInput
      v-if="!book_id"
      required
      @click="toggleNewBookModal"
      v-model:form-data="transactionData.book_id"
      v-model:selected="book_title"
      selectable="id"
      placeholder="Search for book item with (code, title)"
      label="Book for Transaction"
      :search-logic="searchBooks"
    />
    <TextPlaceholder v-else required :value="book_title" label="Book for Transaction" />

    <div class="flex sm:flex-col gap-8 w-full">
      <TextInput
        required
        v-model="transactionData.invoice"
        class="w-full"
        placeholder="Transaction receipt invoice number"
        label="Transaction Invoice"
      />
      <DatePicker required v-model="transactionData.date" class="w-full" label="Transaction date" />
    </div>

    <div class="flex items-center gap-8 w-full">
      <TextInput
        :disabled="transactionType.type"
        v-model="transactionData.quantity"
        required
        class="w-full"
        placeholder="Quantity of items in transaction"
        label="Item Quantity"
      />
      <SwitchInput
        class="px-3 sm:order-first"
        label-location="top"
        v-model="transactionType.type"
        label="Purchase"
      />
    </div>

    <!-- Stores -->
    <div v-if="transactionType.type" class="gap-6 flex flex-col">
      <div class="flex items-center justify-center w-full gap-2 px-2">
        <p class="text-xs text-subtitle font-medium whitespace-nowrap">Warehouse(s)</p>
        <div class="border-b border-border-light w-full py-1 mb-2 m-auto"></div>
      </div>

      <template v-for="(store, index) in storesData" :key="index">
        <div class="flex sm:flex-col w-full gap-8">
          <div class="flex sm:w-full w-1/2">
            <button
              type="button"
              @click="removeStore(index)"
              v-if="index !== 0"
              class="mx-2 self-end rounded-md h-10 w-10 flex items-center justify-center"
            >
              <svg
                width="15"
                height="17"
                viewBox="0 0 15 17"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M1.6 14.7333C1.6 15.1577 1.76857 15.5646 2.06863 15.8647C2.36869 16.1647 2.77565 16.3333 3.2 16.3333H11.2C11.6243 16.3333 12.0313 16.1647 12.3314 15.8647C12.6314 15.5646 12.8 15.1577 12.8 14.7333V5.13331H14.4V3.53331H11.2V1.93331C11.2 1.50897 11.0314 1.102 10.7314 0.801942C10.4313 0.501884 10.0243 0.333313 9.6 0.333313H4.8C4.37565 0.333313 3.96869 0.501884 3.66863 0.801942C3.36857 1.102 3.2 1.50897 3.2 1.93331V3.53331H0V5.13331H1.6V14.7333ZM4.8 1.93331H9.6V3.53331H4.8V1.93331ZM4 5.13331H11.2V14.7333H3.2V5.13331H4Z"
                  fill="#FF8100"
                />
                <path
                  d="M4.80078 6.73334H6.40078V13.1333H4.80078V6.73334ZM8.00078 6.73334H9.60078V13.1333H8.00078V6.73334Z"
                  fill="#FF8100"
                />
              </svg>
            </button>
            <Dropdown
              v-if="!loadingStore"
              class="h-16 grow"
              :hide-label-on-mobile="false"
              required
              v-model="store.store_id"
              :list="stores"
              :resource_list="{ display: 'name', value: 'id' }"
              placeholder="Choose Warehouse"
              label-direction="top"
              label="Warehouse"
            />
          </div>
          <TextInput
            v-model="store.amount"
            required
            class="w-1/2 sm:w-full"
            placeholder="Quantity of items in store"
            label="Item Quantity"
          />
        </div>
      </template>

      <button
        type="button"
        @click="addStore"
        class="-mt-2 px-4 py-2 gap-2 rounded-full bg-brand-secondary w-fit m-auto flex items-center justify-center"
      >
        <svg
          width="19"
          height="19"
          viewBox="0 0 19 19"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M10.7301 4.95667H8.93008V8.55667H5.33008V10.3567H8.93008V13.9567H10.7301V10.3567H14.3301V8.55667H10.7301V4.95667Z"
            fill="#FF8100"
          />
          <path
            d="M9.82813 0.455872C4.86553 0.455872 0.828125 4.49327 0.828125 9.45587C0.828125 14.4185 4.86553 18.4559 9.82813 18.4559C14.7907 18.4559 18.8281 14.4185 18.8281 9.45587C18.8281 4.49327 14.7907 0.455872 9.82813 0.455872ZM9.82813 16.6559C5.85823 16.6559 2.62813 13.4258 2.62813 9.45587C2.62813 5.48597 5.85823 2.25587 9.82813 2.25587C13.798 2.25587 17.0281 5.48597 17.0281 9.45587C17.0281 13.4258 13.798 16.6559 9.82813 16.6559Z"
            fill="#FF8100"
          />
        </svg>
        <span class="focus:outline-none text-brand-primary text-xs font-medium">Add</span>
      </button>
    </div>
  </Form>

  <form
    @submit.prevent="insertMultipleTransactions"
    title="Form"
    submit=""
    class="w-full sm:flex sm:flex-col grid grid-cols-12 gap-2"
  >
    <div class="col-span-4 py-2 flex flex-col gap-1">
      <h1 class="text-base">Insert multiple Book records from CSV</h1>
      <slot name="subtitle">
        <h2 class="text-subtitle">
          Upload an excel or comma separated value (CSV) file to register multiple book records at
          once. Make sure to include all the necessary columns and follow the template to insert the
          data successfully.
        </h2>
      </slot>
    </div>
    <div class="flex flex-col col-span-8 items-end gap-6">
      <FileInput class="w-full" v-model="uploadFile" />
      <div class="w-full flex justify-between items-end px-6 sm:px-0">
        <DatePicker
          drop-direction="up"
          align="left"
          label="Transaction Date"
          class="min-w-[16rem]"
          v-model="bulkTransactionDate"
        />

        <div class="w-full flex justify-end items-center">
          <button type="submit" class="px-4 bg-brand-primary w-fit text-white rounded-md py-2">
            Upload
          </button>
        </div>
      </div>
    </div>
  </form>

  <Teleport v-if="!!loading" to="#top-view">
    <Modal>
      <div class="w-full h-full flex items-end">
        <!-- Loading indicator -->
        <div class="flex w-full items-center justify-center min-h-[6rem] gap-2">
          <div class="flex gap-2 bg-white/75 rounded-full px-4 py-2">
            <svg
              class="animate-rotate"
              width="19"
              height="20"
              viewBox="0 0 19 20"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M17.4706 5.94533C18.1987 5.55015 18.4769 4.63079 17.9774 3.96992C17.0157 2.69771 15.7589 1.66786 14.3065 0.974827C12.4006 0.0653761 10.2557 -0.217352 8.1792 0.167163C6.10271 0.551679 4.20125 1.5837 2.74734 3.11531C1.29344 4.64692 0.361732 6.59949 0.0857576 8.69317C-0.190217 10.7869 0.203708 12.9142 1.21108 14.7702C2.21844 16.6262 3.78753 18.1157 5.69346 19.0252C7.59939 19.9346 9.74431 20.2174 11.8208 19.8328C13.4032 19.5398 14.8839 18.8708 16.1424 17.8912C16.7961 17.3824 16.7656 16.4223 16.1648 15.852V15.852C15.5639 15.2816 14.621 15.3229 13.9359 15.7887C13.1386 16.3308 12.2332 16.7055 11.2746 16.883C9.82102 17.1521 8.31958 16.9542 6.98542 16.3176C5.65127 15.681 4.55291 14.6384 3.84775 13.3391C3.1426 12.0399 2.86685 10.5508 3.06003 9.08522C3.25321 7.61964 3.90541 6.25284 4.92314 5.18072C5.94087 4.10859 7.27189 3.38618 8.72544 3.11701C10.179 2.84785 11.6804 3.04576 13.0146 3.68238C13.8945 4.10224 14.6718 4.69872 15.3015 5.42894C15.8424 6.05634 16.7425 6.34051 17.4706 5.94533V5.94533Z"
                fill="#FF8100"
              />
            </svg>

            <span class="text-subtitle font-medium">
              Populating transactions record.

              <span class="font-normal">This might take a few seconds</span>
            </span>
          </div>
        </div>
      </div>
    </Modal>
  </Teleport>

  <CreateBookModal :show="openNewBookModal" @close="toggleNewBookModal" @data="getBookData" />
</template>

<script setup>
import { onMounted, onBeforeUnmount, ref, computed, watch } from 'vue'
import Form from '../../components/Form/Form.vue'
import TextInput from '../../components/Form/TextInput.vue'
import TextPlaceholder from '../../components/Form/TextPlaceholder.vue'
import SwitchInput from '../../components/Form/SwitchInput.vue'
import store from '../../store'
import router from '../../router'
import DatePicker from '../../components/Form/DatePicker.vue'
import FileInput from '../../components/Form/FileInput.vue'
import Modal from '../../components/Modal.vue'
import ItemSearchInput from '../../components/Form/ItemSearchInput.vue'
import Dropdown from '../../components/Dropdown.vue'
import CreateBookModal from '../../views/CreateBookModal.vue'
import { useRoute } from 'vue-router'

const books = computed(() => store.state.book.books)

const openNewBookModal = ref(false)
function toggleNewBookModal() {
  openNewBookModal.value = !openNewBookModal.value
}

const book_title = ref(null)
function getBookData(payload) {
  book_title.value = payload.title
  transactionData.value.book_id = payload.id
}

const storesData = ref([
  {
    store_id: '',
    amount: 0
  }
])

const transactionType = ref({
  type: true
})
const transactionData = ref({
  book_id: '',
  date: {
    date: '',
    month: '',
    year: ''
  },
  quantity: transactionType.value.type ? storeSum() : '',
  invoice: ''
})

function resetTransactionForm() {
  storesData.value = [
    {
      store_id: '',
      amount: 0
    }
  ]

  transactionData.value = {
    book_id: '',
    date: {
      date: '',
      month: '',
      year: ''
    },
    quantity: transactionType.value.type ? storeSum() : '',
    invoice: ''
  }

  transactionType.value = {
    type: false
  }

  book_title.value = null
}

function addStore() {
  storesData.value.push({
    store_id: '',
    amount: 0
  })
}

function removeStore(index) {
  storesData.value.splice(index, 1)
}

const loading = computed(() => store.state.book.loading)
const uploadFile = ref(null)
const bulkTransactionDate = ref({
  date: '',
  month: '',
  year: ''
})

function insertMultipleTransactions() {
  store
    .dispatch('postMultipleTransactions', {
      file: uploadFile.value,
      transaction_date: bulkTransactionDate.value
    })
    .then((response) => {
      if (response.message === 'ok') {
        // TODO: Handle with a Notification
        store.dispatch('getTransactions').then(() => {
          router.push({ name: 'Transactions' })
          store.dispatch('pushAlert', {
            type: 'success',
            message: response.data
          })
        })
      } else if (response.message === 'error') {
        store.dispatch('pushAlert', {
          type: 'error',
          message: response.data
        })
      } else {
        store.dispatch('pushAlert', {
          type: 'error',
          message: response.message
        })
      }
    })
    .finally(() => {})
}

function createNewTransaction() {
  store
    .dispatch('postTransaction', {
      ...transactionData.value,
      ...transactionType.value,
      store: storesData.value
    })
    .then((response) => {
      if (response.message === 'ok') {
        store.dispatch('pushAlert', {
          type: 'success',
          message: `Transaction successfully added for ${book_title.value}`
        })

        resetTransactionForm()
      } else if (response.message === 'error') {
        store.dispatch('pushAlert', {
          type: 'error',
          message: response.data
        })
      } else {
        store.dispatch('pushAlert', {
          type: 'error',
          message: response
        })
      }
    })
}

const stores = ref([])
const loadingStore = ref(true)
async function getStores() {
  return await axios
    .get('/api/stores')
    .then((response) => {
      stores.value = response.data.data
    })
    .catch((error) => {
      alert('Error fetching Stores list ', error.data.message)
    })
    .finally(() => {
      loadingStore.value = false
    })
}

async function searchBooks(payload) {
  return await axios
    .get('/api/books/search', {
      params: {
        query: payload
      }
    })
    .then((response) => {
      if (response.data.message === 'ok') {
        return response.data.data
      } else {
        alert(response.data.message)
      }
    })
}

const book_id = computed(() => useRoute().params.book_id)
console.log(`hello world ${book_id.value}`)

onMounted(() => {
  getStores()

  if (!!useRoute().params.book_id) {
    axios
      .get('/api/books/', {
        params: {
          id: useRoute().params.book_id
        }
      })
      .then((response) => {
        getBookData({
          id: response.data.data.id,
          title: response.data.data.title
        })
        store.dispatch('setBook', response.data.data)
      })
      .catch((error) => {
        alert(`Error while fetching book ${error}`)
        router.go(-1)
      })
  }
})

onBeforeUnmount(() => {
  store.dispatch('destroyBook')
})

watch(
  [storesData, transactionType],
  () => {
    transactionData.value.quantity = storeSum()
  },
  {
    deep: true
  }
)

function storeSum() {
  return storesData.value.reduce((sum, item) => {
    return sum + parseInt(item.amount)
  }, 0)
}
</script>

<style scoped></style>
