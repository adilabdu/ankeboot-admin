<template>

    <div id="top-view"></div>

    <!-- <template v-if="useRoute().fullPath.includes('documents')">
        <router-view></router-view>
    </template> -->

    <Layout>
        <div id="content-top-view"></div>
        <router-view></router-view>
    </Layout>

</template>

<script setup>

    import { computed } from "vue"
    import Layout from "./layouts/index.vue";

    const primaryColor = computed(() => {
        return localStorage.getItem('connection') === 'remote_mysql' ?
            '#4B8D2C' : '#FF8100'
    })

    const secondaryColor = computed(() => {
        return localStorage.getItem('connection') === 'remote_mysql' ?
            '#4B8D2C20' : '#FF810020'
    })

    const color = {
        primary: 'red'
    }

</script>

<style>

</style>
