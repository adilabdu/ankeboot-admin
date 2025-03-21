<template>
  <div class="highlightable w-full flex flex-col sm:gap-4" :class="[title ? 'gap-4' : '']">
    <!-- Title section -->
    <div
      v-if="!condensed"
      class="flex gap-8 sm:flex-col sm:items-start justify-between items-start sm:gap-3"
    >
      <div class="flex flex-col justify-between sm:gap-1">
        <h1 v-if="title" class="text-base font-medium capitalize">
          {{ title }}
        </h1>
        <h2 class="text-subtitle text-justify">
          <slot name="description">
            A template Table component built with Vue 3 and Tailwind CSS. Insert your data into the
            <span class="font-mono text-brand-primary">`&lt;slot&gt;`</span>
            named
            <span class="font-mono text-brand-primary">`rows`</span>
          </slot>
        </h2>
      </div>
      <div class="sm:w-full">
        <slot name="action">
          <button
            @click="$emit('TableButtonClick')"
            class="px-6 py-2.5 bg-brand-primary rounded-lg text-white flex justify-between sm:justify-center items-center gap-2 sm:w-full"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="fill-white">
              <path
                d="M18.948 11.112C18.511 7.67 15.563 5 12.004 5c-2.756 0-5.15 1.611-6.243 4.15-2.148.642-3.757 2.67-3.757 4.85 0 2.757 2.243 5 5 5h1v-2h-1c-1.654 0-3-1.346-3-3 0-1.404 1.199-2.757 2.673-3.016l.581-.102.192-.558C8.153 8.273 9.898 7 12.004 7c2.757 0 5 2.243 5 5v1h1c1.103 0 2 .897 2 2s-.897 2-2 2h-2v2h2c2.206 0 4-1.794 4-4a4.008 4.008 0 0 0-3.056-3.888z"
              ></path>
              <path d="M13.004 14v-4h-2v4h-3l4 5 4-5z"></path>
            </svg>
            <span class="font-medium whitespace-nowrap">
              Export <span class="font-mono"> CSV </span>
            </span>
          </button>
        </slot>
      </div>
    </div>

    <!-- Table wrapper -->
    <div class="flex flex-col gap-4">
      <!-- Filter Actions -->
      <div
        v-if="noSearchResults || data.length > 0"
        class="w-full flex sm:flex-col sm:gap-2 sm:flex-col-reverse justify-between items-center"
      >
        <!-- Search -->
        <!--                <SearchBar :placeholder="'Search'" :min-query-length="0" @type="" />-->
        <TextInput
          v-model="searchQuery"
          label="Search"
          label-position="left"
          placeholder="Search for items"
        />

        <!-- Hide columns -->
        <SelectMenu
          v-if="hideable"
          placeholder="Choose the columns to hide"
          label="Hide Columns"
          @change="updateHideList"
          :choices="hideColumns"
          :list="objectKeys"
        />

        <!-- Date search -->
        <div class="flex items-center gap-4 sm:gap-2 sm:w-full">
          <DatePicker class="w-48" align="left" v-model="dateQuery.start" />
          <label class="font-medium text-subtitle"> to </label>
          <DatePicker class="w-48" v-model="dateQuery.end" />
        </div>
      </div>

      <!-- Content -->
      <div class="rounded-md overflow-auto border-[0.5px] border-border-light">
        <!-- Table -->
        <table class="w-full bg-white shadow-sm">
          <!-- Header row -->
          <tr
            class="h-11 text-left bg-[#F9FAFB] border-border-dark border-b-[1px] uppercase text-subtitle font-medium text-xs"
          >
            <slot name="headers">
              <template v-if="!!data && data.length > 0" v-for="(name, index) in objectKeys">
                <HeaderCell
                  :header="name"
                  :column="columns[index]"
                  :center="center.includes(name)"
                  :right="right.includes(name)"
                  :sortable="sortable.includes(name)"
                  v-if="!hideColumns.includes(name)"
                  :class="[hideLabels.includes(name) ? 'text-transparent' : '']"
                  @sort="sort"
                >
                  {{ name.replaceAll('_', ' ') }}
                </HeaderCell>
              </template>
              <HeaderCell
                :center="true"
                :sortable="false"
                v-if="!!data && actions && data.length > 0"
                header="actions"
              >
                Actions
              </HeaderCell>
            </slot>
          </tr>

          <!-- Data row -->
          <tr
            class="hover:bg-black/5 h-10 border-border-light border-b-[1px] last:border-b-0"
            v-if="!!data && !loading && data.length > 0"
            v-for="(row, index) in data"
            :key="index"
          >
            <slot name="rows" v-if="row" :record="row" />
            <slot v-if="actions" name="actions">
              <Cell name="actions">
                <div class="flex gap-4 items-center justify-center">
                  <p
                    @click="$emit('edit', row)"
                    class="cursor-pointer text-xs text-brand-primary hover:underline text-start whitespace-nowrap"
                  >
                    Edit
                  </p>
                  <p
                    @click="$emit('delete', row)"
                    class="cursor-pointer text-xs text-brand-primary hover:underline text-start whitespace-nowrap"
                  >
                    Delete
                  </p>
                </div>
              </Cell>
            </slot>
          </tr>
        </table>

        <!-- Case: Loading results -->
        <div
          class="flex items-center justify-center min-h-[7.5rem] border-border-light border-b-[1px] w-full"
          v-if="loading"
        >
          <slot name="search-empty">
            <div class="gap-4 flex flex-col items-center justify-center py-8">
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

              <div class="flex flex-col justify-center items-center gap-0.5">
                <p class="text-sm font-medium">Fetching results</p>
                <p class="text-sm text-subtitle">Please wait, retrieving your data</p>
              </div>
            </div>
          </slot>
        </div>

        <!-- Case: No search results -->
        <div
          class="flex items-center justify-center min-h-[7.5rem] border-border-light border-b-[1px] w-full"
          v-else-if="!!data && data.length === 0"
        >
          <slot name="search-empty">
            <div class="gap-4 flex flex-col items-center justify-center py-8">
              <svg
                width="18"
                height="18"
                viewBox="0 0 18 18"
                class="fill-brand-primary"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M18 9C18 13.9706 13.9706 18 9 18C4.02944 18 0 13.9706 0 9C0 4.02944 4.02944 0 9 0C13.9706 0 18 4.02944 18 9ZM2.0144 9C2.0144 12.858 5.14196 15.9856 9 15.9856C12.858 15.9856 15.9856 12.858 15.9856 9C15.9856 5.14196 12.858 2.0144 9 2.0144C5.14196 2.0144 2.0144 5.14196 2.0144 9Z"
                />
                <rect x="8" y="5" width="2" height="5" />
                <rect x="8" y="11" width="2" height="2" />
              </svg>

              <div class="flex flex-col justify-center items-center gap-0.5">
                <p class="text-sm font-medium">No item</p>
                <p class="text-sm text-subtitle">No item was found in your search</p>
              </div>
            </div>
          </slot>
        </div>

        <!-- Case: No data -->
        <div
          class="flex items-center justify-center min-h-[7.5rem] border-border-light border-b-[1px] w-full"
          v-else-if="noSearchResults"
        >
          <slot name="empty">
            <div class="gap-4 flex flex-col items-center justify-center py-8">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                class="fill-brand-primary"
              >
                <path
                  d="M3 8v11c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.806 5 19c0-.101.009-.191.024-.273.112-.576.584-.717.988-.727H21V4c0-1.103-.897-2-2-2H6c-1.206 0-3 .799-3 3v3zm3-4h13v12H5V5c0-.806.55-.988 1-1z"
                ></path>
                <path d="M11 14h2v-3h3V9h-3V6h-2v3H8v2h3z"></path>
              </svg>

              <div class="flex flex-col justify-center items-center gap-0.5">
                <p class="text-sm font-medium">No item</p>
                <p class="text-sm text-subtitle">Register a new item into inventory.</p>
              </div>

              <button
                @click="$emit('newItem')"
                class="bg-brand-primary flex items-center justify-around gap-2 px-2.5 my-2 rounded-md"
              >
                <span class="text-white text-2xl font-light align-text-top inline-block pb-1"
                  >+</span
                >
                <span class="text-white font-medium">New Item</span>
              </button>
            </div>
          </slot>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="!!data" class="w-full flex sm:flex-col items-center justify-between sm:gap-2">
        <Dropdown
          :list="[5, 10, 25, 50, 'All']"
          v-model="showPerPage"
          label="per page"
          drop-direction="up"
        />
        <p class="sm:py-0.5 sm:order-1 text-subtitle sm:w-full sm:text-right">
          Showing
          <span class="font-medium">
            {{ paginateFrom ?? 0 }}
          </span>
          to
          <span class="font-medium">
            {{ paginateTo ?? 0 }}
          </span>
          of
          <span class="font-medium">
            <span :class="{ hidden: totalItems < 1000 }">~</span>
            {{ totalItems }}
          </span>
        </p>
        <div class="flex gap-2 items-center justify-center sm:w-full">
          <button
            :disabled="!!!previousPage"
            @click="fetchPrevious(showPerPage)"
            :class="[previousPage ? 'cursor-pointer' : 'opacity-50 cursor-not-allowed']"
            class="sm:w-full h-10 bg-white text-black px-5 rounded-md border-[1px] border-border-dark"
          >
            Previous
          </button>
          <button
            :disabled="!!!nextPage"
            @click="fetchNext(showPerPage)"
            :class="[nextPage ? 'cursor-pointer' : 'opacity-50 cursor-not-allowed']"
            class="sm:w-full h-10 bg-white text-black px-5 rounded-md border-[1px] border-border-dark"
          >
            Next
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import HeaderCell from './HeaderCell.vue'
import SelectMenu from '../SelectMenu.vue'
import Cell from './Cell.vue'
import Dropdown from '../Dropdown.vue'
import SearchBar from '../SearchBar.vue'
import DatePicker from '../Form/DatePicker.vue'
import TextInput from '../Form/TextInput.vue'

