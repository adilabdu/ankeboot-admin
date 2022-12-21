import { createRouter, createWebHistory } from 'vue-router'
import store from "../store"

import ContentPage from '../layouts/content-page.vue'
import Dashboard from '../pages/dashboard.vue'
import Login from '../pages/login.vue'

import BookLayout from "../pages/books/index.vue";
import Books from "../pages/books/all.vue";
import Book from "../pages/books/single.vue";

import StoreLayout from "../pages/stores/index.vue";
import Stores from "../pages/stores/all.vue";
import Store from "../pages/stores/single.vue"

import FormLayout from "../layouts/form-content-page.vue";
import CreateBook from "../pages/new/books.vue";
import CreateTransaction from "../pages/new/transactions.vue";
import UpdateBook from "../pages/update/books.vue";
import UpdateTransaction from "../pages/update/transactions.vue"

import TransactionLayout from "../pages/transactions/index.vue"
import Transactions from "../pages/transactions/all.vue"
import Transaction from "../pages/transactions/single.vue"

import DailySaleLayout from "../pages/daily-sales/index.vue"
import DailySales from "../pages/daily-sales/all.vue"
import DailySale from "../pages/daily-sales/single.vue"

import ConsignmentLayout from "../pages/consignments/index.vue"
import Consignments from "../pages/consignments/all.vue"
import Consignment from "../pages/consignments/single.vue"

import DocumentLayout from "../pages/documents/index.vue"
import EmployeeHire from "../pages/documents/employee-hire.vue"
import New from "../pages/documents/new.vue"

import MailingListLayout from "../pages/mailing-list/index.vue"
import MailingLists from "../pages/mailing-list/all.vue"

import ResourceLayout from "../pages/books/index.vue";

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
                path: 'books/:id',
                name: 'UpdateBook',
                component: UpdateBook
            },
            {
                path: 'transactions/:id',
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
        path: '/stores',
        name: 'StoreLayout',
        component: StoreLayout,
        meta: {
            requiresLogin: true
        },
        children: [
            {
                path: '',
                name: 'Stores',
                component: Stores
            },
            {
                path: ':id',
                name: 'Store',
                component: Store
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
            {
                path: ':id',
                name: 'Transaction',
                component: Transaction
            }
        ],
    },
    {
        path: '/credit-sales',
        name: 'CreditSales',
        component: ContentPage,
        meta: {
            requiresLogin: true
        }
    },
    {
        path: '/daily-sales',
        name: 'DailySaleLayout',
        component: DailySaleLayout,
        meta: {
            requiresLogin: true
        },
        children: [
            {
                path: '',
                name: 'DailySales',
                component: DailySales
            },
            {
                path: ':id',
                name: 'DailySale',
                component: DailySale
            },
        ],
    },
    {
        path: '/suppliers',
        name: 'Suppliers',
        component: ContentPage,
        meta: {
            requiresLogin: true
        }
    },
    {
        path: '/consignments',
        name: 'ConsignmentLayout',
        component: ConsignmentLayout,
        meta: {
            requiresLogin: true
        },
        children: [
            {
                path: '',
                name: 'Consignments',
                component: Consignments
            },
            {
                path: ':id',
                name: 'Consignment',
                component: Consignment
            }
        ]
    },
    {
        path: '/documents',
        name: 'DocumentLayout',
        component: DocumentLayout,
        meta: {
            requiresLogin: true
        },
        children: [
            {
                path: 'file/employee-hire',
                name: 'EmployeeHire',
                component: EmployeeHire
            },
            {
                path: 'file/new',
                name: 'New',
                component: New
            }
        ]
    },
    {
        path: '/mailing-list',
        name: 'MailingListLayout',
        component: MailingListLayout,
        meta: {
            requiresLogin: true
        },
        children: [
            {
                path: '',
                name: 'MailingLists',
                component: MailingLists
            },
            {
                path: ':id',
                name: 'MailingList',
                component: MailingLists
            }
        ]
    },
    {
        path: '/resources',
        name: 'ResourceLayout',
        component: ResourceLayout,
        meta: {
            requiresLogin: true
        },
    }
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
