<template>

    <ContentPage>

        <div class="flex flex-col h-fit w-full gap-8">

            <router-view></router-view>
            <DateNavigation :loading="! posted" />

        </div>

    </ContentPage>

</template>

<script setup>

    import { onBeforeUnmount, onMounted, ref } from "vue";
    import ContentPage from "../../layouts/content-page.vue"
    import DateNavigation from "../../components/DateNavigation.vue"
    import store from "../../store"

    const controller = new AbortController()

    const posted = ref(false)
    async function post() {

        await axios.post('/api/daily-sales', {
            signal: controller.signal
        }).then((response) => {

            if (response.data.message === 'ok') {

                store.dispatch('setUnsubmitted', response.data.data['unsubmitted_count'])
                posted.value = true

            }

        })

    }

    onMounted(() => {

        post()

    })

    onBeforeUnmount(() => {

        // TODO: abort axios request
        controller.abort()

    })

</script>

<style scoped></style>
