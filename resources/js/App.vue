<template>

    <div class="h-screen w full grid grid-cols-12">

        <div class="col-span-3"></div>

        <div class="flex flex-col items-center h-full w-full col-span-6 py-12 gap-4">

            <h1 class="capitalize font-medium text-base">Testing <span class="text-brand-primary">Sanctum</span> Login Functionality</h1>

            <div class="flex flex-col gap-2">

                <input v-model="credentials.credential" placeholder="Enter username, phone number or email" type="text" name="username" id="username" class="px-2 h-10 w-80 bg-white rounded-md border border-border-light shadow-sm" />
                <input v-model="credentials.password" placeholder="Enter password" type="password" name="password" id="password" class="px-2 h-10 w-80 bg-white rounded-md border border-border-light shadow-sm" />
                <button @click="attemptLogin" class="capitalize bg-brand-primary rounded-md shadow-sm h-10 text-white">Log in</button>

            </div>

            <button v-if="authenticated" @click="fetchUser" class="capitalize bg-brand-secondary text-brand-primary rounded-md shadow-sm h-10 w-80">Fetch Logged in User</button>

        </div>

        <div class="col-span-3"></div>

    </div>

</template>

<script setup>

    import { ref, onMounted } from "vue";
    import axios from "axios";

    const credentials = ref({
        credential: '',
        password: ''
    })

    const authenticated = ref(false)

    function attemptLogin() {

        axios.get('/sanctum/csrf-cookie').then(response => {

            axios.post('/api/auth/login', credentials.value).then(res => {

                console.log('Log in success ', res)
                fetchUser()

            }).catch((error) => {

                console.log('Log in fail ', error)

            })

        })

    }

    function fetchUser() {

        axios.get('/api/user').then(response => {

            console.log('User fetch success', response)
            authenticated.value = true

        }).catch(error => {

            console.log('User fetch fail ', error)

        })

    }

    onMounted(() => {

        fetchUser()

    })

</script>

<style scoped>

</style>
