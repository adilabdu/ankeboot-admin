<template>

    <div class="flex flex-row max-w-full relative overflow-x-hidden">

        <Navigation v-if="authenticated">

            <div class="flex flex-col justify-between h-full">

                <div class="flex flex-col">
                    <NavigationLink v-slot="props" label="dashboard" route="">
                        <PresentationChartLineIcon :class="[props.active ? 'stroke-white' : 'stroke-black', 'stroke-[1.5] scale-90']" />
                    </NavigationLink>
                    <NavigationLink v-slot="props" label="books">
                        <BookOpenIcon class="scale-90 stroke-[1.5]" :class="[props.active ? 'stroke-white' : 'stroke-black']" />
                    </NavigationLink>
                    <NavigationLink v-slot="props" label="transactions">
                        <ArrowPathRoundedSquareIcon class="scale-90 stroke-[1.5]" :class="[props.active ? 'stroke-white' : 'stroke-black']" />
                    </NavigationLink>
                    <NavigationLink v-slot="props" label="stores">
                        <BuildingStorefrontIcon class="stroke-[1.5] scale-90" :class="[props.active ? 'stroke-white' : 'stroke-black']" />
                    </NavigationLink>
                    <NavigationLink :number="store.state.daily_sale.unsubmitted" v-slot="props" label="daily sales" route="daily-sales">
                        <CalendarDaysIcon class="scale-90" :class="[props.active ? 'stroke-white stroke-2' : 'stroke-black stroke-[1.5]']" />
                    </NavigationLink>
                    <NavigationLink v-slot="props" label="consignments" route="consignments">
                        <svg class="w-6 h-6 stroke-[1.5] fill-transparent scale-95 origin-left" :class="[props.active ? 'stroke-white' : 'stroke-black']" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path class="fill-none" stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.41-.513m14.095-5.13l1.41-.513M5.106 17.785l1.15-.964m11.49-9.642l1.149-.964M7.501 19.795l.75-1.3m7.5-12.99l.75-1.3m-6.063 16.658l.26-1.477m2.605-14.772l.26-1.477m0 17.726l-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205L12 12m6.894 5.785l-1.149-.964M6.256 7.178l-1.15-.964m15.352 8.864l-1.41-.513M4.954 9.435l-1.41-.514M12.002 12l-3.75 6.495" />
                        </svg>
                    </NavigationLink>
                    <NavigationLink v-slot="props" label="credit sales" route="credit-sales">
                        <svg class="scale-90 fill-transparent" width="20" height="16" viewBox="0 0 20 16" xmlns="http://www.w3.org/2000/svg">
                            <path :class="[props.active ? 'stroke-white' : 'stroke-black']" class="stroke-2" d="M15 5V3C15 2.46957 14.7893 1.96086 14.4142 1.58579C14.0391 1.21071 13.5304 1 13 1H3C2.46957 1 1.96086 1.21071 1.58579 1.58579C1.21071 1.96086 1 2.46957 1 3V9C1 9.53043 1.21071 10.0391 1.58579 10.4142C1.96086 10.7893 2.46957 11 3 11H5M7 15H17C17.5304 15 18.0391 14.7893 18.4142 14.4142C18.7893 14.0391 19 13.5304 19 13V7C19 6.46957 18.7893 5.96086 18.4142 5.58579C18.0391 5.21071 17.5304 5 17 5H7C6.46957 5 5.96086 5.21071 5.58579 5.58579C5.21071 5.96086 5 6.46957 5 7V13C5 13.5304 5.21071 14.0391 5.58579 14.4142C5.96086 14.7893 6.46957 15 7 15ZM14 10C14 10.5304 13.7893 11.0391 13.4142 11.4142C13.0391 11.7893 12.5304 12 12 12C11.4696 12 10.9609 11.7893 10.5858 11.4142C10.2107 11.0391 10 10.5304 10 10C10 9.46957 10.2107 8.96086 10.5858 8.58579C10.9609 8.21071 11.4696 8 12 8C12.5304 8 13.0391 8.21071 13.4142 8.58579C13.7893 8.96086 14 9.46957 14 10V10Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </NavigationLink>
                    <NavigationLink v-slot="props" label="suppliers">
                        <svg class="scale-90" width="19" height="20" viewBox="0 0 19 20" :class="[props.active ? 'fill-white' : 'fill-black']" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.5 1C15.8371 1.00053 15.2015 1.26409 14.7328 1.73282C14.2641 2.20155 14.0005 2.83712 14 3.5C14 3.857 14.078 4.196 14.214 4.505L12.259 6.704C11.5949 6.24558 10.807 6.00004 10 6C9.26 6 8.576 6.216 7.981 6.566L5.707 4.293L5.684 4.316C5.88 3.918 6 3.475 6 3C6 2.40666 5.82405 1.82664 5.49441 1.33329C5.16477 0.839943 4.69623 0.455426 4.14805 0.228363C3.59987 0.00129986 2.99667 -0.0581102 2.41473 0.0576455C1.83279 0.173401 1.29824 0.459124 0.878681 0.878681C0.459124 1.29824 0.173401 1.83279 0.0576455 2.41473C-0.0581102 2.99667 0.00129986 3.59987 0.228363 4.14805C0.455426 4.69623 0.839943 5.16477 1.33329 5.49441C1.82664 5.82405 2.40666 6 3 6C3.475 6 3.917 5.88 4.316 5.684L4.293 5.707L6.567 7.98C6.19964 8.59003 6.00375 9.28791 6 10C6 10.997 6.38 11.899 6.985 12.601L4.408 15.177C4.11917 15.0614 3.81111 15.0013 3.5 15C2.122 15 1 16.121 1 17.5C1 18.879 2.122 20 3.5 20C4.878 20 6 18.879 6 17.5C6 17.179 5.934 16.874 5.823 16.591L8.661 13.753C9.082 13.903 9.528 14 10 14C12.206 14 14 12.206 14 10C14 9.364 13.837 8.771 13.572 8.236L15.689 5.853C15.945 5.941 16.215 6 16.5 6C17.879 6 19 4.879 19 3.5C19 2.121 17.879 1 16.5 1ZM10 12C8.897 12 8 11.103 8 10C8 8.897 8.897 8 10 8C11.103 8 12 8.897 12 10C12 11.103 11.103 12 10 12Z" />
                        </svg>
                    </NavigationLink>
                    <NavigationLink v-slot="props" label="documents" route="documents">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" :class="[props.active ? 'stroke-white' : 'stroke-black']" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                        </svg>
                    </NavigationLink>
                    <NavigationLink v-slot="props" label="mailing list" route="mailing-list">
                        <svg class="scale-90" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14.5 10C14.5 11.657 15.507 13 16.75 13C17.993 13 19 11.657 19 10C19 7.9178 18.278 5.90003 16.957 4.29048C15.6361 2.68093 13.7979 1.57919 11.7557 1.17299C9.71355 0.766794 7.5937 1.08126 5.75737 2.06282C3.92104 3.04437 2.48187 4.63228 1.68506 6.55599C0.888253 8.47969 0.783114 10.6202 1.38756 12.6127C1.992 14.6052 3.26863 16.3265 4.99992 17.4833C6.73121 18.6401 8.81004 19.1608 10.8822 18.9567C12.9544 18.7526 14.8917 17.8363 16.364 16.364M14.5 10V6.25M14.5 10C14.5 11.1935 14.0259 12.3381 13.182 13.182C12.3381 14.0259 11.1935 14.5 10 14.5C8.80653 14.5 7.66194 14.0259 6.81802 13.182C5.97411 12.3381 5.5 11.1935 5.5 10C5.5 8.80652 5.97411 7.66193 6.81802 6.81802C7.66194 5.9741 8.80653 5.5 10 5.5C11.1935 5.5 12.3381 5.9741 13.182 6.81802C14.0259 7.66193 14.5 8.80652 14.5 10V10Z" :class="[props.active ? 'stroke-white' : 'stroke-black']" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </NavigationLink>
                    <NavigationLink v-slot="props" label="resources" route="resources">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" :class="[props.active ? 'stroke-white' : 'stroke-black']" class="w-6 h-6 scale-90 origin-left">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                    </NavigationLink>
                </div>

                <div class="flex flex-col items-center pt-8 gap-2">
                    <SwitchInput :loading="loading" label="Remote" v-model="remote" />
                    <h1 class="text-xs text-subtitle text-center">&copy; {{ footerWords()[getRandomInt(footerWords().length)] }}. 2022</h1>
                </div>

            </div>

        </Navigation>

        <div :class="[authenticated ? 'w-[calc(100%-16rem)]' : 'w-full']" class="h-screen h-[100dvh] xl:w-full flex flex-col">

            <Header />

            <div class="grow overflow-y-auto flex flex-col relative overflow-overlay">

                <div class="h-fit max-h-full min-w-96 m-2 absolute right-0 flex flex-col items-end gap-2 z-50">
                    <Alert :key="alert" v-for="alert in alerts" :type="alert.type" :message="alert.message" />
                </div>

                <slot />