const props = defineProps({
  condensed: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: null
  },
  sortable: {
    type: Array,
    default: []
  },
  center: {
    type: Array,
    default: []
  },
  right: {
    type: Array,
    default: []
  },
  hide: {
    type: Array,
    default: []
  },
  hideable: {
    type: Boolean,
    default: true
  },
  hideLabels: {
    type: Array,
    default: []
  },
  actions: {
    type: Boolean,
    default: true
  },
  searchable: {
    type: Array,
    default: []
  },
  perPage: {
    type: Number,
    default: 10
  },
  mapper: {
    type: Function,
    required: true
  },
  url: {
    type: String,
    required: true
  },
  columns: {
    type: Array,
    default: []
  }
})

const dateQuery = ref({
  start: {
    date: '',
    month: '',
    year: ''
  },
  end: {
    date: '',
    month: '',
    year: ''
  }
})
watch(
  dateQuery,
  () => {
    fetchData(1, showPerPage.value)
  },
  { deep: true }
)

const searchQuery = ref(null)
watch(searchQuery, () => {
  if (searchQuery.value === '') searchQuery.value = null
  fetchData(1, showPerPage.value)
})

const showPerPage = ref(props.perPage)
watch(showPerPage, () => {
  if (showPerPage.value === 'All') {
    showPerPage.value = totalItems.value
  }
  fetchData(1, showPerPage.value)
})

