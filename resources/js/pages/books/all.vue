<template>
  <div
    class="grid grid-cols-4 gap-2 sm:flex sm:min-h-[112px] -mx-[8px] px-[8px] sm:overflow-auto sm:snap-mandatory sm:snap-x"
  >
    <InfoCard class="col-span-1 sm:min-w-full sm:snap-center" label="Total Books">
      <span v-if="statistics" class="">{{ formatNumber(statistics.count) ?? 0 }}</span>
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
    <InfoCard class="group col-span-1 sm:min-w-full sm:snap-center" label="Books by Type">
      <div class="w-full flex items-center justify-between">
        <div class="flex items-center gap-2">
          <template v-if="countByType">
            <span>{{ formatNumber(countByType.count) ?? 0 }}</span>
            <span
              :class="[
                countByType.type === 'consignment'
                  ? 'bg-lime-100 text-lime-700'
                  : 'bg-stone-200 text-stone-700'
              ]"
              class="mt-0.5 text-sm px-2 rounded-lg font-medium"
              >{{ countByType.type }}</span
            >
          </template>
          <template v-else>
            <svg
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
          </template>
        </div>
        <svg
          @click="toggleCountType"
          :class="[countType === 'consignment' ? 'rotate-45' : '-rotate-45']"
          class="group-hover:opacity-100 opacity-0 transition duration-500 cursor-pointer"
          width="20"
          height="14"
          viewBox="0 0 20 14"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M8 5.99999H5.101L5.102 5.99099C5.23257 5.35162 5.48813 4.74434 5.854 4.20399C6.39845 3.4018 7.16215 2.77315 8.054 2.39299C8.356 2.26499 8.671 2.16699 8.992 2.10199C9.65789 1.96698 10.3441 1.96698 11.01 2.10199C11.967 2.29808 12.8451 2.7714 13.535 3.46299L14.951 2.05099C14.3128 1.41262 13.5578 0.903028 12.727 0.549986C12.3033 0.370615 11.8628 0.233939 11.412 0.141986C10.4818 -0.0470031 9.52316 -0.0470031 8.593 0.141986C8.14185 0.23432 7.70101 0.371329 7.277 0.550986C6.02753 1.08109 4.95793 1.96108 4.197 3.08499C3.68489 3.84284 3.32676 4.69398 3.143 5.58999C3.115 5.72499 3.1 5.86299 3.08 5.99999H0L4 9.99999L8 5.99999ZM12 7.99999H14.899L14.898 8.00799C14.6367 9.28975 13.8812 10.4171 12.795 11.146C12.2548 11.5122 11.6475 11.7677 11.008 11.898C10.3424 12.033 9.65656 12.033 8.991 11.898C8.35163 11.7674 7.74435 11.5119 7.204 11.146C6.93862 10.9665 6.69085 10.7622 6.464 10.536L5.05 11.95C5.68851 12.5882 6.44392 13.0974 7.275 13.45C7.699 13.63 8.142 13.767 8.59 13.858C9.51982 14.0471 10.4782 14.0471 11.408 13.858C13.2005 13.4859 14.7773 12.4294 15.803 10.913C16.3146 10.1557 16.6724 9.30525 16.856 8.40999C16.883 8.27499 16.899 8.13699 16.919 7.99999H20L16 3.99999L12 7.99999Z"
            fill="#6A727F"
          />
        </svg>
      </div>
    </InfoCard>
    <InfoCard class="col-span-1 sm:min-w-full sm:snap-center" label="Categories">
      <span v-if="statistics">{{ formatNumber(statistics['categories_count']) ?? '0' }}</span>
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
    <InfoCard
      class="col-span-1 sm:min-w-full sm:snap-center"
      label="Items Registered in Last 30 days"
    >
      <span v-if="statistics">{{ formatNumber(statistics['count_last_month']) ?? '0' }}</span>
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

  <TableAJAX
    url="/api/books/paginate"
    title="Books"
    :columns="['id', 'code', 'title', 'category', 'type', 'balance', 'created_at']"
    :center="['code', 'balance']"
    :right="[]"
    :sortable="['category', 'code', 'balance', 'title', 'purchase_type', 'added_on']"
    :searchable="['title', 'added_on', 'code']"
    :hideable="false"
    :hide="['id']"
    :hide-labels="['id']"
    actions
    @table-button-click="handleTableButton"
    @edit="handleEdit"
    @delete="handleDelete"
    @new-item="goto('CreateBook')"
    :mapper="mapBooks"
  >
    <template #description>
      <p>Complete list of books in the inventory</p>
    </template>

    <template #rows="data">
      <Cell
        name="code"
        :hide="data.hide"
        class="w-[2%] text-xs font-semibold text-subtitle text-center"
        >
          <span v-html="data['record']['code'].toLowerCase()"></span>
      </Cell>
      <LinkCell
        class="w-[90%]"
        name="title"
        :main="true"
        :hide="data.hide"
        :value="data['record']['title']"
        :to="'books/' + data['record']['id']"
      >
        <RouterLink :to="'books/' + data['record']['id']">
            <span v-html="data['record']['title']"></span>
        </RouterLink>
      </LinkCell>
      <Cell class="w-[2%]" name="category" :hide="data.hide"
        >
          <span v-html="data['record']['category']"></span>
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
        name="balance"
        :hide="data.hide"
        class="w-[2%] text-xs font-semibold text-subtitle text-center"
        >
          <span v-html="data['record']['balance']"></span>
      </Cell>
      <DateCell
        class="w-[2%]"
        name="added_on"
        :hide="data.hide"
        :value="data['record']['added_on']"
      />
    </template>
  </TableAJAX>

  <ConfirmationModal
    :open="deleteModal"
    title="Delete book"
    button-text="Delete book"
    :loading="deleting"
    @cancel="cancelDelete"
    @confirm="attemptDelete(recordToDelete.id)"
  >
    Are you sure you want to delete
    <span class="text-black">{{ recordToDelete.title }}</span>
    ? This action cannot be undone. Any transaction history of the book will also be deleted.
  </ConfirmationModal>
