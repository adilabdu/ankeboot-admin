<script setup>

  import { computed, onMounted, ref, watch } from "vue";

  import HeaderCell from "./HeaderCell.vue";
  import SelectMenu from "../SelectMenu.vue";
  import Cell from "./Cell.vue";
  import Dropdown from "../Dropdown.vue";
  import SearchBar from "../SearchBar.vue";
  import DatePicker from "../Form/DatePicker.vue";
  // import ItemDescription from "../ItemDescription.vue";

  const props = defineProps({
    data: {
      type: Array,
      default: [],
    },
    condensed: {
      type: Boolean,
      default: false,
    },
    title: {
      type: String,
      default: null,
    },
    sortable: {
      type: Array,
      default: [],
    },
    center: {
      type: Array,
      default: [],
    },
    right: {
      type: Array,
      default: [],
    },
    hide: {
      type: Array,
      default: []
    },
    hideable: {
      type: Boolean,
      default: true,
    },
    hideLabels: {
      type: Array,
      default: [],
    },
    actions: {
      type: Boolean,
      default: true,
    },
    searchable: {
      type: Array,
      default: (props) => Object.keys(props.data[0])
    },
    perPage: {
      type: Number,
      default: 10,
    },
  })

  const hideColumns = ref(props.hide)
  function updateHideList(list) {
    hideColumns.value = list.value
  }

  const filteredData = ref(props.data)
  const objectKeys = ref(!! props.data[0] ? Object.keys(props.data[0]) : [])
  const limit = ref(25)
  const page = ref(1)

  const dateSearchable = ref(false)
  const stringSearchable = ref(false)

  function limitTable(item) {

    page.value = 1

    if (item === 'All' || item > (filteredData.value.length)) {
      limit.value = filteredData.value.length
    } else {
      limit.value = item
    }

  }

  const displayData = computed(() => {

    let dynamicLimit = limit.value
    if (page.value * limit.value >= filteredData.value.length && limit.value !== filteredData.value.length) {
      if (filteredData.value.length % limit.value !== 0) {
        dynamicLimit = filteredData.value.length % limit.value
      }
    }

    return [...Array(dynamicLimit).keys()].map((number) => {
      return number + ((page.value - 1) * limit.value)
    })

  })

  function paginateBack() {
    if (page.value > 1) {
      page.value--;
    } else {
    }
  }

  function paginateNext() {
    if ((page.value * limit.value) < filteredData.value.length) {
      page.value++;
    } else {
    }
  }

  const stringQuery = ref('')
  function setStringQuery(query) {
    stringQuery.value = query
  }

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
    },
  })

  // Watch for search query changes to update the data
  watch([stringQuery, dateQuery.value], () => {

    // Start from string search
    filteredData.value = searchString(stringQuery.value)

    if (!! dateQuery.value.start.date) {
      filteredData.value = searchDate(dateQuery.value.start, 'start')
    }

    if (!! dateQuery.value.end.date) {
      filteredData.value = searchDate(dateQuery.value.end, 'end')
    }

  })

  function searchDate(event, range) {

    page.value = 1

    return filteredData.value.filter((object) => {

      let found = false

      props.searchable.forEach(column => {

        if (props.data[0][column] instanceof Date) {

          if (range === 'start') {
            found = found || object[column] >= new Date(event.year, event.month, event.date)
          } else {
            found = found || object[column] <= new Date(event.year, event.month, event.date)
          }

          if (event.date === null) {
            found = true
          }

        }

      })

      return found

    })

  }

  function searchString(query) {

    page.value = 1;

    return props.data.filter((object) => {

      let found = false

      props.searchable.forEach(column => {

        found = found || object[column].toString().toLowerCase().includes(query.toLowerCase())

      })

      return found

    })

  }

  function sort(sort) {

    page.value = 1;

    if (props.data[0][sort.header] instanceof Date) {

      filteredData.value.sort((a, b) => {
        if (sort.descending) {
          return b[sort.header] - a[sort.header]
        }

        return a[sort.header] - b[sort.header]
      })

    }

    if (typeof props.data[0][sort.header] === 'number') {

      filteredData.value.sort((a, b) => {
        if (sort.descending) {
          return b[sort.header] - a[sort.header]
        }

        return a[sort.header] - b[sort.header]
      })

    } else if (typeof props.data[0][sort.header] === 'string') {

      filteredData.value.sort((a, b) => {

        return compareStrings(a[sort.header], b[sort.header], sort.descending)
      })

    }

  }

  function compareStrings(a, b, descending) {
    a = a.toLowerCase();
    b = b.toLowerCase();

    if (descending) {
      return a < b ? 1 : a > b ? -1 : 0;
    }

    return (a < b) ? -1 : (a > b) ? 1 : 0;
  }

  function arrayToStrings(array) {
    return array.map(item => {
      return item.toString().replaceAll('_', ' ')
    }).join(', ')
  }

  onMounted(() => {

    limitTable(props.perPage)

    props.searchable.forEach(column => {
        if (!! props.data[0]) {
            if (props.data[0][column] instanceof Date) {
                dateSearchable.value = true
            }

            if(
                typeof props.data[0][column] === 'string' ||
                typeof props.data[0][column] === 'number'
            ) {
                stringSearchable.value = true
            }
        }
    })

  })

