<template>

    <ContentPage>

        <div class="grid grid-cols-12 w-full gap-6">

            <div class="col-span-9 gap-8 flex flex-col">
                <router-view />
            </div>

            <div class="sticky top-8 h-fit w-full rounded-lg bg-white/50 col-span-3 border border-border-light">

                <ul class="p-2 font-medium items-end text-subtitle flex flex-col gap-1">
                    <RouterLink to="/new/books" class="flex items-center flex-row-reverse justify-between p-3 hover:bg-brand-secondary hover:text-brand-primary w-full text-right rounded-md group">
                        Register new Book
                        <svg width="19" height="16" viewBox="0 0 19 16" class="group-hover:opacity-100 opacity-0 group-hover:fill-brand-primary" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.414 6.707H3.828L9.121 1.414L7.707 0L0 7.707L7.707 15.414L9.121 14L3.828 8.707H18.414V6.707Z" />
                        </svg>
                    </RouterLink>
                    <RouterLink
                        to="/new"
                        :class="['border border-brand-primary bg-brand-secondary text-brand-primary']"
                        class="flex items-center flex-row-reverse justify-between p-3 hover:bg-brand-secondary hover:text-brand-primary w-full text-right rounded-md group">
                        Update an existing Book
                        <svg width="19" height="16" viewBox="0 0 19 16" :class="'fill-brand-primary'" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.414 6.707H3.828L9.121 1.414L7.707 0L0 7.707L7.707 15.414L9.121 14L3.828 8.707H18.414V6.707Z" />
                        </svg>
                    </RouterLink>
                    <RouterLink to="" class="p-3 hover:bg-brand-secondary hover:text-brand-primary w-full text-right rounded-md">Make a new Transaction</RouterLink>
                    <li class="p-3 hover:bg-brand-secondary hover:text-brand-primary w-full text-right rounded-md">Update an existing Transaction</li>
                </ul>

            </div>

        </div>

    </ContentPage>

</template>

<script setup>

import ContentPage from "../../layouts/content-page.vue";

</script>

<style scoped>

</style>
