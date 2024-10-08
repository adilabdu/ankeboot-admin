<template>
  <div class="grid grid-cols-4 gap-2 sm:flex sm:overflow-auto sm:snap-mandatory sm:snap-x">
    <InfoCard class="group col-span-1 sm:min-w-full sm:snap-center" label="Store information">
      <div v-if="store" class="text-sm font-normal flex flex-col overflow-hidden">
        <div class="flex items-center gap-4">
          <p class="text-xl font-medium">{{ store.name }}</p>
        </div>
        <div class="flex gap-4">
          <span v-if="store.address" class="text-subtitle text-xs max-w-[18rem] truncate">
            {{ store.address.city }}, {{ store.address.sub_city }}, Woreda
            {{ store.address.kebele }}, House No.
            {{ store.address.house_number }}
          </span>
          <span v-else class="flex items-center gap-1 text-red-500 text-xs">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="2"
              stroke="currentColor"
              class="w-4 h-4"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"
              />
            </svg>
            Update store address
          </span>
        </div>
      </div>
      <svg
        v-else
        class="animate-rotate my-2"
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
    </InfoCard>
    <InfoCard class="col-span-1 sm:min-w-full sm:snap-center" label="Copies of Items in Store">
      <span v-if="statistics" class="">{{ statistics['total_copies'] }}</span>
      <svg
        v-else
        class="animate-rotate my-2"
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
    </InfoCard>
    <InfoCard class="group col-span-1 sm:min-w-full sm:snap-center" label="Book titles in store">
      <span v-if="statistics" class="">{{ statistics['books_count'] }}</span>
      <svg
        v-else
        class="animate-rotate my-2"
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
    </InfoCard>
    <InfoCard class="group col-span-1 sm:min-w-full sm:snap-center" label="Latest Transaction">
      <div class="text-sm font-normal flex flex-col overflow-hidden" v-if="statistics">
        <template v-if="statistics['latest_transaction']">
          <div class="flex items-center gap-4">
            <p class="text-xl font-medium whitespace-nowrap max-w-[18rem] truncate">
              {{ statistics['latest_transaction']['book_title'] }}
            </p>
            <svg
              v-if="statistics['latest_transaction']['type'] === 'sale'"
              class="stroke-2 stroke-red-600 w-5 h-5"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            <svg
              v-else
              class="w-5 h-5 min-w-[1.25rem] min-h-[1.25rem] stroke-2 stroke-green-600"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
          </div>
          <div class="flex gap-4">
            <p class="text-xs">
              {{ statistics['latest_transaction']['quantity'] }}
              <span v-if="statistics['latest_transaction']['transaction_quantity'] > 1"
                >copies</span
              >
              <span v-else>copy</span>
            </p>
            <p class="text-subtitle text-xs">
              {{ new Date(statistics['latest_transaction']['on']).toLocaleDateString() }}
            </p>
          </div>
        </template>
        <span v-else class="text-3xl font-medium text-subtitle scale-75 origin-left">N/A</span>
      </div>
      <svg
        v-else
        class="animate-rotate my-2"
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
    </InfoCard>
  </div>

  <Table
    :loading="booksLoading"
    title="Store records"
    :data="books"
    :center="['code', 'balance']"
    :right="[]"
    :sortable="['category', 'code', 'balance', 'title', 'purchase_type', 'added_on']"
    :searchable="['title', 'added_on', 'code']"
    :hideable="false"
    :hide="['id']"
    :hide-labels="['id']"
    :actions="false"
    @table-button-click="handleTableButton"
  >
    <template #description>
      <p>Complete list of books in store</p>
    </template>

    <template #rows="data">
      <Cell
        name="code"
        :hide="data.hide"
        class="w-[2%] text-xs font-semibold text-subtitle text-center"
        >{{ data['record']['code'].toLowerCase() }}
      </Cell>
      <LinkCell
        class="w-[90%]"
        name="title"
        :main="true"
        :hide="data.hide"
        :value="data['record']['title']"
        :to="'/books/' + data['record']['id']"
      >
        <RouterLink :to="'/books/' + data['record']['id']">
          {{ data['record']['title'] }}
        </RouterLink>
      </LinkCell>
      <Cell class="w-[2%]" name="category" :hide="data.hide"
        >{{ data['record']['category'] }}
      </Cell>
      <EnumCell
        class="w-[2%]"
        name="purchase_type"
        :hide="data.hide"
        :colors="['lime', 'stone']"
        :options="['consignment', 'cash']"
        :value="data['record']['purchase_type']"
      />
      <Cell
        name="in_store"
        :hide="data.hide"
        class="w-[2%] text-xs font-semibold text-subtitle text-center"
        >{{ data['record']['in_store'] }}
      </Cell>
      <DateCell
        class="w-[2%]"
        name="added_on"
        :hide="data.hide"
        :value="data['record']['added_on']"
      />
    </template>
  </Table>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute } from 'vue-router'
import InfoCard from '../../components/InfoCard.vue'
import Table from '../../components/Table/Table.vue'
import Cell from '../../components/Table/Cell.vue'
import LinkCell from '../../components/Table/LinkCell.vue'
import EnumCell from '../../components/Table/EnumCell.vue'
import DateCell from '../../components/Table/DateCell.vue'

function handleTableButton() {}

const store = ref(null)
function loadStore() {
  axios
    .get('/api/stores/', {
      params: {
        id: useRoute().params.id
      }
    })
    .then((response) => {
      store.value = response.data.data
      console.log({ ...store.value })
    })
    .catch((error) => {
      alert(`Error while fetching store information: `, error)
    })
}

const books = ref(null)
const booksLoading = ref(true)
function loadBookList() {
  axios
    .get('/api/stores/list', {
      params: {
        store_id: useRoute().params.id
      }
    })
    .then((response) => {
      books.value = response.data.data.map((book) => {
        return {
          id: book.book.id,
          code: book.book.code,
          title: book.book.title,
          category: book.book.category,
          purchase_type: book.book.type,
          in_store: book.balance,
          added_on: new Date(book.book['created_at'] * 1000)
        }
      })
    })
    .catch((error) => {
      alert(`Error while fetching book list in store: ${error}`)
    })
    .finally(() => (booksLoading.value = false))
}

const statistics = ref(null)
function getStoreStatistics() {
  axios
    .get('/api/stores/statistics', {
      params: {
        store_id: useRoute().params.id
      }
    })
    .then((response) => {
      statistics.value = response.data.data
    })
    .catch((error) => {
      alert(`Error while fetching store statistics, ${error}`)
    })
}

onMounted(() => {
  loadStore()
  loadBookList()
  getStoreStatistics()
})
</script>

<style scoped></style>
