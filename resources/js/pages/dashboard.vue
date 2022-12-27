<template>

    <ContentPage>

        <div class="grid grid-cols-3 gap-2 sm:grid-rows-3 sm:grid-cols-1">
            <BarChart :data="monthlySales" :loading="!! ! monthlySales" />
            <LineChart
                background-color='#FF810020'
                :data="[32, 40, 32, 44, 58, 42, 70, 70, 50, 43, 33, 29]"
                title="Monthly subscribers"
                subtitle="Mailing list registrations for each month of the year"
            />
            <BarChart :data="monthlyPurchases" :loading="!! ! monthlyPurchases" />
        </div>

        <ActionMenu />

    </ContentPage>

</template>

<script setup>

    import { onMounted, ref } from "vue";
    import ContentPage from "../layouts/content-page.vue";
    import InfoCard from "../components/InfoCard.vue";
    import BarChart from "../components/Graphs/BarChart.vue";
    import LineChart from "../components/Graphs/LineChart.vue";
    import { formatNumber } from "../utils";
    import ActionMenu from "../components/ActionMenu.vue";

    const monthlySales = ref(null)
    function getSales() {

        axios.get('/api/statistics/sales')
            .then(response => {

                monthlySales.value = response.data.data
                console.log(monthlySales.value)

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

    onMounted(() => {

        getSales()
        getPurchases()

    })

</script>

<style scoped>

</style>
