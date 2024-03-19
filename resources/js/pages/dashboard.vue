<template>
  <ContentPage>
    <div
      class="flex overflow-auto lg:snap-x lg:snap-mandatory scrollbar-brand gap-2 sm:grid-rows-3 sm:grid-cols-1"
    >
      <BarChart
        :data="monthlyPurchases"
        :loading="chartsLoading"
        title="Monthly Purchases"
        subtitle="Purchases made within the past 12 months"
      />
      <DonutChart
        :loading="chartsLoading"
        :ratio="yearlyConsignmentSales"
        subtitle="Ratio of consignment to cash sales within the last year"
        title="Rate of Consignment Sales"
        :legends="{
          first: `Percentage of cash sales made within the past year (${getLastYear()} - ${new Date().toLocaleDateString()})`,
          second: `Percentage of consignment sales made within the past year (${getLastYear()} - ${new Date().toLocaleDateString()})`
        }"
      />
      <!--            <PieChart-->
      <!--                :loading="!! ! yearlyConsignmentSales"-->
      <!--                :ratio="yearlyConsignmentSales"-->
      <!--                subtitle="Ratio of consignment to cash sales within the last year"-->
      <!--                title="Rate of Consignment Sales"-->
      <!--                :legends="{-->
      <!--                    first: `Percentage of cash sales made within the past year (${getLastYear()} - ${new Date().toLocaleDateString()})`,-->
      <!--                    second: `Percentage of consignment sales made within the past year (${getLastYear()} - ${new Date().toLocaleDateString()})`-->
      <!--                }"-->
      <!--            />-->
      <LineChart :data="monthlySales" :loading="monthlySalesLoading" />
    </div>

    <div class="min-h-fit">
      <ActionMenu />
    </div>
  </ContentPage>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import ContentPage from '../layouts/content-page.vue'
import BarChart from '../components/Graphs/BarChart.vue'
import DonutChart from '../components/Graphs/DonutChart.vue'
import LineChart from '../components/Graphs/LineChart.vue'
import ActionMenu from '../components/ActionMenu.vue'

const monthlySales = ref(null)
function getSales() {
  axios
    .get('/api/statistics/sales')
    .then((response) => {
      monthlySales.value = response.data.data
    })
    .catch((error) => {
      alert(`Error while fetching monthly sales: ${error}`)
    })
}

const monthlyPurchases = ref(null)
function getPurchases() {
  axios
    .get('/api/statistics/purchases')
    .then((response) => {
      monthlyPurchases.value = response.data.data
    })
    .catch((error) => {
      alert(`Error while fetching monthly purchases: ${error}`)
    })
}

const yearlyConsignmentSales = ref(0)
function getYearlyConsignmentSales() {
  axios
    .get('/api/statistics/sales/consignments')
    .then((response) => {
      yearlyConsignmentSales.value = response.data.data
    })
    .catch((error) => {
      alert(`Error while fetching yearly consignment sales: ${error}`)
    })
}

const monthlySalesLoading = computed(() => !!!monthlySales.value)

const chartsLoading = computed(() => {
  return !!!monthlyPurchases.value && !!!monthlySales.value && !!!yearlyConsignmentSales.value
})

function getLastYear() {
  const date = new Date()
  date.setFullYear(date.getFullYear() - 1)

  return date.toLocaleDateString()
}

onMounted(() => {
  getSales()
  getPurchases()
  getYearlyConsignmentSales()
})
</script>

<style scoped>
@media (min-width: 1456px) {
  .chart-child {
    @apply w-full;
  }
}

@media (max-width: 1200px), (max-width: 1455px) and (min-width: 1280px) {
  .chart-child {
    @apply min-w-[calc(50%-8px)] w-1/2;
  }
}

@media (max-width: 770px) {
  .chart-child {
    @apply min-w-full;
  }
}
</style>
