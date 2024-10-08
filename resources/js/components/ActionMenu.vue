<template>

    <div class="overflow-hidden rounded-2xl w-full bg-white border border-border-light shadow-md grid grid-cols-3 grid-rows-2 md:grid-cols-2 md:grid-rows-3 sm:grid-cols-1 sm:grid-rows-6">

        <RouterLink to="new/books" class="overflow-ellipsis cursor-pointer p-8 col-span-1 row-span-1 border-r border-b sm:border-r-0 border-border-light flex flex-col justify-between hover:bg-wallpaper/75 group">

            <div class="grid place-items-center w-12 h-12 bg-green-100 rounded-lg mb-3 group-hover:bg-brand-secondary transition-colors duration-300 ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="group-hover:stroke-brand-primary stroke-green-400 stroke-2 w-5 h-5 transition-colors duration-300 ">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>

            <div class="flex flex-col gap-4">
                <h1 class="group-hover:text-brand-primary font-medium text-base transition-colors duration-300">Register new Book</h1>
                <p class="text-subtitle group-hover:text-black overflow-hidden transition-colors duration-300">
                    Register a new item when stock arrives. A new book entry requires
                    book title, unique item code (can be auto-generated), type of purchase (cash / consignment), and
                    book category.
                </p>
            </div>
        </RouterLink>

        <RouterLink to="new/books" class="cursor-pointer p-8 col-span-1 row-span-1 border-b border-r md:border-r-0 border-border-light flex flex-col justify-between hover:bg-wallpaper/75 group">

            <div class="grid place-items-center w-12 h-12 bg-red-100 rounded-lg mb-3 group-hover:bg-brand-secondary transition-colors duration-300 ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="group-hover:stroke-brand-primary stroke-red-400 stroke-2 w-5 h-5 transition-colors duration-300">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                </svg>
            </div>

            <div class="flex flex-col gap-4">
                <h1 class="group-hover:text-brand-primary font-medium text-base transition-colors duration-300">Insert Book records from Data Source</h1>
                <p class="text-subtitle group-hover:text-black overflow-hidden transition-colors duration-300">
                    If you have a bulk of item records that need registration, insert these
                    records all at once by uploading a worksheet generated CSV file. Make sure all the required
                    columns are present.
                </p>
            </div>
        </RouterLink>

        <RouterLink to="/daily-sales" class="cursor-pointer p-8 col-span-1 row-span-1 md:border-r sm:border-r-0 border-b border-border-light flex flex-col justify-between gap-4 hover:bg-wallpaper/75 group">

            <div class="grid place-items-center w-12 h-12 bg-sky-100 rounded-lg mb-3 group-hover:bg-brand-secondary transition-colors duration-300 ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="group-hover:stroke-brand-primary stroke-sky-500 stroke-2 w-5 h-5 transition-colors duration-300">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                </svg>
            </div>

            <div class="flex flex-col gap-4">
                <h1 class="group-hover:text-brand-primary font-medium text-base transition-colors duration-300">Keep up with your Daily Sales</h1>
                <p class="group-hover:text-black text-subtitle overflow-hidden transition-colors duration-300">
                    Record your daily sales and receive weekly and monthly overview. Keep up to date with daily sales
                    with reminder notifications for missing transactions.
                </p>
            </div>
        </RouterLink>

        <RouterLink to="/books" class="cursor-pointer p-8 col-span-1 row-span-1 border-r md:border-b md:border-r-0 border-border-light flex flex-col justify-between gap-4 hover:hover:bg-wallpaper/75 group">

            <div class="grid place-items-center w-12 h-12 bg-fuchsia-50 rounded-lg group-hover:bg-brand-secondary transition-colors duration-300 ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="group-hover:stroke-brand-primary stroke-fuchsia-400 stroke-2 w-5 h-5 transition-colors duration-300">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                </svg>
            </div>

            <div class="flex flex-col gap-4">
                <h1 class="group-hover:text-brand-primary font-medium text-base transition-colors duration-300">View Stock Records</h1>
                <p class="group-hover:text-black text-subtitle overflow-hidden transition-colors duration-300">
                    View the stock records for any items in your inventory. Stock record includes a
                    detail transaction history of an item including the transaction date and its aggregate
                    balance currently in stock.
                </p>
            </div>
        </RouterLink>

        <RouterLink to="/documents/file/new" class="cursor-pointer p-8 col-span-1 row-span-1 border-r sm:border-b sm:border-r-0 border-border-light flex flex-col justify-between gap-4 hover:hover:bg-wallpaper/75 group">

            <div class="grid place-items-center w-12 h-12 bg-gray-100 rounded-lg mb-3 group-hover:bg-brand-secondary transition-colors duration-300 ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="group-hover:stroke-brand-primary stroke-gray-700 stroke-2 w-5 h-5 transition-colors duration-300">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                </svg>
            </div>

            <div class="flex flex-col gap-4">
                <h1 class="group-hover:text-brand-primary font-medium text-base transition-colors duration-300">Document Tracking</h1>
                <p class="group-hover:text-black text-subtitle overflow-hidden transition-colors duration-300">
                    Manage all company signed letters and documents. Create new template for future use,
                    view all previously generated documents ready for export or print.
                </p>
            </div>
        </RouterLink>

        <RouterLink to="/suppliers" class="cursor-pointer p-8 col-span-1 row-span-1 flex flex-col justify-between gap-4 hover:hover:bg-wallpaper/75 group">

            <div class="grid place-items-center w-12 h-12 bg-amber-100 rounded-lg mb-3 group-hover:bg-brand-secondary transition-colors duration-300 ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="group-hover:stroke-brand-primary stroke-amber-700 stroke-2 w-5 h-5 transition-colors duration-300">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12.75 3.03v.568c0 .334.148.65.405.864l1.068.89c.442.369.535 1.01.216 1.49l-.51.766a2.25 2.25 0 01-1.161.886l-.143.048a1.107 1.107 0 00-.57 1.664c.369.555.169 1.307-.427 1.605L9 13.125l.423 1.059a.956.956 0 01-1.652.928l-.679-.906a1.125 1.125 0 00-1.906.172L4.5 15.75l-.612.153M12.75 3.031a9 9 0 00-8.862 12.872M12.75 3.031a9 9 0 016.69 14.036m0 0l-.177-.529A2.25 2.25 0 0017.128 15H16.5l-.324-.324a1.453 1.453 0 00-2.328.377l-.036.073a1.586 1.586 0 01-.982.816l-.99.282c-.55.157-.894.702-.8 1.267l.073.438c.08.474.49.821.97.821.846 0 1.598.542 1.865 1.345l.215.643m5.276-3.67a9.012 9.012 0 01-5.276 3.67m0 0a9 9 0 01-10.275-4.835M15.75 9c0 .896-.393 1.7-1.016 2.25" />
                </svg>
            </div>

            <div class="flex flex-col gap-4">
                <h1 class="group-hover:text-brand-primary font-medium text-base transition-colors duration-300">Suppliers List</h1>
                <p class="group-hover:text-black text-subtitle overflow-hidden transition-colors duration-300">
                    Register suppliers' information and their supply list. Assign a stock item to a supplier or
                    create a new supplier when a new item arrives. Supplier's information includes tin number, contact
                    info, address.
                </p>
            </div>
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
