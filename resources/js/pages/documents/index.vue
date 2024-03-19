<template>
  <ContentPage>
    <router-view />

    <button
      v-if="!isLoggedIn"
      @click="loginWithGoogle"
      class="shadow-sm flex gap-2 items-center justify-center bg-white rounded-md border-[1.75px] border-border-dark font-medium px-4 py-2.5 w-fit text-black/90"
    >
      <img
        class="w-4"
        src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg"
        alt="Google Logo"
      />
      <span>Sign in with Google</span>
    </button>

    <div
      v-if="files"
      class="grid grid-cols-5 xs:grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-3"
    >
      <GoogleDriveFileLink v-for="file in files" :file="file" />
    </div>
  </ContentPage>
</template>

<script setup>
import { computed, watch } from 'vue'
import ContentPage from '../../layouts/content-page.vue'
import GoogleDriveFileLink from '../../views/GoogleDriveFileLink.vue'
import axios from 'axios'
import store from '../../store'

const files = computed(() => store.state.googleDriveFiles.files)
const isLoggedIn = computed(() => store.state.googleDriveFiles.isLoggedIn)

function loginWithGoogle() {
  axios
    .get('/api/google/login/url')
    .then((response) => {
      window.open(response.data.auth_url)
    })
    .catch((error) => {
      alert(JSON.stringify(error))
    })
}

watch(
  isLoggedIn,
  () => {
    if (isLoggedIn.value) store.dispatch('getDriveFiles')
  },
  {
    immediate: true
  }
)
</script>

<style scoped></style>
