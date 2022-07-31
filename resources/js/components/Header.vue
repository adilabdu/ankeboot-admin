<template>

  <div class="flex flex-col w-full bg-white shadow-sm border-b border-border-light">
    <header :class="[authenticated ? 'justify-between' : 'justify-end']" class="w-full min-h-[4rem] flex items-center px-8">

        <div v-if="authenticated" class="flex items-center justify-center gap-4 h-full">
            <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 16C9.77498 15.9996 11.4988 15.4054 12.897 14.312L17.293 18.708L18.707 17.294L14.311 12.898C15.405 11.4997 15.9996 9.77544 16 8C16 3.589 12.411 0 8 0C3.589 0 0 3.589 0 8C0 12.411 3.589 16 8 16ZM8 2C11.309 2 14 4.691 14 8C14 11.309 11.309 14 8 14C4.691 14 2 11.309 2 8C2 4.691 4.691 2 8 2Z" fill="#6A727F"/>
            </svg>
            <input class="h-full focus:outline-none placeholder-subtitle w-96" type="text" placeholder="Search..." />
        </div>

        <div v-if="authenticated" class="flex items-center justify-center gap-6">
            <div @click="toggleView" class="z-10 cursor-pointer relative border border-brand-primary font-medium w-12 h-12 rounded-full bg-brand-secondary shadow-sm flex items-center justify-center">
                <h1 class="text-brand-primary text-base">{{ user.name[0] }}</h1>

                <ul ref="headerMenu" :class="[viewHeaderMenu ? '' : 'hidden']" class="text-subtitle font-medium right-0 -mr-3 -mb-3 z-50 bottom-0 translate-y-full overflow-auto absolute max-h-72 w-[12rem] bg-white shadow-md rounded-md border-[0.5px] border-border-light">
                    <li @click="settings" class="hover:bg-brand-secondary hover:text-brand flex justify-start gap-4 items-center h-10 px-4 group cursor-pointer">
                        <svg class="" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.42188 14C11.6279 14 13.4219 12.206 13.4219 10C13.4219 7.794 11.6279 6 9.42188 6C7.21587 6 5.42188 7.794 5.42188 10C5.42188 12.206 7.21587 14 9.42188 14ZM9.42188 8C10.5059 8 11.4219 8.916 11.4219 10C11.4219 11.084 10.5059 12 9.42188 12C8.33788 12 7.42188 11.084 7.42188 10C7.42188 8.916 8.33788 8 9.42188 8Z" fill="#6A727F"/>
                            <path d="M0.267829 14.136L1.26783 15.866C1.79883 16.783 3.07683 17.127 3.99783 16.596L4.52683 16.29C5.10537 16.7451 5.74324 17.1192 6.42283 17.402V18C6.42283 19.103 7.31983 20 8.42283 20H10.4228C11.5258 20 12.4228 19.103 12.4228 18V17.402C13.1022 17.1192 13.74 16.7454 14.3188 16.291L14.8478 16.597C15.7708 17.127 17.0458 16.785 17.5788 15.866L18.5778 14.137C18.8428 13.6777 18.9147 13.132 18.7777 12.6197C18.6406 12.1075 18.3058 11.6706 17.8468 11.405L17.3418 11.113C17.4492 10.3756 17.4492 9.62645 17.3418 8.889L17.8468 8.597C18.3056 8.33123 18.6402 7.89433 18.7773 7.38216C18.9143 6.86998 18.8426 6.32436 18.5778 5.865L17.5788 4.136C17.0478 3.216 15.7708 2.871 14.8478 3.404L14.3188 3.71C13.7403 3.25491 13.1024 2.8808 12.4228 2.598V2C12.4228 0.897 11.5258 0 10.4228 0H8.42283C7.31983 0 6.42283 0.897 6.42283 2V2.598C5.74347 2.88084 5.10565 3.25459 4.52683 3.709L3.99783 3.403C3.07383 2.872 1.79783 3.216 1.26683 4.135L0.267829 5.864C0.00281164 6.3233 -0.0690592 6.86901 0.0680041 7.38126C0.205067 7.89352 0.539856 8.33042 0.998829 8.596L1.50383 8.888C1.39606 9.62508 1.39606 10.3739 1.50383 11.111L0.998829 11.403C0.53999 11.669 0.205358 12.106 0.0683222 12.6184C-0.0687136 13.1307 0.00303391 13.6765 0.267829 14.136V14.136ZM3.59383 11.378C3.48091 10.9273 3.42349 10.4646 3.42283 10C3.42283 9.538 3.48083 9.074 3.59283 8.622C3.64557 8.41133 3.62822 8.18921 3.54341 7.98928C3.45861 7.78935 3.31096 7.6225 3.12283 7.514L1.99983 6.864L2.99783 5.135L4.14283 5.797C4.32955 5.90505 4.54629 5.94962 4.7605 5.92401C4.97471 5.89841 5.17483 5.80401 5.33083 5.655C6.00735 5.01156 6.82316 4.53281 7.71483 4.256C7.91965 4.19347 8.09899 4.06683 8.22645 3.89473C8.3539 3.72263 8.42274 3.51416 8.42283 3.3V2H10.4228V3.3C10.4229 3.51416 10.4918 3.72263 10.6192 3.89473C10.7467 4.06683 10.926 4.19347 11.1308 4.256C12.0223 4.53321 12.838 5.0119 13.5148 5.655C13.671 5.80372 13.8711 5.89792 14.0852 5.92352C14.2994 5.94911 14.516 5.90473 14.7028 5.797L15.8468 5.136L16.8468 6.865L15.7228 7.514C15.5348 7.62262 15.3873 7.78948 15.3025 7.98938C15.2177 8.18927 15.2003 8.41133 15.2528 8.622C15.3648 9.074 15.4228 9.538 15.4228 10C15.4228 10.461 15.3648 10.925 15.2518 11.378C15.1993 11.5888 15.2169 11.8109 15.3019 12.0108C15.3869 12.2107 15.5346 12.3775 15.7228 12.486L16.8458 13.135L15.8478 14.864L14.7028 14.203C14.5162 14.0948 14.2994 14.0501 14.0851 14.0757C13.8709 14.1013 13.6707 14.1958 13.5148 14.345C12.8383 14.9884 12.0225 15.4672 11.1308 15.744C10.926 15.8065 10.7467 15.9332 10.6192 16.1053C10.4918 16.2774 10.4229 16.4858 10.4228 16.7L10.4248 18H8.42283V16.7C8.42274 16.4858 8.3539 16.2774 8.22645 16.1053C8.09899 15.9332 7.91965 15.8065 7.71483 15.744C6.82333 15.4668 6.00761 14.9881 5.33083 14.345C5.17513 14.1954 4.97488 14.1007 4.76048 14.0753C4.54607 14.0498 4.32922 14.095 4.14283 14.204L2.99883 14.866L1.99883 13.137L3.12283 12.486C3.31102 12.3775 3.45878 12.2107 3.54376 12.0108C3.62874 11.8109 3.64631 11.5888 3.59383 11.378V11.378Z" fill="#6A727F"/>
                        </svg>
                        <p class="capitalize">Settings</p>
                    </li>
                    <li @click="attemptLogout" class="hover:bg-brand-secondary hover:text-brand flex justify-start gap-5 items-center h-10 px-4 group cursor-pointer">
                        <svg class="-ml-1" width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14 10V8H5V5L0 9L5 13V10H14Z" fill="#6A727F"/>
                            <path d="M18 0H9C7.897 0 7 0.897 7 2V6H9V2H18V16H9V12H7V16C7 17.103 7.897 18 9 18H18C19.103 18 20 17.103 20 16V2C20 0.897 19.103 0 18 0Z" fill="#6A727F"/>
                        </svg>
                        <p class="capitalize">Log out</p>
                    </li>
                </ul>

            </div>
        </div>

        <button v-if="!authenticated" class="text-subtitle px-4 py-2 rounded-md hover:bg-wallpaper capitalize">request new password</button>

    </header>
  </div>

</template>

<script setup>

    import { ref, computed } from "vue"
    import { onClickOutside } from "@vueuse/core"
    import store from "../store"
    import router from "../router"

    const authenticated = computed(() => store.state.auth.isAuthenticated)
    const user = computed(() => store.state.auth.user)

    const viewHeaderMenu = ref(false)
    const headerMenu = ref(null)

    function toggleView() {
        viewHeaderMenu.value = !viewHeaderMenu.value
    }

    onClickOutside(headerMenu, () => {
        if (viewHeaderMenu.value) {
            toggleView()
        }
    })

    function settings() {

    }

    function attemptLogout() {
        store.dispatch("logout")
            .then(() => {
                router.push({ name: "Login" })
            })
    }

</script>

<style scoped>

</style>
