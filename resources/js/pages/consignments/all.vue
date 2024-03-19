<template>
  <Table
    :loading="!!!consignments"
    title="Consignments"
    :data="consignments"
    :center="['code', 'balance', 'payable', 'settled']"
    :right="[]"
    :sortable="['category', 'code', 'balance', 'payable', 'title', 'purchase_type', 'added_on']"
    :searchable="['title', 'added_on', 'code']"
    :hideable="false"
    :hide="['id']"
    :hide-labels="['id']"
    :actions="true"
  >
    <template #description>
      <p>Complete list of consignment books in the inventory</p>
    </template>

    <template #rows="data">
      <Cell
        name="code"
        :hide="data.hide"
        class="w-[2%] text-xs font-semibold text-subtitle text-center"
        >{{ data['record']['code'].toLowerCase() }}
      </Cell>
      <Cell class="w-[2%]" name="category" :hide="data.hide"
        >{{ data['record']['category'] }}
      </Cell>
      <LinkCell
        class="w-[90%]"
        name="title"
        :main="true"
        :hide="data.hide"
        :value="data['record']['title']"
        :to="'books/' + data['record']['id']"
      >
        <RouterLink :to="'consignments/' + data['record']['id']">
          {{ data['record']['title'] }}
        </RouterLink>
      </LinkCell>
      <Cell
        name="balance"
        :hide="data.hide"
        class="w-[2%] text-xs font-semibold text-subtitle text-center"
        >{{ data['record']['balance'] }}
      </Cell>
      <Cell
        name="payable"
        :hide="data.hide"
        class="w-[2%] text-xs font-semibold text-subtitle text-center"
        >{{ data['record']['payable'] }}
      </Cell>
      <Cell
        name="settled"
        :hide="data.hide"
        class="w-[2%] text-xs font-semibold text-subtitle text-center"
        >{{ data['record']['settled'] }}
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
import { onMounted, ref } from 'vue'

import Table from '../../components/Table/Table.vue'
import Cell from '../../components/Table/Cell.vue'
import LinkCell from '../../components/Table/LinkCell.vue'
import DateCell from '../../components/Table/DateCell.vue'
import axios from 'axios'

const consignments = ref(null)
function getBooks() {
  axios
    .get('/api/consignments/books')
    .then((response) => {
      consignments.value = response.data.data.map((book) => {
        return {
          id: book.id,
          code: book.code,
          category: book.category,
          title: book.title,
          balance: book.balance,
          payable: book.payable,
          settled: book.settled,
          added_on: new Date(book['created_at'] * 1000)
        }
      })
    })
    .catch((error) => {
      alert(`Error while fetching consignment books, ${error}`)
    })
}

onMounted(() => {
  getBooks()
})
</script>

<style scoped></style>
