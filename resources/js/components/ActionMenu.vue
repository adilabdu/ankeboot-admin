<template>

    <div class="overflow-hidden rounded-2xl w-full h-96 bg-white border border-border-light shadow-md grid grid-cols-3 grid-rows-2">

        <RouterLink to="/new" class="cursor-pointer p-8 col-span-1 row-span-1 border-r border-b border-border-light flex flex-col justify-start gap-4 hover:bg-wallpaper/75 group">
            <h1 class="group-hover:text-brand-primary font-medium text-base">Register new Book</h1>
            <p class="text-subtitle group-hover:text-black">
                Register a new item when stock arrives. A new book entry requires
                book title, unique item code (can be auto-generated), type of purchase (cash / consignment), and
                book category.
            </p>
        </RouterLink>
        <RouterLink to="/" class="cursor-pointer p-8 col-span-1 row-span-1 border-b border-r border-border-light flex flex-col justify-start gap-4 hover:bg-wallpaper/75 group">
            <h1 class="group-hover:text-brand-primary font-medium text-base">Insert Book records from Data Source</h1>
            <p class="text-subtitle group-hover:text-black">
                If you have a bulk of item records that need registration, insert these
                records all at once by uploading a worksheet generated CSV file. Make sure all the required
                columns are present.
            </p>
        </RouterLink>
        <RouterLink to="" class="cursor-pointer p-8 col-span-1 row-span-1 border-b border-border-light flex flex-col justify-start gap-4 hover:bg-wallpaper/75 group">
            <h1 class="group-hover:text-brand-primary font-medium text-base">Keep up with your Daily Sales</h1>
            <p class="group-hover:text-black text-subtitle">
                Record your daily sales and receive weekly and monthly overview. Keep up to date with daily sales
                with reminder notifications for missing transactions.
            </p>
        </RouterLink>
        <RouterLink to="" class="cursor-pointer p-8 col-span-1 row-span-1 border-r border-border-light flex flex-col justify-start gap-4 hover:hover:bg-wallpaper/75 group">
            <h1 class="group-hover:text-brand-primary font-medium text-base">View Stock Records</h1>
            <p class="group-hover:text-black text-subtitle">
                View the stock records for any of the items in your inventory. Stock record includes a
                detail transaction history of an item including the transaction date and an aggregate
                balance of the item currently in stock.
            </p>
        </RouterLink>
        <RouterLink to="" class="cursor-pointer p-8 col-span-1 row-span-1 border-r border-border-light flex flex-col justify-start gap-4 hover:hover:bg-wallpaper/75 group">
            <h1 class="group-hover:text-brand-primary font-medium text-base">Document Tracking</h1>
            <p class="group-hover:text-black text-subtitle">
                Manage all company signed letters and documents. Create new template for future use,
                view all previously generated documents ready for export or print.
            </p>
        </RouterLink>
        <RouterLink to="" class="cursor-pointer p-8 col-span-1 row-span-1 flex flex-col justify-start gap-4 hover:hover:bg-wallpaper/75 group">
            <h1 class="group-hover:text-brand-primary font-medium text-base">Suppliers List</h1>
            <p class="group-hover:text-black text-subtitle">
                Register suppliers' information and their supply list. Assign a stock item to a supplier or
                create a new supplier when a new item arrives. Supplier's information includes tin number, contact
                info, address.
            </p>
        </RouterLink>

    </div>

</template>

<script setup>

</script>

<style scoped>

    * {
        @apply focus:outline-none;
    }

</style>
