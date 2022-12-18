<template>

    <div id="page" class="group hover:shadow-md transition-all duration-300 print:hover:shadow-none print:border-none border border-border-light relative bg-white p-8 print:p-0 justify-between flex flex-col gap-4" size="A4">

        <div @mouseenter="toggleBlur(true)" @mouseleave="toggleBlur(false)" class="print:hidden opacity-0 group hover:opacity-100 absolute w-full h-full bg-white/50 top-0 left-0">
            <div class="p-4 flex w-full h-full transition-opacity duration-300 opacity-0 group-hover:opacity-100 backdrop-blur-xl">

                <button @click="print" class="sticky top-2 bg-brand-primary px-6 py-0.5 h-10 text-white rounded-lg">Print file</button>

            </div>
        </div>

        <!--Document header-->
        <header class="flex-col gap-4 flex">
            <div class="flex w-full justify-between">
                <img src="../../assets/images/ankeboot-logo.jpg" class="w-24" />
                <div class="flex flex-col items-end w-fit">
                    <h1 class="text-2xl font-bold uppercase">Ankeboot General Trading PLC</h1>
                    <div class="flex justify-between w-full">
                        <h2>TIN no. 0037776894</h2>
                        <h2>VAT registration no. 6077200010</h2>
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-end">
                <h2 class="font-bold">Ankeboot Bookstore</h2>
                <h2>+251 924 35 64 74</h2>
            </div>

            <div class="flex flex-col items-end">
                <h2>Woreda 09 No 1029</h2>
                <h2>Sub City ARADA</h2>
                <h2>Addis Ababa, ETH</h2>
            </div>
        </header>

        <section class="grow gap-4 flex flex-col">
            
            <!--Document record-->
            <div class="flex flex-col">
                <div class="flex gap-2">
                    <label>Date</label>
                    <p class="font-medium"> {{ date ?? new Date().toDateString() }} </p>
                </div>
                <div class="flex gap-2">
                    <label>Document No.</label>
                    <p class="font-medium"> {{ no === '' ? `ANK/ /${formatNumberToTwoIntegerPlaces(new Date().getMonth()+1)}${formatNumberToTwoIntegerPlaces(new Date().getDate())}` : no }} </p>
                </div>
            </div>

            <!--Document title-->
            <div class="mx-auto pt-4 flex gap-2">
                <p>ጉዳዩ</p>
                <div v-if="!! ! title" class="w-52 bg-gray-400/25 h-5" />
                <p v-else class="font-bold">{{ title }}</p>
            </div>

            <!--Document content-->
            <main>
                <slot />
            </main>

        </section>

        <!--Document footer-->
        <footer class="flex flex-col gap-4">

            <div class="flex justify-between">

                <div class="flex flex-col">
                    <h2>ankebootpublishing@gmail.com</h2>
                    <h2>office phone +251 11 157 59 10</h2>
                    <h2>mobile +251 924 35 64 74</h2>
                </div>

                <img src="../../assets/images/ankeboot-qr.svg" class="w-24" />

            </div>
            
            <div class="flex justify-between">

                <h2>ankeboot.com</h2>
                <h2>@ankebootbooks</h2>

            </div>


        </footer>

    </div>

</template>

<script setup>

    import { onMounted, ref } from "vue"
    import { formatNumberToTwoIntegerPlaces } from '../utils';

    const hideBlur = ref(false);
    function toggleBlur(hide) {
        hideBlur.value = hide;
    }

    const props = defineProps({
        title: {
            type: String,
            required: true
        },
        date: {
            type: [String, null],
            required: true
        },
        no: {
            type: String,
            required: true 
        }
    })

    function print() {

        toggleBlur(false)
        document.body.innerHTML = document.getElementById('page').outerHTML
        window.print()

        location.reload()

    }

    onMounted(() => {

        document.onkeydown = function (e) {
            if (e.ctrlKey && e.keyCode == 80) {
                print()
                return false;
            }
        }

    })

</script>

<style scoped>

    div[size="A4"] {  
        width: 21cm;
        height: 29.7cm; 
    }

    /* * {
        @apply border border-red-400;
    } */

</style>