<!--                <p class="text-xs text-subtitle text-center pt-8 pb-1">&nbsp;</p>-->

            </div>

        </div>

    </div>

</template>

<script setup>

    import { computed, ref, watch, onMounted } from "vue"
    import Navigation from "../components/Navigation.vue"
    import Header from "../components/Header.vue"
    import NavigationLink from "../components/NavigationLink.vue"
    import SwitchInput from "../components/Form/SwitchInput.vue"
    import ContentPage from "./content-page.vue"
    import Alert from "../components/Alert.vue"
    import {
        PresentationChartLineIcon,
        BookOpenIcon,
        ArrowPathRoundedSquareIcon,
        BuildingStorefrontIcon,
        CalendarDaysIcon
    } from "@heroicons/vue/24/outline"
    import store from "../store"
    import { footerWords, getRandomInt } from "../utils";
    import axios from "axios";

    const authenticated = computed(() => store.state.auth.isAuthenticated)
    const alerts = computed(() => store.state.alert.alerts)

    const remote = ref(
        localStorage.getItem('connection') === 'remote_mysql'
    )
    const loading = ref(false)

    watch(remote, () => {

        loading.value = true

        remote.value ? localStorage.setItem('connection', 'remote_mysql') :
            localStorage.setItem('connection', 'mysql')

        window.axios.defaults.headers.common['database'] = localStorage.getItem('connection') || 'mysql'

        loading.value = false

        store.dispatch("logout")
        location.reload()

    })

    onMounted(() => {

        if (!! ! localStorage.getItem('connection')) {
            localStorage.setItem('connection', 'mysql')
        }

    })

</script>

<style scoped>

    .overflow-overlay {
        overflow: overlay;
    }

</style>
