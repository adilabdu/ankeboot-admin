import { createApp } from "vue"
import "../css/app.css"
import store from "./store"
import router from "./router/index"

import.meta.glob([
    '../assets/favicon/**',
    '../assets/images/**',
]);

import App from "./App.vue"

import './bootstrap'

import { useFavicon } from "@vueuse/core"
import favicon from "../assets/favicon/favicon.ico";

useFavicon().value = favicon;

store.dispatch("getUser")
    .then(() => {

        createApp(App)
            .use(store)
            .use(router)
            .mount('#app')
    })


