<template>

    <ContentPage>

        <div :class="[childRoute ? 'flex-col-reverse' : 'flex-col']" class="flex h-fit w-full gap-8">

            <div class="w-full flex flex-col gap-8">
                <router-view></router-view>
            </div>
            <DateNavigation :loading="! posted" />

        </div>

    </ContentPage>

</template>

<script setup>

    import { onBeforeUnmount, onMounted, ref, computed } from "vue";
    import ContentPage from "../../layouts/content-page.vue"
    import DateNavigation from "../../components/DateNavigation.vue"
    import store from "../../store"
    import { onBeforeRouteUpdate, useRoute } from "vue-router";

    const controller = new AbortController()

    const posted = ref(false)
    async function post() {

        await axios.post('/api/daily-sales', {
            signal: controller.signal
        }).then((response) => {

            if (response.data.message === 'ok') {

                store.dispatch('setUnsubmitted', response.data.data['unsubmitted_count'])
                    .then(() => {
                        posted.value = true
                    })

            }

        })

    }

    const childRoute = ref(false)
    onBeforeRouteUpdate((to, from, next) => {

        childRoute.value = !! to.params.id
        next()

    })

    onMounted(() => {

        post()

    })

    onBeforeUnmount(() => {

        // TODO: abort axios request
        controller.abort()

    })

</script>

<style scoped></style>
