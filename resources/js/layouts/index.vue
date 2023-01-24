<template>

    <div class="flex flex-row max-w-full relative overflow-x-hidden">

        <Navigation :minimize="miniNav" v-if="authenticated">

            <div class="flex flex-col justify-between h-full">

                <div class="flex flex-col">
                    <NavigationLink :minimize="miniNav" v-slot="props" label="dashboard" route="">
                        <PresentationChartLineIcon class="w-6 h-6" :class="[props.active ? 'stroke-white' : 'stroke-black', 'stroke-[1.5] scale-90']" />
                    </NavigationLink>
                    <NavigationLink :minimize="miniNav" v-slot="props" label="books">
                        <BookOpenIcon class="w-6 h-6 scale-90 stroke-[1.5]" :class="[props.active ? 'stroke-white' : 'stroke-black']" />
                    </NavigationLink>
                    <NavigationLink :minimize="miniNav" v-slot="props" label="transactions">
                        <ArrowPathRoundedSquareIcon class="w-6 h-6 scale-90 stroke-[1.5]" :class="[props.active ? 'stroke-white' : 'stroke-black']" />
                    </NavigationLink>
                    <NavigationLink :minimize="miniNav" v-slot="props" label="stores">
                        <BuildingStorefrontIcon class="w-6 h-6 stroke-[1.5] scale-90" :class="[props.active ? 'stroke-white' : 'stroke-black']" />
                    </NavigationLink>
                    <NavigationLink :minimize="miniNav" :number="store.state.daily_sale.unsubmitted" v-slot="props" label="daily sales" route="daily-sales">
                        <CalendarDaysIcon class="w-6 h-6 scale-90" :class="[props.active ? 'stroke-white stroke-2' : 'stroke-black stroke-[1.5]']" />
                    </NavigationLink>
                    <NavigationLink :minimize="miniNav" v-slot="props" label="consignments" route="consignments">
                        <CogIcon class="w-6 h-6 stroke-[1.5] scale-90" :class="[props.active ? 'stroke-white' : 'stroke-black']" />
                    </NavigationLink>
                    <NavigationLink :minimize="miniNav" v-slot="props" label="credit sales" route="credit-sales">
                        <BanknotesIcon class="w-6 h-6 stroke-[1.5] scale-90" :class="[props.active ? 'stroke-white' : 'stroke-black']" />
                    </NavigationLink>
                    <NavigationLink :minimize="miniNav" v-slot="props" label="suppliers">
                        <PhoneIcon class="w-6 h-6 stroke-[1.5] scale-90" :class="[props.active ? 'stroke-white' : 'stroke-black']" />
                    </NavigationLink>
                    <NavigationLink :minimize="miniNav" v-slot="props" label="documents" route="documents">
                        <PaperClipIcon class="w-6 h-6 stroke-[1.5] scale-90" :class="[props.active ? 'stroke-white' : 'stroke-black']" />
                    </NavigationLink>
                    <NavigationLink :minimize="miniNav" v-slot="props" label="mailing list" route="mailing-list">
                        <AtSymbolIcon class="w-6 h-6 stroke-[1.5] scale-90" :class="[props.active ? 'stroke-white' : 'stroke-black']" />
                    </NavigationLink>
                    <NavigationLink :minimize="miniNav" v-slot="props" label="resources" route="resources">
                        <CubeIcon class="w-6 h-6 stroke-[1.5] scale-90" :class="[props.active ? 'stroke-white' : 'stroke-black']" />
                    </NavigationLink>
                </div>

                <div class="flex flex-col items-center pt-8 gap-2">
                    <SwitchInput :class="{ 'hidden': miniNav }" :loading="loading" label="Remote" v-model="remote" />
                    <h1 class="flex gap-1 text-xs text-subtitle text-center">
                        <span> &copy; </span>
                        <span :class="{ 'hidden': miniNav }"> {{ footerWords()[getRandomInt(footerWords().length)] }}. </span>
                        <span> 2023 </span>
                    </h1>
                </div>

            </div>

        </Navigation>

        <div :class="[authenticated ? miniNav ? 'w-[calc(100%-63px)]' : 'w-[calc(100%-16rem)]' : 'w-full']" class="min-h-screen h-[100dvh] xl:w-full flex flex-col">

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
    import Alert from "../components/Alert.vue"
    import {
        PresentationChartLineIcon,
        BookOpenIcon,
        ArrowPathRoundedSquareIcon,
        BuildingStorefrontIcon,
        CalendarDaysIcon,
        CogIcon,
        BanknotesIcon,
        PhoneIcon,
        PaperClipIcon,
        AtSymbolIcon,
        CubeIcon
    } from "@heroicons/vue/24/outline"
    import store from "../store"
    import { footerWords, getRandomInt } from "../utils";

    const authenticated = computed(() => store.state.auth.isAuthenticated)
    const alerts = computed(() => store.state.alert.alerts)
    const miniNav = computed(() => store.state.ui.minimizeNavigation)

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
