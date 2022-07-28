<template>

    <div class="w-full grid grid-cols-12">

        <div class="col-span-3"></div>

        <div class="flex flex-col items-center h-full w-full col-span-6 py-12 gap-4">

            <div v-if="!authenticated" class="px-4 flex items-center justify-start gap-2">

                <div class="leading-none text-left">
                    <h1 class="font-semibold text-lg text-brand-primary">Ankeboot</h1>
                    <h1 class="text-subtitle">Management System</h1>
                </div>

            </div>

            <form @submit.prevent="attemptLogin" method="POST" v-if="!authenticated" class="flex flex-col gap-2 relative">

                <div v-if="loading" class="flex items-center justify-center w-full h-full bg-white/75 absolute rounded-md">

                    <p class="text-xs text-brand-primary">Loading...</p>

                </div>

                <input v-model="credentials.credential" placeholder="Enter username, phone number or email" type="text" name="username" id="username" class="px-2 h-10 w-80 bg-white rounded-md border border-border-light shadow-sm" />
                <input v-model="credentials.password" placeholder="Enter password" type="password" name="password" id="password" class="px-2 h-10 w-80 bg-white rounded-md border border-border-light shadow-sm" />
                <button class="capitalize bg-brand-primary rounded-md shadow-sm h-10 text-white">Log in</button>

            </form>

            <div class="flex flex-col w-full gap-2 items-center">
                <button v-if="authenticated" @click="fetchUser" class="capitalize bg-brand-secondary text-brand-primary rounded-md shadow-sm h-10 w-80">Fetch Logged in User</button>
                <button v-if="authenticated" @click="attemptLogout" class="capitalize bg-brand-secondary text-brand-primary rounded-md shadow-sm h-10 w-80 border border-brand-primary">Log out User</button>
            </div>

        </div>

        <div class="col-span-3"></div>

    </div>

</template>

<script setup>

    import axios from "axios";
    import { ref, onMounted, computed } from "vue";
    import store from "../store"

    const credentials = ref({
        credential: '',
        password: ''
    })
    const loading = ref(false)
    const authenticated = computed(() => store.state.auth.isAuthenticated)

    function attemptLogin() {

        loading.value = true

        axios.get('/sanctum/csrf-cookie').then(response => {

            axios.post('/api/auth/login', credentials.value).then(res => {

                console.log('Log in success ', res)
                store.dispatch('toggleAuthentication')
                fetchUser()

            }).catch((error) => {

                console.log('Log in fail ', error)

            })

        }).finally(() => { loading.value = false })

    }

    async function fetchUser() {

        axios.get('/api/user').then(response => {

            console.table(response.data)
            store.dispatch('setUser', response.data)

        }).catch(error => {

            console.log('User fetch fail ', error)
            store.dispatch('destroyUser')

        })

    }

    function attemptLogout() {

        axios.post('/api/auth/logout').then(response => {

            console.log('Log out success ', response)
            fetchUser()
            store.dispatch('destroyUser')

        }).catch(error => {

            console.log('Log out fail ', error)

        })

    }

</script>

<style scoped>

</style>
