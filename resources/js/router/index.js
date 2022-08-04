import { createRouter, createWebHistory } from 'vue-router'
import store from "../store"

import ContentPage from '../layouts/content-page.vue'
import Dashboard from '../pages/dashboard.vue'
import Login from '../pages/login.vue'

import BookLayout from "../pages/books/index.vue";
import Books from "../pages/books/all.vue";
import Book from "../pages/books/single.vue";

import FormLayout from "../layouts/form-content-page.vue";
import CreateBook from "../pages/new/books.vue";
import CreateTransaction from "../pages/new/transactions.vue";
import UpdateBook from "../pages/update/books.vue";
import UpdateTransaction from "../pages/update/transactions.vue"

import TransactionLayout from "../pages/transactions/index.vue"
import Transactions from "../pages/transactions/all.vue"

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
        path: '/new',
        name: 'NewFormLayout',
        component: FormLayout,
        meta: {
            requiresLogin: true
        },
        children: [
            {
                path: 'books',
                name: 'CreateBook',
                component: CreateBook
            },
            {
                path: 'transactions',
                name: 'CreateTransaction',
                component: CreateTransaction
            }
        ]
    },
    {
        path: '/update',
        name: 'UpdateFormLayout',
        component: FormLayout,
        meta: {
            requiresLogin: true
        },
        children: [
            {
                path: 'books',
                name: 'UpdateBook',
                component: UpdateBook
            },
            {
                path: 'transactions',
                name: 'UpdateTransaction',
                component: UpdateTransaction
            }
        ]
    },
    {
        path: '/books',
        name: 'BookLayout',
        component: BookLayout,
        meta: {
            requiresLogin: true
        },
        children: [
            {
                path: '',
                name: 'Books',
                component: Books
            },
            {
                path: ':id',
                name: 'Book',
                component: Book
            }
        ],
    },
    {
        path: '/transactions',
        name: 'TransactionLayout',
        component: TransactionLayout,
        meta: {
            requiresLogin: true
        },
        children: [
            {
                path: '',
                name: 'Transactions',
                component: Transactions
            },
        ],
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
        path: '/daily-sales',
        name: 'DailySales',
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