</script>

<template>

  <div class="flex flex-col sm:gap-4" :class="[title ? 'gap-4' : '']">

    <div class="flex gap-8 sm:flex-col sm:items-start justify-between items-start sm:gap-3">
      <div class="flex flex-col justify-between sm:gap-1">
        <h1 v-if="title" class="text-xl font-medium capitalize">{{ title }}</h1>
        <h2 class="text-subtitle text-justify">
          <slot name="description">
            A template Table component built with Vue 3 and Tailwind CSS. Insert your data into the
            <span class="font-mono text-brand-primary">`&lt;slot&gt;`</span> named <span class="font-mono text-brand-primary">`rows`</span>
          </slot>
        </h2>
      </div>
      <div class="sm:w-full">
        <slot name="action">
          <button @click="$emit('TableButtonClick')" class="px-6 py-2.5 bg-brand-primary rounded-lg text-white flex justify-between sm:justify-center items-center gap-2 sm:w-full">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="fill-white"><path d="M18.948 11.112C18.511 7.67 15.563 5 12.004 5c-2.756 0-5.15 1.611-6.243 4.15-2.148.642-3.757 2.67-3.757 4.85 0 2.757 2.243 5 5 5h1v-2h-1c-1.654 0-3-1.346-3-3 0-1.404 1.199-2.757 2.673-3.016l.581-.102.192-.558C8.153 8.273 9.898 7 12.004 7c2.757 0 5 2.243 5 5v1h1c1.103 0 2 .897 2 2s-.897 2-2 2h-2v2h2c2.206 0 4-1.794 4-4a4.008 4.008 0 0 0-3.056-3.888z"></path><path d="M13.004 14v-4h-2v4h-3l4 5 4-5z"></path></svg>
            <span class="font-medium whitespace-nowrap">
            Export <span class="font-mono"> CSV </span>
          </span>
          </button>
        </slot>
      </div>
    </div>

    <div class="flex flex-col gap-4">

      <!-- Filter Actions -->
      <div v-if="stringSearchable || dateSearchable || hideable" class="w-full flex sm:flex-col sm:gap-2 sm:flex-col-reverse justify-between items-center">

        <!-- TODO: figure a valid method to pass non Date type columns to the placeholder -->
        <!-- Search -->
        <SearchBar v-if="stringSearchable" :placeholder="'Search for ' + arrayToStrings(searchable)" :min-query-length="0" @type="setStringQuery($event)" />

        <!-- Hide columns -->
        <SelectMenu
            v-if="hideable"
            class=""
            @change="updateHideList"
            placeholder="Choose the columns to hide"
            label="Hide Columns"
            :choices="hideColumns"
            :list="objectKeys"
        />

        <!-- Date search -->
        <div v-if="dateSearchable" class="flex items-center gap-4">
          <DatePicker class="w-48" align="left" v-model="dateQuery.start" />
          <label class="font-medium text-subtitle"> to </label>
          <DatePicker class="w-48" v-model="dateQuery.end" />
        </div>

      </div>

      <!-- Content -->
      <div class="rounded-md overflow-auto border-[0.5px] border-border-light">

        <!-- Table -->
        <table class="w-full bg-white shadow-sm">
          <tr class="h-11 text-left bg-[#F9FAFB] border-border-dark border-b-[1px] uppercase text-subtitle font-medium text-xs">
            <slot name="headers">

              <template v-if="data.length > 0" v-for="name in objectKeys">
                <HeaderCell
                    :center="center.includes(name)"
                    :right="right.includes(name)"
                    :sortable="sortable.includes(name)"
                    :header="name"
                    v-if="!hideColumns.includes(name) && !hideLabels.includes(name)"
                    @sort="sort"
                >
                  {{ name.replaceAll('_', ' ') }}
                </HeaderCell>
              </template>
              <HeaderCell :center="true" :sortable="false" v-if="actions && data.length > 0" header="actions">
                Actions
              </HeaderCell>

            </slot>
          </tr>

          <tr class="hover:bg-black/5 h-10 border-border-light border-b-[1px]" v-if="filteredData.length > 0" v-for="index in displayData" :key="index">
            <slot name="rows" :hide="hideColumns" v-if="filteredData[index]" :record="filteredData[index]" />
            <slot v-if="actions" name="actions">
              <Cell name="actions">
                <div class="flex gap-4 items-center justify-center">
                  <p @click="$emit('edit', filteredData[index])" class="cursor-pointer text-xs text-brand-primary hover:underline text-start whitespace-nowrap">Edit</p>
                  <p @click="$emit('delete', filteredData[index])" class="cursor-pointer text-xs text-brand-primary hover:underline text-start whitespace-nowrap">Delete</p>
                </div>
              </Cell>
            </slot>
          </tr>

        </table>

        <!-- Case: No data -->
        <div class="flex items-center justify-center min-h-[7.5rem] border-border-light border-b-[1px] w-full" v-if="data.length === 0">

          <slot name="empty">
            <div class="gap-4 flex flex-col items-center justify-center py-8">

              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="fill-brand-primary"><path d="M3 8v11c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.806 5 19c0-.101.009-.191.024-.273.112-.576.584-.717.988-.727H21V4c0-1.103-.897-2-2-2H6c-1.206 0-3 .799-3 3v3zm3-4h13v12H5V5c0-.806.55-.988 1-1z"></path><path d="M11 14h2v-3h3V9h-3V6h-2v3H8v2h3z"></path></svg>

              <div class="flex flex-col justify-center items-center gap-0.5">
                <p class="text-sm font-medium"> No item </p>
                <p class="text-sm text-subtitle"> Register a new item into inventory. </p>
              </div>

              <button class="bg-brand-primary flex items-center justify-around gap-2 px-2.5 my-2 rounded-md">

                <span class="text-white text-2xl font-light align-text-top inline-block pb-1">+</span>
                <span class="text-white font-medium">New Item</span>

              </button>

            </div>
          </slot>

        </div>

        <!-- Case: No search results -->
        <div class="flex items-center justify-center min-h-[7.5rem] border-border-light border-b-[1px] w-full" v-if="filteredData.length === 0 && data.length > 0">

          <slot name="search-empty">
            <div class="gap-4 flex flex-col items-center justify-center py-8">

              <svg width="18" height="18" viewBox="0 0 18 18" class="fill-brand-primary" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 9C18 13.9706 13.9706 18 9 18C4.02944 18 0 13.9706 0 9C0 4.02944 4.02944 0 9 0C13.9706 0 18 4.02944 18 9ZM2.0144 9C2.0144 12.858 5.14196 15.9856 9 15.9856C12.858 15.9856 15.9856 12.858 15.9856 9C15.9856 5.14196 12.858 2.0144 9 2.0144C5.14196 2.0144 2.0144 5.14196 2.0144 9Z" />
                <rect x="8" y="5" width="2" height="5" />
                <rect x="8" y="11" width="2" height="2" />
              </svg>

              <div class="flex flex-col justify-center items-center gap-0.5">
                <p class="text-sm font-medium"> No item </p>
                <p class="text-sm text-subtitle"> No item was found in your search </p>
              </div>

            </div>
          </slot>

        </div>

      </div>

      <!-- Pagination -->
      <div class="w-full flex sm:flex-col items-center justify-between sm:gap-2">
        <Dropdown :list="[5, 10, 25, 50, 'All']" :choice="[5, 10, 25, 50, 'All'].indexOf(perPage)" label="per page" drop-direction="up" @change="limitTable" />
        <p class="sm:py-0.5 sm:order-1 text-subtitle sm:w-full sm:text-right">
          Showing
          <span class="font-medium">
            {{ (page * limit) - (limit - 1)  }}
          </span>
          to
          <span class="font-medium">
            {{ page * limit > filteredData.length ? filteredData.length : page * limit }}
          </span>
          of
          <span class="font-medium">
            {{ filteredData.length }}
          </span>
        </p>
        <div class="flex gap-2 items-center justify-center sm:w-full">
          <button
              @click="paginateBack"
              :class="[page <= 1 ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer']"
              class="sm:w-full h-10 bg-white text-black px-5 rounded-md border-[1px] border-border-dark"
          >
            Previous
          </button>
          <button
              @click="paginateNext"
              :class="[(page * limit) < filteredData.length ? 'cursor-pointer' : 'opacity-50 cursor-not-allowed']"
              class="sm:w-full h-10 bg-white text-black px-5 rounded-md border-[1px] border-border-dark">Next</button>
        </div>
      </div>

      <!-- Summary -->
      <slot name="summary" :dateQuery="dateQuery" :data="filteredData">
      </slot>

    </div>

  </div>

</template>

<style scoped>

  tr:last-child {
    @apply border-none;
  }

</style>
