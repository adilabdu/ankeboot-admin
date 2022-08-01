<template>

    <ContentPage>

<!--        <p class="text-subtitle font-medium">Dashboard</p>-->

        <div class="grid grid-cols-4 gap-2">
            <InfoCard class="col-span-1" label="Current Stock Balance">
                <span>{{ formatNumber(1661) }}</span>
<!--                <span class="mt-0.5 text-sm px-2 rounded-lg font-medium bg-red-100 text-red-900">sell</span>-->
            </InfoCard>
            <InfoCard class="col-span-1" label="Last Transaction Quantity">
                <span>2</span>
                <span class="mt-0.5 text-sm px-2 rounded-lg font-medium bg-red-100 text-red-900">sell</span>
            </InfoCard>
            <InfoCard class="col-span-1" label="Last Transaction on" :content="new Date().getDate() + '.' + (new Date().getMonth() + 1) + '.' + new Date().getFullYear()" />
            <InfoCard class="col-span-1" label="Last Transaction on" :content="new Date().getDate() + '.' + (new Date().getMonth() + 1) + '.' + new Date().getFullYear()" />
        </div>

        <div class="grid grid-cols-3 gap-2">
            <BarChart class="w-col-span-1" />
            <BarChart class="w-col-span-1" />
            <BarChart class="w-col-span-1" />
        </div>

        <div class="overflow-hidden rounded-2xl w-full h-96 bg-white border border-border-light shadow-md grid grid-cols-3 grid-rows-2">

            <div class="p-8 col-span-1 row-span-1 border-r border-b border-border-light flex flex-col justify-start gap-4 hover:bg-brand-secondary group">
                <h1 class="group-hover:text-brand-primary font-medium text-base">Register new Book</h1>
                <p class="text-subtitle group-hover:text-black">
                    You can register a new book into the system when a new stock arrives. Registering a new book requires
                    book title, unique item code (can be auto-generated), type of purchase (cash / consignment), and
                    book category.
                </p>
            </div>
            <div class="p-8 col-span-1 row-span-1 border-b border-r border-border-light flex flex-col justify-start gap-4 hover:bg-brand-secondary group">
                <h1 class="group-hover:text-brand-primary font-medium text-base">Insert Book records from Data Source</h1>
                <p class="text-subtitle group-hover:text-black">
                    If you have a bulk of item records that need registering into the system, you can insert these
                    records into the system all at once by uploading a worksheet generated CSV file.
                </p>
            </div>
            <div class="p-8 col-span-1 row-span-1 border-b border-border-light flex flex-col justify-start gap-4 hover:bg-brand-secondary group">
                <h1 class="group-hover:text-brand-primary font-medium text-base">Keep up with your Daily Sales</h1>
                <p class="group-hover:text-black text-subtitle">
                    Record your daily sales onto the system and receive weekly and monthly overview. Keep up to date with
                    notifications for missing daily sales transaction.
                </p>
            </div>
            <div class="p-8 col-span-1 row-span-1 border-r border-border-light flex flex-col justify-start gap-4 hover:bg-brand-secondary group">
                <h1 class="group-hover:text-brand-primary font-medium text-base">View Stock Records</h1>
                <p class="group-hover:text-black text-subtitle">
                    View the stock records for any of the items in your inventory. Stock record includes a
                    detail transaction history of an item including the transaction date and an aggregate
                    balance of the item currently in stock.
                </p>
            </div>
            <div class="col-span-1 row-span-1 border-r border-border-light"></div>

        </div>

    </ContentPage>

</template>

<script setup>

    import ContentPage from "../layouts/content-page.vue";
    import InfoCard from "../components/InfoCard.vue";
    import BarChart from "../components/Graphs/BarChart.vue";
    import { formatNumber } from "../utils";

</script>

<style scoped>

</style>
