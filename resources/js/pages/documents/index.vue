<template>

    <ContentPage>
        <router-view />

        <button v-if="loginViaGoogle" @click="loginWithGoogle" class="shadow-sm flex gap-2 items-center justify-center bg-white rounded-md border-[1.75px] border-border-dark font-medium px-4 py-2.5 w-fit text-black/90">
            <img class="w-4" src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg"  alt="Google Logo"/>
            <span>Sign in with Google</span>
        </button>

        Files: <pre>{{ files }}</pre>

    </ContentPage>

</template>

<script setup>

    import { onMounted, ref } from "vue"
    import axios from "axios";
    import ContentPage from "../../layouts/content-page.vue";

    const files = ref([])
    const loginViaGoogle = ref(false)

    function fetchDriveFiles() {
        axios.get('/api/google/drive/files')
            .then((response) => {
                files.value = response.data
            })
            .catch((error) => {
                loginViaGoogle.value = true
            })
    }

    function loginWithGoogle() {

        axios.get('/api/google/login/url')
            .then(response => {
                window.open(response.data.auth_url);
            })
            .catch(error => {
                alert(JSON.stringify(error))
            })

    }

    onMounted(() => {
        fetchDriveFiles()
    })

</script>

<style scoped>

</style>
