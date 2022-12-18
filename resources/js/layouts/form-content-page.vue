<template>

    <ContentPage>

        <div class="sm:flex sm:flex-col grid grid-cols-12 w-full gap-6">

            <div class="col-span-9 gap-8 flex flex-col">
                <router-view></router-view>
            </div>

            <div class="sm:relative sticky top-8 h-fit w-full rounded-lg col-span-3">

                <ul class="p-2 font-medium items-end text-subtitle flex flex-col gap-1">
                    <RouterLink v-slot="{ isActive }" active-class="ring-1 ring-brand-primary bg-brand-secondary text-brand-primary" to="/new/books" class="focus:outline-none flex items-center flex-row-reverse justify-between p-3 hover:bg-brand-secondary hover:text-brand-primary w-full text-right rounded-md group">
                        Register a new Book
                        <svg width="19" height="16" viewBox="0 0 19 16" :class="[isActive ? '!opacity-100' : 'group-hover:opacity-100']" class="opacity-0 fill-brand-primary" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.414 6.707H3.828L9.121 1.414L7.707 0L0 7.707L7.707 15.414L9.121 14L3.828 8.707H18.414V6.707Z" />
                        </svg>
                    </RouterLink>
                    <RouterLink v-slot="{ isActive }" active-class="ring-1 ring-brand-primary bg-brand-secondary text-brand-primary" to="/new/transactions" class="focus:outline-none flex items-center flex-row-reverse justify-between p-3 hover:bg-brand-secondary hover:text-brand-primary w-full text-right rounded-md group">
                        Make a new Transaction
                        <svg width="19" height="16" viewBox="0 0 19 16" :class="[isActive ? '!opacity-100' : 'group-hover:opacity-100']" class="opacity-0 fill-brand-primary" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.414 6.707H3.828L9.121 1.414L7.707 0L0 7.707L7.707 15.414L9.121 14L3.828 8.707H18.414V6.707Z" />
                        </svg>
                    </RouterLink>
                    <div :class="[isUpdateBook ? 'ring-1 ring-brand-primary bg-brand-secondary text-brand-primary' : '']" class="focus:outline-none flex items-center flex-row-reverse justify-between p-3 w-full text-right rounded-md group">
                        Update an existing Book
                        <svg width="19" height="16" viewBox="0 0 19 16" :class="[isUpdateBook ? '!opacity-100' : '']" class="opacity-0 fill-brand-primary" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.414 6.707H3.828L9.121 1.414L7.707 0L0 7.707L7.707 15.414L9.121 14L3.828 8.707H18.414V6.707Z" />
                        </svg>
                    </div>
                    <div :class="[isUpdateTransaction ? 'ring-1 ring-brand-primary bg-brand-secondary text-brand-primary' : '']" class="focus:outline-none flex items-center flex-row-reverse justify-between p-3 w-full text-right rounded-md group">
                        Update an existing Transaction
                        <svg width="19" height="16" viewBox="0 0 19 16" :class="[isUpdateTransaction ? '!opacity-100' : '']" class="opacity-0 fill-brand-primary" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.414 6.707H3.828L9.121 1.414L7.707 0L0 7.707L7.707 15.414L9.121 14L3.828 8.707H18.414V6.707Z" />
                        </svg>
                    </div>
                </ul>

            </div>

        </div>

    </ContentPage>

</template>

<script setup>

    import { computed } from "vue"
    import { useRoute } from "vue-router";
    import ContentPage from "./content-page.vue";

    const isUpdateBook = computed(() => useRoute().name === 'UpdateBook')
    const isUpdateTransaction = computed(() => useRoute().name === 'UpdateTransaction')

</script>

<style scoped>

</style>
