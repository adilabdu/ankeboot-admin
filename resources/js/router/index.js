import { createRouter, createWebHistory } from 'vue-router'
import store from "../store"

import ContentPage from '../layouts/content-page.vue'
import Dashboard from '../pages/dashboard.vue'
import Login from '../pages/login.vue'

const routes = [
    {
        path: '/',
        name: 'Dashboard',
        component: Dashboard,
        meta: {
            requiresLogin: true
        }
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: {
            requiresGuest: true
        }
    },
    {
        path: '/books',
        name: 'Books',
        component: ContentPage,
        meta: {
            requiresLogin: true
        }
    },
    {
        path: '/transactions',
        name: 'Transactions',
        component: ContentPage,
        meta: {
            requiresLogin: true
        }
    },
    {
        path: '/stocks',
        name: 'Stocks',
        component: ContentPage,
        meta: {
            requiresLogin: true
        }
    },
    {
        path: '/suppliers',
        name: 'Suppliers',
        component: ContentPage,
        meta: {
            requiresLogin: true
        }
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to, from, next) => {

    let loggedIn = store.getters.getAuthStatus

    if( to.matched.some( record => record.meta['requiresLogin'] ) ) {
        if ( !loggedIn ) next({ name: "Login" })
        else next()
    }

    if( to.matched.some( record => record.meta['requiresGuest'] ) ) {
        if ( loggedIn ) next({ name: "Dashboard" })
        else next()
    }

})

export default router
