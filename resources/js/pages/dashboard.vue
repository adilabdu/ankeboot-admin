<template>

    <ContentPage>

        <div class="grid grid-cols-4 gap-2 sm:grid-rows-4 sm:grid-cols-1">
            <InfoCard class="col-span-1 sm:row-span-1" label="Current Stock Balance">
                <span>{{ formatNumber(1661) }}</span>
            </InfoCard>
            <InfoCard class="col-span-1 sm:row-span-1" label="Last Transaction Quantity">
                <span>2</span>
                <span class="mt-0.5 text-sm px-2 rounded-lg font-medium bg-red-100 text-red-900">sell</span>
            </InfoCard>
            <InfoCard class="col-span-1 sm:row-span-1" label="Last Transaction on" :content="new Date().getDate() + '.' + (new Date().getMonth() + 1) + '.' + new Date().getFullYear()" />
            <InfoCard class="col-span-1 sm:row-span-1" label="Last Transaction on" :content="new Date().getDate() + '.' + (new Date().getMonth() + 1) + '.' + new Date().getFullYear()" />
        </div>

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
