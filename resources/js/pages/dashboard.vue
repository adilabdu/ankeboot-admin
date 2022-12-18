<template>

    <ContentPage>

        <div class="grid grid-cols-3 gap-2 sm:grid-rows-3 sm:grid-cols-1">
            <BarChart />
            <LineChart
                background-color='#FF810020'
                :data="[32, 40, 32, 44, 58, 42, 70, 70, 50, 43, 33, 29]"
                title="Monthly subscribers"
                subtitle="Mailing list registrations for each month of the year"
            />
            <BarChart
                title="Monthly Purchases"
                subtitle="Purchases made within the last 12 months"
                :data="[['Jan', 6909, 32], ['Feb', 5900, 30], ['Mar', 7110, 29], ['Apr', 8322, 35], ['May', 7221, 42], ['Jun', 8122, 58], ['Jul', 7210, 55], ['Aug', 9101, 74], ['Sep', 10111, 59], ['Oct', 12307, 45], ['Nov', 11721, 42], ['Dec', 9122, 39]]"
            />
        </div>

        <ActionMenu />

    </ContentPage>

</template>

<script setup>

    import ContentPage from "../layouts/content-page.vue";
    import InfoCard from "../components/InfoCard.vue";
    import BarChart from "../components/Graphs/BarChart.vue";
    import LineChart from "../components/Graphs/LineChart.vue";
    import { formatNumber } from "../utils";
    import ActionMenu from "../components/ActionMenu.vue";

</script>

<style scoped>

</style>
