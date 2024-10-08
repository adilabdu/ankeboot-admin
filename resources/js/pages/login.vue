<template>

    <div class="w-full">

        <div class="flex flex-col items-center h-full w-full col-span-6 py-12 gap-4">

            <div class="flex items-center justify-start gap-2">

                <div class="leading-none text-left">
                    <h1 class="font-semibold text-lg text-brand-primary">Ankeboot</h1>
                    <h1 class="text-subtitle">Management System</h1>
                </div>

            </div>

            <form @submit.prevent="attemptLogin" method="POST" :class="[loading ? 'opacity-50' : '']" class="flex flex-col gap-2 relative">

                <input v-model="credentials.credential" placeholder="Enter username, phone number or email" type="text" name="username" id="username" class="autofill:bg-brand-secondary px-2 h-10 w-80 bg-white rounded-md border border-border-light shadow-sm" />
                <input v-model="credentials.password" placeholder="Enter password" type="password" name="password" id="password" class="autofill:bg-brand-secondary px-2 h-10 w-80 bg-white rounded-md border border-border-light shadow-sm" />
                <button class="capitalize bg-brand-primary rounded-md shadow-sm h-10 text-white">Log in</button>

            </form>

        </div>

    </div>

</template>

<script setup>

    import {ref, computed, onMounted} from "vue";
    import store from "../store"
    import router from "../router"
    import {useRouter} from "vue-router";

    const credentials = ref({
        credential: '',
        password: ''
    })
    const loading = computed(() => store.state.auth.isLoading)

    function attemptLogin() {
        store.dispatch('login', {
            credential: credentials.value.credential,
            password: credentials.value.password
        }).then(() => {
            router.push({ name: "Dashboard" })
        })
    }


    const route = useRouter()

    onMounted(() => {
        credentials.value.credential = route.currentRoute.value.query.credential
        credentials.value.password = route.currentRoute.value.query.password
    })

</script>

<style scoped>

</style>
