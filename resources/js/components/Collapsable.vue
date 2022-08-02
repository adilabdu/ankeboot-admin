<template>

    <template v-if="false">
        <div class="border-b border-border-light w-full"></div>

        <div class="flex flex-col gap-1">
            <label class="text-subtitle font-medium">Book title</label>
            <input type="text" name="title" placeholder="Book title" class="focus:outline-brand-primary focus:outline focus:outline-2 focus:outline-offset-2 h-10 w-full p-2 border border-border-light rounded-md" />
        </div>

        <div class="flex w-full gap-8">
            <div class="flex flex-col gap-1 w-full">
                <label class="text-subtitle font-medium">Author</label>
                <input type="text" name="author" placeholder="Book author" class="focus:outline-brand-primary focus:outline focus:outline-2 focus:outline-offset-2 h-10 p-2 border border-border-light rounded-md" />
            </div>
            <div class="flex flex-col gap-1 w-full">
                <label class="text-subtitle font-medium">Category</label>
                <div class="relative">
                    <input type="text" name="author" placeholder="Book Category" class="w-full focus:outline-brand-primary focus:outline focus:outline-2 focus:outline-offset-2 h-10 p-2 border border-border-light rounded-md" />
                    <ul class="hidden absolute w-full bg-white rounded-md border border-border-light shadow-md mt-2">
                        <li class="h-10"></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-1">
            <label class="text-subtitle font-medium">Code</label>
            <input type="text" name="code" placeholder="Item code" class="focus:outline-brand-primary focus:outline focus:outline-2 focus:outline-offset-2 h-10 w-2/5 p-2 border border-border-light rounded-md" />
        </div>

        <div class="flex items-center w-fit gap-4">
            <label class="text-subtitle font-medium">Consignment</label>
            <div tabindex="0" @keydown.enter="toggleRadio" @click="toggleRadio" :class="[radioState ? 'bg-brand-primary border-brand-primary' : 'bg-wallpaper border-border-dark']" class="transition-colors duration-500 focus:outline-brand-primary focus:outline focus:outline-2 focus:outline-offset-2 cursor-pointer h-6 w-12 flex rounded-full relative border items-center">
                <div :class="[radioState ? 'translate-x-full border-white' : '-translate-x-0.5 border-border-dark']" class="bg-white m-1 transition-all duration-500 w-5 h-5 border rounded-full"></div>
            </div>
        </div>
    </template>
    <button type="button" class="text-subtitle w-fit rounded-full px-2 font-medium text-xs justify-start flex items-center gap-1">
        <svg width="10" height="7.5" viewBox="0 0 15 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0.536 19.886C0.697765 19.9702 0.879391 20.0087 1.06138 19.9974C1.24337 19.9861 1.41886 19.9255 1.569 19.822L14.569 10.822C14.7018 10.73 14.8104 10.6071 14.8854 10.464C14.9603 10.3208 14.9995 10.1616 14.9995 10C14.9995 9.83843 14.9603 9.67923 14.8854 9.53607C14.8104 9.39291 14.7018 9.27006 14.569 9.17803L1.569 0.178029C1.41897 0.0742216 1.24341 0.013434 1.06131 0.00224798C0.879219 -0.00893801 0.697533 0.029904 0.53593 0.114568C0.374326 0.199232 0.238963 0.326492 0.144497 0.482569C0.0500308 0.638645 6.15833e-05 0.817591 1.35831e-08 1.00003V19C-3.00674e-05 19.1826 0.0499031 19.3616 0.144387 19.5178C0.238871 19.674 0.374302 19.8013 0.536 19.886Z" fill="#6A727F"/>
        </svg>
        <span>Register first purchase</span>
    </button>

</template>

<script setup>

</script>

<style scoped>

</style>
