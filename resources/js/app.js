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

store.dispatch("getUser")
    .then(() => {

        createApp(App)
            .use(store)
            .use(router)
            .mount('#app')
    })


