<template>
  <Transactions />

  <Teleport to="#top-view">
    <Modal>
      <div
        v-if="record"
        ref="detailModal"
        class="bg-white w-[42rem] min-h-fit rounded-xl sm:rounded-b-none p-8 flex flex-col gap-4 sm:animate-slide-up-modal animate-scale-up"
      >
        <div class="flex justify-between">
          <div class="flex flex-col">
            <h3 class="text-lg font-medium leading-tight">
              {{ record.transaction.book.title }}
            </h3>
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
            <p class="text-xl font-medium">
              {{ record.transaction.quantity }}
            </p>
            <label
              v-if="record.transaction.type === 'purchase'"
              class="text-2xs font-semibold text-center whitespace-nowrap inline py-0.5 px-1.5 rounded-md bg-green-200 text-green-800"
              >purchase</label
            >
            <label
              v-else
              class="text-2xs font-semibold text-center whitespace-nowrap inline py-0.5 px-1.5 rounded-md bg-red-200 text-red-800"
              >sale</label
            >
          </div>
        </div>

        <div class="flex flex-col gap-2">
          <h4 class="text-xs text-subtitle">Stores</h4>
          <div v-for="store in record['stores']" class="flex flex-col gap-3">
            <div
              class="min-h-fit flex justify-between items-center px-4 py-3 border border-border-dark rounded-lg w-full"
            >
              <div class="flex flex-col">
                <h3 class="font-medium">
                  {{ store.store.name }}
                </h3>
                <label class="text-subtitle text-xs">{{
                  store.store.primary ? 'Primary' : 'Warehouse'
                }}</label>
              </div>

              <h2 class="text-xl font-medium">
                {{ store.quantity }}
              </h2>
            </div>
          </div>
        </div>

        <div class="flex flex-col gap-2">
          <div class="flex gap-3">
            <RouterLink
              :to="'/books/' + record.transaction.book.id"
              class="group hover:bg-border-dark/50 bg-border-dark/25 transition-colors duration-150 min-h-[6rem] flex flex-col justify-around px-4 py-3 rounded-lg w-full"
            >
              <BookOpenIcon class="w-6 h-6" />

              <label class="font-medium flex items-center gap-2">
                Go to Book Details
                <ArrowRightIcon
                  class="w-4 h-4 -translate-x-full opacity-0 group-hover:translate-x-0 group-hover:opacity-100 duration-150"
                />
              </label>
            </RouterLink>

            <component
              :is="record.transaction.book.type === 'consignment' ? 'router-link' : 'span'"
              :to="'/consignments/' + record.transaction.book.id"
              class="group transition-colors duration-150 min-h-[6rem] flex flex-col justify-around px-4 py-3 rounded-lg w-full"
              :class="[
                record.transaction.book.type === 'cash'
                  ? 'bg-white border'
                  : 'bg-border-dark/25 hover:bg-border-dark/50'
              ]"
            >
              <CogIcon
                class="w-6 h-6 stroke-sub"
                :class="{
                  'stroke-black/50': record.transaction.book.type === 'cash'
                }"
              />

              <label
                class="font-medium flex items-center gap-2"
                :class="{
                  'text-black/50': record.transaction.book.type === 'cash'
                }"
              >
                Go to Consignment Details
                <ArrowRightIcon
                  v-if="record.transaction.book.type === 'consignment'"
                  class="w-4 h-4 -translate-x-full opacity-0 group-hover:translate-x-0 group-hover:opacity-100 duration-150"
                />
              </label>
            </component>
          </div>
        </div>
      </div>

      <LoadingIndicator v-else />
    </Modal>
  </Teleport>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { onClickOutside } from '@vueuse/core'
import Transactions from './all.vue'
import Modal from '../../components/Modal.vue'
import router from '../../router'
import { useRoute } from 'vue-router'
import { BookOpenIcon, CogIcon, ArrowRightIcon } from '@heroicons/vue/24/outline'
import LoadingIndicator from '../../components/LoadingIndicator.vue'

const record = ref(null)
function getTransaction() {
  axios
    .get('/api/transactions', {
      params: {
        id: useRoute().params.id
      }
    })
    .then((response) => {
      record.value = response.data.data
    })
    .catch((error) => {
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

<style scoped></style>
