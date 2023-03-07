import { createRouter, createWebHistory } from 'vue-router'
import store from "../store"

const routes = [
    {
        path: '/',
        name: 'Dashboard',
        component: () => import('../pages/dashboard.vue'),
        meta: {
            requiresLogin: true
        }
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import('../pages/login.vue'),
        meta: {
            requiresGuest: true
        }
    },
    {
        path: '/new',
        name: 'NewFormLayout',
        component: () => import("../layouts/form-content-page.vue"),
        meta: {
            requiresLogin: true
        },
        children: [
            {
                path: 'books',
                name: 'CreateBook',
                component: () => import("../pages/new/books.vue")
            },
            {
                path: 'transactions',
                name: 'CreateTransaction',
                component: () => import("../pages/new/transactions.vue"),
                children: [
                    {
                        path: ':book_id',
                        name: 'CreateTransactionForBook',
                        component: () => import("../pages/new/transactions.vue")
                    }
                ]
            }
        ]
    },
    {
        path: '/update',
        name: 'UpdateFormLayout',
        component: () => import("../layouts/form-content-page.vue"),
        meta: {
            requiresLogin: true
        },
        children: [
            {
                path: 'books',
                name: 'UpdateBooks',
                component: () => import("../pages/update/books/index.vue")
            },
            {
                path: 'books/:id',
                name: 'UpdateBook',
                component: () => import("../pages/update/books/books.vue")
            },
            {
                path: 'transactions/:id',
                name: 'UpdateTransaction',
                component: () => import("../pages/update/transactions.vue")
            }
        ]
    },
    {
        path: '/books',
        name: 'BookLayout',
        component: () => import("../pages/books/index.vue"),
        meta: {
            requiresLogin: true
        },
        children: [
            {
                path: '',
                name: 'Books',
                component: () => import("../pages/books/all.vue")
            },
            {
                path: ':id',
                name: 'Book',
                component: () => import("../pages/books/single.vue")
            }
        ],
    },
    {
        path: '/stores',
        name: 'StoreLayout',
        component: () => import("../pages/stores/index.vue"),
        meta: {
            requiresLogin: true
        },
        children: [
            {
                path: '',
                name: 'Stores',
                component: () => import("../pages/stores/all.vue")
            },
            {
                path: ':id',
                name: 'Store',
                component: () => import("../pages/stores/single.vue")
            }
        ],
    },
    {
        path: '/transactions',
        name: 'TransactionLayout',
        component: () => import("../pages/transactions/index.vue"),
        meta: {
            requiresLogin: true
        },
        children: [
            {
                path: '',
                name: 'Transactions',
                component: () => import("../pages/transactions/all.vue")
            },
            {
                path: ':id',
                name: 'Transaction',
                component: () => import("../pages/transactions/single.vue")
            }
        ],
    },
    {
        path: '/credit-sales',
        name: 'CreditSales',
        component: () => import('../layouts/content-page.vue'),
        meta: {
            requiresLogin: true
        }
    },
    {
        path: '/daily-sales',
        name: 'DailySaleLayout',
        component: () => import("../pages/daily-sales/index.vue"),
        meta: {
            requiresLogin: true
        },
        children: [
            {
                path: '',
                name: 'DailySales',
                component: () => import("../pages/daily-sales/all.vue")
            },
            {
                path: ':id',
                name: 'DailySale',
                component: () => import("../pages/daily-sales/single.vue")
            },
        ],
    },
    {
        path: '/suppliers',
        name: 'Suppliers',
        component: () => import('../layouts/content-page.vue'),
        meta: {
            requiresLogin: true
        }
    },
    {
        path: '/consignments',
        name: 'ConsignmentLayout',
        component: () => import("../pages/consignments/index.vue"),
        meta: {
            requiresLogin: true
        },
        children: [
            {
                path: '',
                name: 'Consignments',
                component: () => import("../pages/consignments/all.vue")
            },
            {
                path: ':id',
                name: 'Consignment',
                component: () => import("../pages/consignments/single.vue")
            }
        ]
    },
    {
        path: '/documents',
        name: 'DocumentLayout',
        component: () => import("../pages/documents/index.vue"),
        meta: {
            requiresLogin: true
        },
        children: [
            {
                path: 'file/employee-hire',
                name: 'EmployeeHire',
                component: () => import("../pages/documents/employee-hire.vue")
            },
            {
                path: 'file/new',
                name: 'New',
                component: () => import("../pages/documents/new.vue")
            }
        ]
    },
    {
        path: '/mailing-list',
        name: 'MailingListLayout',
        component: () => import("../pages/mailing-list/index.vue"),
        meta: {
            requiresLogin: true
        },
        children: [
            {
                path: '',
                name: 'MailingLists',
                component: () => import("../pages/mailing-list/all.vue")
            },
            {
                path: ':id',
                name: 'MailingList',
                component: () => import("../pages/mailing-list/all.vue")
            }
        ]
    },
    {
        path: '/resources',
        name: 'ResourceLayout',
        component: () => import("../pages/books/index.vue"),
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

    store.dispatch("togglePageLoading", true).then()

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

router.afterEach(() => {

    store.dispatch("togglePageLoading", false).then()

})

export default router
