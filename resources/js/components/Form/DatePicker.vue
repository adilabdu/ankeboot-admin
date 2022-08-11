<template>

<!--  <div class="w-36 h-10 bg-white rounded-md border border-border-light shadow-sm outline-2 outline outline-offset-2 outline-lime-500"></div>-->

  <div class="flex flex-col items-end gap-1 relative sm:w-full h-fit">

    <label v-if="!! label" class="text-subtitle font-medium text-left inline w-full">
        {{ label }}
        <span v-if="required" class="text-red-600">*</span>
    </label>

    <div ref="form" tabindex="0" @keyup.enter="togglePicker" @click="togglePicker" class="w-full focus:outline-2 focus:outline-brand-primary focus:outline-offset-2 border-[0.5px] border-border-light rounded-md sm:w-full bg-white h-10 shadow-sm flex items-center justify-between">

      <p class="px-3" :class="[modelValue.date ? 'text-brand-primary' : 'text-subtitle']">{{ visibleDate }}</p>
      <input :value="visibleDate === 'dd MM YYYY' ? null : visibleDate" :required="required" class="sr-only" />

      <div class="flex items-center justify-center h-full pr-3 gap-1 cursor-pointer">
        <div class="h-full w-1 blur-lg bg-white" />
        <svg width="18" height="20" viewBox="0 0 18 20" class="fill-subtitle" xmlns="http://www.w3.org/2000/svg">
          <path d="M4 9H6V11H4V9ZM4 13H6V15H4V13ZM8 9H10V11H8V9ZM8 13H10V15H8V13ZM12 9H14V11H12V9ZM12 13H14V15H12V13Z" />
          <path d="M2 20H16C17.103 20 18 19.103 18 18V4C18 2.897 17.103 2 16 2H14V0H12V2H6V0H4V2H2C0.897 2 0 2.897 0 4V18C0 19.103 0.897 20 2 20ZM16 6L16.001 18H2V6H16Z" />
        </svg>
      </div>

    </div>

    <!-- TODO: Consider if min-w-[347px] is necessary -->
    <div :class="[align === 'left' ? 'left-0' : '']" ref="datepicker" v-if="showPicker" class="z-50 translate-y-full -mb-2 bottom-0 absolute min-w-[304.2px] overflow-hidden bg-white rounded-md shadow-md border-[0.5px] border-border-dark flex flex-col">

      <div class="w-full flex justify-between items-center bg-[#F9FAFB] border-b border-border-dark shadow-sm">

        <div @click="displayDatePicker ? previous() : () => {}" class="cursor-pointer p-4 flex items-center">
          <button type="button">
            <svg :class="[!displayDatePicker ? 'opacity-25' : '']" class="fill-subtitle rotate-180" width="11" height="16" viewBox="0 0 11 16" xmlns="http://www.w3.org/2000/svg">
              <path d="M3.06096 15.061L10.121 7.99996L3.06096 0.938965L0.938965 3.06096L5.87896 7.99996L0.938965 12.939L3.06096 15.061Z" />
            </svg>
          </button>
        </div>
        <label class="grow text-center font-semibold text-subtitle">
          <span class="cursor-pointer" :class="[displayYearPicker ? 'hidden' : '', displayMonthPicker ? 'text-subtitle/50' : '']" @click="toggleWhichPicker('month')">{{ months[month] }}</span>
          <span>&nbsp;</span>
          <span class="cursor-pointer" :class="[displayYearPicker ? 'text-subtitle/50' : '', displayMonthPicker ? 'text-subtitle/50' : '']" @click="toggleWhichPicker('year')">{{ year }}</span>
        </label>
        <div @click="displayDatePicker ? next() : () => {}" class="cursor-pointer p-4 flex items-center">
          <button type="button">
            <svg :class="[!displayDatePicker ? 'opacity-25' : '']" class="fill-subtitle" width="11" height="16" viewBox="0 0 11 16" xmlns="http://www.w3.org/2000/svg">
              <path d="M3.06096 15.061L10.121 7.99996L3.06096 0.938965L0.938965 3.06096L5.87896 7.99996L0.938965 12.939L3.06096 15.061Z" />
            </svg>
          </button>
        </div>

      </div>

      <button type="button" @click="setToToday" :class="[isSetToToday ? 'bg-brand-secondary border-brand-primary' : 'border-border-light', displayDatePicker ? '' : 'hidden']" class="flex justify-between items-center focus:outline-brand-primary focus:outline-offset-2 px-4 py-2 mx-3 rounded-md my-2 border-[1px] shadow-sm">
        <span :class="[isSetToToday ? 'text-brand-primary' : 'text-subtitle']" class="font-medium">Today&nbsp;</span>
        <span :class="isSetToToday ? 'text-brand-primary' : 'text-subtitle'" class="font-medium">{{ today }}</span>
      </button>

      <div :class="[displayDatePicker ? '' : 'hidden']" class="grid grid-cols-7 grid-rows-7 p-2">

        <label v-for="i in days" class=" font-medium text-subtitle p-2 border-2 border-transparent flex items-center justify-center rounded-md">
          {{ i }}
        </label>

        <label v-for="_ in dateOffset" class="p-2 border-2 border-transparent flex items-center justify-center rounded-lg">
          {{   }}
        </label>

        <label tabindex="0" @keyup.enter="toggleSelectedDate(i, month, year)" @click="toggleSelectedDate(i, month, year)" v-for="i in numberOfDays" :class="[ i === modelValue.date && month === modelValue.month && year === modelValue.year ? 'hover:bg-brand-primary font-medium text-white bg-brand-primary' : '' ]" class="font-medium cursor-pointer p-2 border-2 border-transparent flex items-center justify-center hover:bg-brand-secondary rounded-lg transition duration-150">
          {{ i }}
        </label>

      </div>

      <div :class="[displayYearPicker ? '' : 'hidden']" class="grid grid-cols-4 grid-rows-7 p-2">

        <label tabindex="0" @keyup.enter="toggleSelectedYear(i)" @click="toggleSelectedYear(i)" v-for="i in availableYears" :class="[ i === year ? 'hover:bg-brand-primary font-medium text-white bg-brand-primary' : '' ]" class="font-medium cursor-pointer p-2 border-2 border-transparent flex items-center justify-center hover:bg-brand-secondary rounded-lg transition duration-150">
          {{ i }}
        </label>

      </div>

      <div :class="[displayMonthPicker ? '' : 'hidden']" class="grid grid-cols-3 p-2">

        <label tabindex="0" @keyup.enter="toggleSelectedMonth(index)" @click="toggleSelectedMonth(index)" v-for="(m, index) in months" :class="[ index === month ? 'hover:bg-brand-primary font-medium text-white bg-brand-primary' : '' ]" class="font-medium cursor-pointer p-2 border-2 border-transparent flex items-center justify-center hover:bg-brand-secondary rounded-lg transition duration-150">
          {{ m }}
        </label>

      </div>

    </div>

  </div>

