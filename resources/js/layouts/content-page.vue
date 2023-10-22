<template>

    <div id="contentPage" class="scrollbar-brand overflow-y-auto grow px-12 py-8 sm:px-4 xs:px-2 gap-8 flex flex-col w-full">
        <div class="print:hidden px-4 gap-3 -mb-3 bg-white w-fit min-h-[2rem] flex items-center justify-center border border-border-light shadow-sm rounded-lg">
            <template v-for="(route, index) in routes">
                <RouterLink class="hover:text-brand-primary focus:outline-none" v-if="route === '' && index !== routes.length - 1" :to="{ name: 'Dashboard' }">Home</RouterLink>
                <RouterLink :class="{'text-brand-primary' : index === routes.length - 1}" :to="{ path: getSubRoute(index) }" v-else class="capitalize hover:text-brand-primary focus:outline-none">{{ route === '' ? index === routes.length - 1 ? 'Dashboard' : 'Home' : route.replace('-' , ' ') }}</RouterLink>
                <p v-if="index + 1 !== routes.length" class="font-semibold text-subtitle">/</p>
            </template>
        </div>

        <div class="max-w-[1535px] flex flex-col gap-8">
            <slot />
        </div>

    </div>

    <Teleport v-if="contentLoading" to="#content-top-view">

        <Modal :class="[authenticated ? 'w-[calc(100%-16rem)]' : 'w-full']" class="xl:w-full !bg-wallpaper">

            <svg class="animate-rotate" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.4706 5.94533C18.1987 5.55015 18.4769 4.63079 17.9774 3.96992C17.0157 2.69771 15.7589 1.66786 14.3065 0.974827C12.4006 0.0653761 10.2557 -0.217352 8.1792 0.167163C6.10271 0.551679 4.20125 1.5837 2.74734 3.11531C1.29344 4.64692 0.361732 6.59949 0.0857576 8.69317C-0.190217 10.7869 0.203708 12.9142 1.21108 14.7702C2.21844 16.6262 3.78753 18.1157 5.69346 19.0252C7.59939 19.9346 9.74431 20.2174 11.8208 19.8328C13.4032 19.5398 14.8839 18.8708 16.1424 17.8912C16.7961 17.3824 16.7656 16.4223 16.1648 15.852V15.852C15.5639 15.2816 14.621 15.3229 13.9359 15.7887C13.1386 16.3308 12.2332 16.7055 11.2746 16.883C9.82102 17.1521 8.31958 16.9542 6.98542 16.3176C5.65127 15.681 4.55291 14.6384 3.84775 13.3391C3.1426 12.0399 2.86685 10.5508 3.06003 9.08522C3.25321 7.61964 3.90541 6.25284 4.92314 5.18072C5.94087 4.10859 7.27189 3.38618 8.72544 3.11701C10.179 2.84785 11.6804 3.04576 13.0146 3.68238C13.8945 4.10224 14.6718 4.69872 15.3015 5.42894C15.8424 6.05634 16.7425 6.34051 17.4706 5.94533V5.94533Z" fill="#FF8100"/>
            </svg>

        </Modal>

    </Teleport>

</template>

<script setup>

    import { computed } from "vue"
    import { useRoute } from "vue-router"
    import store from "../store"
    import Modal from "../components/Modal.vue"

    const authenticated = computed(() => store.state.auth.isAuthenticated)

    const routes = computed(() => {

        return useRoute().fullPath.split('/')

    })

    const contentLoading = computed(() => {

        return store.state.ui.contentLoading

    })

    function getSubRoute(i) {

        let subRoute = ''

        routes.value.forEach((route, index) => {

            if (index < i) {
                subRoute += `${route}/`
            } else if (index === i) (
                subRoute += route
            )

        })

        return subRoute

    }

</script>

<style scoped>

</style>
