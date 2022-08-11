<template>

    <ContentPage>

        <div class="grid grid-cols-4 gap-2">
            <InfoCard class="col-span-1" label="Current Stock Balance">
                <span>{{ formatNumber(1661) }}</span>
            </InfoCard>
            <InfoCard class="col-span-1" label="Last Transaction Quantity">
                <span>2</span>
                <span class="mt-0.5 text-sm px-2 rounded-lg font-medium bg-red-100 text-red-900">sell</span>
            </InfoCard>
            <InfoCard class="col-span-1" label="Last Transaction on" :content="new Date().getDate() + '.' + (new Date().getMonth() + 1) + '.' + new Date().getFullYear()" />
            <InfoCard class="col-span-1" label="Last Transaction on" :content="new Date().getDate() + '.' + (new Date().getMonth() + 1) + '.' + new Date().getFullYear()" />
        </div>

        <div class="grid grid-cols-3 gap-2">
            <BarChart />
            <LineChart />
            <BarChart />
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