</template>

<script setup>

  import { ref, computed, onMounted } from "vue";
  import { onClickOutside } from "@vueuse/core";

  const props = defineProps({
    label: {
      type: String,
      default: ''
    },
    year: {
      type: Number,
      default: new Date().getFullYear(),
    },
    month: {
      type: Number,
      default: new Date().getMonth(),
    },
    availableYears: {
      type: Array,
      default: [2014, 2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025],
    },
    align: {
      type: String,
      default: "right",
    },
    modelValue: {
      type: Object,
    },
    required: {
      type: Boolean,
      default: false
    }
  })

  const emit = defineEmits(['update:modelValue'])

  const isSetToToday = computed(() => {
    const today = new Date();
    return today.getFullYear() === props.modelValue.year &&
        today.getMonth() === props.modelValue.month &&
        today.getDate() === props.modelValue.date;
  })
  function setToToday() {

    emit('update:modelValue', {
        date: new Date().getDate(),
        month: new Date().getMonth(),
        year: new Date().getFullYear()
    })
    year.value = new Date().getFullYear()
    month.value = new Date().getMonth()

  }

  const today = new Date().toDateString();

  const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
  const days = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']

  const year = ref(props.year)
  const month = ref(props.month)

  const dateOffset = computed(() => new Date(year.value, month.value).getDay())
  const numberOfDays = computed(() => 35 - (new Date(year.value, month.value, 35).getDate()))

  const visibleDate = computed(() => {
    return props.modelValue.date ?
      `${props.modelValue.date} ${months[props.modelValue.month]} ${props.modelValue.year}` :
      `dd MM YYYY`
  })

  function toggleWhichPicker(which='date') {

    displayMonthPicker.value = displayDatePicker.value = displayYearPicker.value = false

    switch (which) {

      case 'month':
        displayMonthPicker.value = true
        break;
      case 'year':
        displayYearPicker.value = true
        break;
      default:
        displayDatePicker.value = true
        break;

    }

  }

  function toggleSelectedDate(date, month, year) {
      if (props.modelValue.date === date) {
          emit('update:modelValue', { date: '', month: '', year: '' })
      } else {
          emit('update:modelValue', { date, month, year })
      }
  }

  const displayDatePicker = ref(true)
  const displayYearPicker = ref(false)
  const displayMonthPicker = ref(false)

  function toggleSelectedYear(chosen_year) {

    year.value = chosen_year
    displayYearPicker.value = false
    displayMonthPicker.value = true

  }

  function toggleSelectedMonth(chosen_month) {

    month.value = chosen_month
    displayMonthPicker.value = false
    displayDatePicker.value = true

  }

  const showPicker = ref(false)
  function togglePicker() {
    showPicker.value = !showPicker.value
  }

  const datepicker = ref(null)
  onClickOutside(datepicker, (event) => {
    if (showPicker.value) {
      toggleWhichPicker()
      togglePicker()
    }
  })

  function next() {
    if (month.value >= 11) {
      month.value = 0
      year.value++
    } else {
      month.value++
    }
  }
  function previous() {
    if (month.value <= 0) {
      month.value = 11
      year.value--
    } else {
      month.value--
    }
  }

</script>

<style scoped>

</style>
