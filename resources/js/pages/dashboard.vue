<template>

    <ContentPage>

        <div class="grid grid-cols-3 gap-2 sm:grid-rows-3 sm:grid-cols-1">
            <BarChart
                :data="monthlyPurchases"
                :loading="!! ! monthlyPurchases"
                title="Monthly Purchases"
                subtitle="Purchases made within the past 12 months"
            />
            <DonutChart
                :loading="!! ! yearlyConsignmentSales"
                :ratio="yearlyConsignmentSales"
                subtitle="Ratio of consignment to cash sales within the last year"
                title="Rate of Consignment Sales"
                :legends="{
                    first: `Percentage of cash sales made within the past year (${getLastYear()} - ${new Date().toLocaleDateString()})`,
                    second: `Percentage of consignment sales made within the past year (${getLastYear()} - ${new Date().toLocaleDateString()})`
                }"
            />
            <LineChart :data="monthlySales" :loading="!! ! monthlySales" />
            <PieChart
                :loading="!! ! yearlyConsignmentSales"
                :ratio="yearlyConsignmentSales"
                subtitle="Ratio of consignment to cash sales within the last year"
                title="Rate of Consignment Sales"
                :legends="{
                    first: `Percentage of cash sales made within the past year (${getLastYear()} - ${new Date().toLocaleDateString()})`,
                    second: `Percentage of consignment sales made within the past year (${getLastYear()} - ${new Date().toLocaleDateString()})`
                }"
            />
        </div>

        <ActionMenu />

    </ContentPage>

</template>

<script setup>

    import { onMounted, ref } from "vue";
    import ContentPage from "../layouts/content-page.vue";
    import InfoCard from "../components/InfoCard.vue";
    import BarChart from "../components/Graphs/BarChart.vue";
    import DonutChart from "../components/Graphs/DonutChart.vue";
    import PieChart from "../components/Graphs/PieChart.vue";
    import LineChart from "../components/Graphs/LineChart.vue";
    import { formatNumber } from "../utils";
    import ActionMenu from "../components/ActionMenu.vue";

    const monthlySales = ref(null)
    function getSales() {

        axios.get('/api/statistics/sales')
            .then(response => {

                monthlySales.value = response.data.data

            })
            .catch(error => {

                alert(`Error while fetching monthly sales: ${error}`)

            })

    }

    const monthlyPurchases = ref(null)
    function getPurchases() {

        axios.get('/api/statistics/purchases')
            .then(response => {

                monthlyPurchases.value = response.data.data

            })
            .catch(error => {

                alert(`Error while fetching monthly purchases: ${error}`)

            })

    }

    const yearlyConsignmentSales = ref(0)
    function getYearlyConsignmentSales() {

        axios.get('/api/statistics/sales/consignments')
            .then(response => {

                yearlyConsignmentSales.value = response.data.data

            })
            .catch(error => {

                alert(`Error while fetching yearly consignment sales: ${error}`)

            })

    }

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

</style>
