<template>

  <div class="flex flex-col w-full bg-white shadow-sm border-b border-border-light">
    <header :class="[authenticated ? 'justify-between' : 'justify-end']" class="w-full min-h-[4rem] gap-8 flex items-center px-8">

        <svg class="hidden xl:inline" @click="toggleNavigation" width="22" height="17" viewBox="0 0 22 17" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="22" height="3" fill="#6A727F"/>
            <rect y="7" width="22" height="3" fill="#6A727F"/>
            <rect y="14" width="22" height="3" fill="#6A727F"/>
        </svg>


        <div v-if="authenticated" class="flex flex-row-reverse items-center justify-center gap-4 h-full grow">
            <input ref="searchInput" @focusout="toggleInputFocus(false)" @focus="toggleInputFocus(true)" class="peer h-full selection:text-white selection:bg-brand-primary focus:outline-none placeholder-subtitle grow" type="text" placeholder="Search... (CTRL + K)" />
            <svg class="peer-focus:fill-brand-primary fill-subtitle transition-all duration-150 scale-90 peer-focus:scale-100" width="19" height="19" viewBox="0 0 19 19" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 16C9.77498 15.9996 11.4988 15.4054 12.897 14.312L17.293 18.708L18.707 17.294L14.311 12.898C15.405 11.4997 15.9996 9.77544 16 8C16 3.589 12.411 0 8 0C3.589 0 0 3.589 0 8C0 12.411 3.589 16 8 16ZM8 2C11.309 2 14 4.691 14 8C14 11.309 11.309 14 8 14C4.691 14 2 11.309 2 8C2 4.691 4.691 2 8 2Z" />
            </svg>
        </div>

        <div v-if="authenticated" class="relative w-12 h-12 rounded-full flex items-center justify-center">
            <svg :class="[viewNotificationsMenu ? 'scale-105' : '']" @click="toggleNotificationsView" width="20" height="20" viewBox="0 0 20 20" class="fill-transparent hover:scale-105 group transition-transform duration-300" xmlns="http://www.w3.org/2000/svg">
                <path class="stroke-black/50 group-hover:stroke-black" d="M12.8565 15.082C14.7197 14.8614 16.5504 14.4217 18.3105 13.772C16.8199 12.1208 15.9962 9.9745 15.9995 7.75V7.05V7C15.9995 5.4087 15.3674 3.88258 14.2421 2.75736C13.1169 1.63214 11.5908 1 9.9995 1C8.4082 1 6.88208 1.63214 5.75686 2.75736C4.63164 3.88258 3.9995 5.4087 3.9995 7V7.75C4.00252 9.97463 3.17849 12.121 1.6875 13.772C3.4205 14.412 5.2475 14.857 7.1425 15.082M12.8565 15.082C10.9585 15.3071 9.04051 15.3071 7.1425 15.082M12.8565 15.082C13.0006 15.5319 13.0364 16.0094 12.9611 16.4757C12.8857 16.942 12.7013 17.384 12.4229 17.7656C12.1444 18.1472 11.7798 18.4576 11.3587 18.6716C10.9376 18.8856 10.4719 18.9972 9.9995 18.9972C9.52712 18.9972 9.06142 18.8856 8.64031 18.6716C8.21919 18.4576 7.85457 18.1472 7.57612 17.7656C7.29767 17.384 7.11326 16.942 7.03791 16.4757C6.96256 16.0094 6.9984 15.5319 7.1425 15.082" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>

            <div v-if="! loadingNotifications" class="top-2 right-2 text-white rounded-full flex items-center justify-center absolute w-4 h-4 bg-brand-primary font-medium text-[0.5rem]">
                {{ unreadNotifications.length }}
            </div>

            <ul ref="notificationMenu" v-if="viewNotificationsMenu" class="w-96 min-h-[3rem] flex flex-col bg-white absolute border border-border-light shadow-sm rounded-md right-0 -mr-3 -mb-3 z-50 bottom-0 translate-y-full">

                <li v-if="loadingNotifications" class="flex flex-col gap-1 items-center justify-center text-subtitle p-4 w-full border-b ">

                    <svg class="animate-rotate" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.4706 5.94533C18.1987 5.55015 18.4769 4.63079 17.9774 3.96992C17.0157 2.69771 15.7589 1.66786 14.3065 0.974827C12.4006 0.0653761 10.2557 -0.217352 8.1792 0.167163C6.10271 0.551679 4.20125 1.5837 2.74734 3.11531C1.29344 4.64692 0.361732 6.59949 0.0857576 8.69317C-0.190217 10.7869 0.203708 12.9142 1.21108 14.7702C2.21844 16.6262 3.78753 18.1157 5.69346 19.0252C7.59939 19.9346 9.74431 20.2174 11.8208 19.8328C13.4032 19.5398 14.8839 18.8708 16.1424 17.8912C16.7961 17.3824 16.7656 16.4223 16.1648 15.852V15.852C15.5639 15.2816 14.621 15.3229 13.9359 15.7887C13.1386 16.3308 12.2332 16.7055 11.2746 16.883C9.82102 17.1521 8.31958 16.9542 6.98542 16.3176C5.65127 15.681 4.55291 14.6384 3.84775 13.3391C3.1426 12.0399 2.86685 10.5508 3.06003 9.08522C3.25321 7.61964 3.90541 6.25284 4.92314 5.18072C5.94087 4.10859 7.27189 3.38618 8.72544 3.11701C10.179 2.84785 11.6804 3.04576 13.0146 3.68238C13.8945 4.10224 14.6718 4.69872 15.3015 5.42894C15.8424 6.05634 16.7425 6.34051 17.4706 5.94533V5.94533Z" fill="#FF8100"/>
                    </svg>

                </li>
                
                <template v-else>

                    <li @click="notificationAction(unread.type, unread.id)" v-for="unread in unreadNotifications" class="hover:bg-border-light/25 cursor-pointer flex gap-4 p-4 w-full border-b ">

                        <div class="w-12 flex justify-center">
                            <div class="text-xl text-green-600 w-8 h-8 rounded-full bg-green-200 flex items-center justify-center">
                                +
                            </div>
                        </div>
                        <div v-if="unread.type = 'App\\Notifications\\NewSubscriberRegistered'" class="grow flex flex-col gap-1">
                            <p class="">New Subscriber</p>
                            <p class="text-subtitle">
                                <span class="text-black">{{ unread.data.name }}</span> subscribed to your channel.
                                You can find them in the subscribers list.
                            </p>
                        </div>
                        <div class="text-subtitle">1h</div>

                    </li>

                    <li v-if="unreadNotifications.length === 0" class="flex flex-col gap-1 items-center justify-center text-subtitle p-4 w-full border-b ">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="stroke-brand-primary scale-75 w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.143 17.082a24.248 24.248 0 003.844.148m-3.844-.148a23.856 23.856 0 01-5.455-1.31 8.964 8.964 0 002.3-5.542m3.155 6.852a3 3 0 005.667 1.97m1.965-2.277L21 21m-4.225-4.225a23.81 23.81 0 003.536-1.003A8.967 8.967 0 0118 9.75V9A6 6 0 006.53 6.53m10.245 10.245L6.53 6.53M3 3l3.53 3.53" />
                        </svg>
                        <p class="font-medium text-black">No unread notifications</p>
                        <p class="text-center">You don't have any unread notifications. You can view all your notifications instead.</p>
                        <button class="px-2 py-1 rounded-md bg-brand-secondary scale-90 mt-2 text focus:outline-none text-brand-primary">View all</button>

                    </li>

                </template>

            </ul>

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
    <div :class="[inputFocused ? 'w-full' : 'w-0']" class="transition-width duration-150 border-b border-brand-primary"></div>
  </div>

