import './bootstrap';
import '../css/app.css';
import { createApp } from "vue"
import store from "./store";
import App from "./App.vue"

store.dispatch('getAuthenticatedUser').then(() => {})
createApp(App)
    .use(store)
    .mount('#app')
