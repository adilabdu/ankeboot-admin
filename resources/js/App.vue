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

    import { computed, ref } from "vue"
    import { useRoute } from "vue-router"
    import Layout from "./layouts/index.vue";
    import Login from "./pages/login.vue";

    const primaryColor = computed(() => {
        return localStorage.getItem('connection') === 'remote_mysql' ?
            '#4B8D2C' : '#FF8100'
    })

    const secondaryColor = computed(() => {
        return localStorage.getItem('connection') === 'remote_mysql' ?
            '#4B8D2C20' : '#FF810020'
    })

    console.log('hello from App ', primaryColor.value)

    const color = {
        primary: 'red'
    }

</script>

<style>

    /*:root {*/
    /*    --primary-color: v-bind('color.primary');*/
    /*    --secondary-color: v-bind('secondaryColor');*/
    /*}*/

    .hello {
        color: v-bind('color.primary')
    }

</style>