</template>

<script setup>

    import { ref, computed, onMounted } from "vue"
    import { onClickOutside } from "@vueuse/core"
    import store from "../store"
    import router from "../router"
import axios from "axios";

    const authenticated = computed(() => store.state.auth.isAuthenticated)
    const user = computed(() => store.state.auth.user)

    const viewNotificationsMenu = ref(false)
    const viewHeaderMenu = ref(false)
    const headerMenu = ref(null)
    const notificationMenu = ref(null)

    function toggleNotificationsView() {
        viewNotificationsMenu.value = !viewNotificationsMenu.value
    }

    function toggleView() {
        viewHeaderMenu.value = !viewHeaderMenu.value
    }

    onClickOutside(headerMenu, () => {
        
        if (viewHeaderMenu.value) {
            toggleView()
        }

    })

    onClickOutside(notificationMenu, () => {
        if (viewNotificationsMenu.value) {
            toggleNotificationsView()
        }
    })

    function settings() {

    }


    function toggleNavigation() {

        store.dispatch('toggleNavigation')

    }

    const inputFocused = ref(false)
    function toggleInputFocus(state) {
        inputFocused.value = state
    }

    function attemptLogout() {
        store.dispatch("logout")
            .then(() => {
                router.push({ name: "Login" })
            })
    }

    const unreadNotifications = ref([])
    const loadingNotifications = ref(true)
    async function getUnreadNotifications() {

        await axios.get('/api/notifications/unread')
            .then(response => {
                unreadNotifications.value = response.data.data
            })
            .catch(error => {
                alert(error)
            })

    }

    function notificationAction(type, id) {

        switch (type) {

            case 'App\\Notifications\\NewSubscriberRegistered':
                axios.post('/api/notifications/read', {
                    id
                }).then(() => {
                    getUnreadNotifications().then(() => {
                        router.push({ name: 'MailingLists' })
                    })
                })
                break;

            default:
                break;

        }

        toggleNotificationsView()

    }

    const searchInput = ref(null)

    onMounted(() => {

        getUnreadNotifications()
            .then()
            .finally(() => {
                loadingNotifications.value = false
            })

        document.onkeydown = function(e) {
            if (e.ctrlKey && e.keyCode === 75) {
                searchInput.value.focus()
                return false;
            }
        }

    })

</script>

<style scoped>

</style>