const objectKeys = computed(() => {
  if (data.value[0]) {
    return Object.keys(data.value[0])
  }

  return []
})
const hideColumns = ref(props.hide)
function updateHideList(list) {
  hideColumns.value = list.value
}

const data = ref([])
const loading = ref(true)
const previousPage = ref(null)
const nextPage = ref(null)
const totalItems = ref(null)
const paginateFrom = ref(0)
const paginateTo = ref(0)

const handleResponse = (response) => {
  console.log(response.data.data)
  data.value = response.data.data.data['hits'].map(props.mapper)
  previousPage.value = response.data.data['prev_page_url']
  nextPage.value = response.data.data['next_page_url']
  totalItems.value = response.data.data.total
  paginateFrom.value = response.data.data.from
  paginateTo.value = response.data.data.to
}
const handleError = (error) => {
  alert(`Error while loading paginated books ${error}`)
}
function fetchData(page = 1, per_page = 10, sort_by = 'id', descending = false) {
  loading.value = true

  const params = {
    page,
    per_page
  }

  if (sort_by !== 'id') {
    params.order_by = sort_by
    params.desc = descending
  }

  if (searchQuery.value !== null) {
    params.query = searchQuery.value
  }

  if (dateQuery.value.start.date !== '') {
    params.from_date = Math.floor(
      new Date(
        dateQuery.value.start.year,
        dateQuery.value.start.month,
        dateQuery.value.start.date
      ).valueOf() / 1000
    )
  }

  if (dateQuery.value.end.date !== '') {
    params.to_date = Math.floor(
      new Date(
        dateQuery.value.end.year,
        dateQuery.value.end.month,
        parseInt(dateQuery.value.end.date) + 1
      ).valueOf() / 1000
    )
  }

  axios
    .get(props.url, {
      params: params
    })
    .then(handleResponse)
    .catch(handleError)
    .finally(() => (loading.value = false))
}

function fetchPrevious(per_page = 10) {
  loading.value = true

  axios
    .get(previousPage.value, {
      params: {
        per_page
      }
    })
    .then(handleResponse)
    .catch(handleError)
    .finally(() => (loading.value = false))
}

function fetchNext(per_page = 10) {
  loading.value = true

  axios
    .get(nextPage.value, {
      params: {
        per_page
      }
    })
    .then(handleResponse)
    .catch(handleError)
    .finally(() => (loading.value = false))
}

function sort(column) {
  fetchData(1, showPerPage.value, column.header, column.descending ? 1 : 0)
}

const noSearchResults = computed(() => {
  return (searchQuery.value !== null || !dateQueryEmpty.value) && data.value.length === 0
})

const dateQueryEmpty = computed(() => {
  return dateQuery.value.start.date === '' && dateQuery.value.end.date === ''
})

onMounted(() => {
  fetchData()
})
</script>

<style scoped></style>