</template>

<script setup>
import { onMounted, computed, ref, onBeforeUnmount } from 'vue'
import InfoCard from '../../components/InfoCard.vue'
import Table from '../../components/Table/Table.vue'
import TableAJAX from '../../components/Table/TableAjax.vue'
import Cell from '../../components/Table/Cell.vue'
import LinkCell from '../../components/Table/LinkCell.vue'
import EnumCell from '../../components/Table/EnumCell.vue'
import DateCell from '../../components/Table/DateCell.vue'
import store from '../../store'
import router from '../../router'
import ConfirmationModal from '../../components/ConfirmationModal.vue'
import { formatNumber } from '../../utils'

const deleteModal = ref(false)
const recordToDelete = ref(null)
const deleting = ref(false)

function cancelDelete() {
  deleteModal.value = false
  recordToDelete.value = null
}

function attemptDelete(id) {
  deleting.value = true
  console.log(id)
  store.dispatch('deleteBook', id).then(() => {
    deleteModal.value = false
    deleting.value = false
    recordToDelete.value = null
  })
}

const loading = ref(true)
const books = computed(() => store.state.book.books)
const statistics = computed(() => store.state.book.statistics)

const countType = ref('consignment')
const countByType = ref(null)
function toggleCountType() {
  if (countType.value === 'consignment') {
    countByType.value.type = 'cash'
    countByType.value.count = statistics.value['count_by_type']['cash']
    countType.value = 'cash'
  } else {
    countByType.value.type = 'consignment'
    countByType.value.count = statistics.value['count_by_type']['consignment']
    countType.value = 'consignment'
  }
}

function handleTableButton() {}

function handleEdit(object) {
  router.push({ name: 'UpdateBook', params: { id: object.id } })
}

function handleDelete(object) {
  console.log(object)
  recordToDelete.value = object
  deleteModal.value = true
}

async function fetchBooks() {
  if (!!!store.state.book.books) {
    await store
      .dispatch('getBooks')
      .then(() => {
        console.log(store.state.book.books[0])
      })
      .finally(() => {
        loading.value = false
      })
  } else {
    loading.value = false
  }
}

function goto(route) {
  router.push({ name: route })
}

const mapBooks = (book) => {
    console.log(book)
  return {
    id: book['_formatted'].id,
    code: book['_formatted'].code,
    title: book['_formatted'].title,
    category: book['_formatted'].category,
    purchase_type: book['_formatted'].type,
    balance: book['_formatted'].balance,
    added_on: new Date(book['_formatted']['created_at'] * 1000)
  }
}

onMounted(() => {
  fetchBooks()
  store.dispatch('getBooksStats').then(() => {
    countByType.value = {
      type: 'consignment',
      count: statistics.value['count_by_type']['consignment']
    }
  })
})

onBeforeUnmount(() => {
  // TODO: Cancel all pending requests
  store.dispatch('cancelBookRequest')
})
</script>

<style scoped></style>
