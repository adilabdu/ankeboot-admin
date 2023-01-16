import axios from "axios";
import router from "../../router"
import { formatDate } from "../../utils"

const state = {

    books: null,
    book: null,
    statistics: null,
    loading: false,
    controller: new AbortController(),

}

const getters = {

}

const actions = {

    setLoadingState(context, payload) {
        context.commit('setLoadingState', payload)
    },

    async getBooks({ commit }) {

        await axios.get('/api/books', {
            signal: state.controller.signal
        }).then((response) => {
            commit('getBooks', response.data.data)
        }).catch((error) => {



        })

    },

    cancelBookRequest() {

        state.controller.abort()
        state.controller = new AbortController()

    },

    async postBook(context, payload) {

        const transaction_on = !! payload.transaction_on ?
            formatDate(payload.transaction_on) :
            null

        const transaction_type = payload.transaction_type !== undefined ?
            payload.transaction_type ?
                'purchase' :
                'sale' :
            undefined

        const transaction_data = {}

        if (!! payload.transaction_on) {
            Object.assign(transaction_data, {
                transaction_invoice: payload.transaction_invoice,
                transaction_quantity: payload.transaction_quantity,
                transaction_on,
                transaction_type
            })
        }

        return axios.post('/api/books', {
            title: payload.title,
            alternate_title: payload['alternate_title'],
            author: payload.author,
            category: payload.category,
            type: payload.type ? 'consignment' : 'cash',
            code: payload.code,
            balance: payload.balance,
            ... transaction_data
        }).then((response) => {
            return response.data
        }).catch((error) => {
            return error.response.data.message
        })

    },

    async updateBook(context, payload) {

        console.log('inside updateBook dispatch', payload)

        return axios.post('/api/books/update', {
            id: payload.id,
            title: payload.title,
            alternate_title: payload['alternate_title'],
            author: payload.author,
            category: payload.category,
            type: payload.type ? 'consignment' : 'cash',
            code: payload.code,
            balance: payload.balance
        }).then((response) => {
            return response.data
        }).catch((error) => {
            return error.response.data.message
        })

    },

    async postMultipleBooks({ commit, dispatch }, payload) {

        dispatch('setLoadingState', true)

        return axios.post('/api/books/csv', {
            file: payload.file
        }, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
            .then((response) => {
                return response.data
            }).catch((error) => {
                return error.response.data.message
            }).finally(() => {
                dispatch('setLoadingState', false)
            })
    },

    async deleteBook(context, payload) {

        return axios.post('/api/books/delete', {
            id: payload
        })
            .then((response) => {
                return response.data
            }).catch((error) => {
                return error.response.data.message
            })

    },

    async getBook({ commit, dispatch }, payload) {

        await axios.get('/api/books/', {
            params: {
                id: payload
            }
        }).then(async (response) => {

            if (response.data.data.type === 'consignment') {

                await axios.get('/api/consignments/returns', {
                    params: {
                        book_id: payload
                    }
                }).then((res) => {

                    commit('getBook', {
                        id: response.data.data.id,
                        code: response.data.data.code,
                        type: response.data.data.type,
                        title: response.data.data.title,
                        author: response.data.data.author,
                        alternate_title: response.data.data.alternate_title,
                        category: response.data.data.category,
                        balance: response.data.data.balance,
                        supplier: response.data.data.supplier,
                        transactions: [
                            ...response.data.data.transactions,
                            ...res.data.data.map(data => {

                                return {
                                    id: null,
                                    invoice: data.receipt,
                                    quantity: data.quantity,
                                    transaction_on: data.transaction_on,
                                    type: 'return',
                                    created_at: data.created_at,
                                    updated_at: data.updated_at
                                }

                            })
                        ]
                            .sort((a, b) => {
                                return new Date(b.updated_at).getTime() - new Date(a.updated_at).getTime();
                            }),
                        created_at: response.data.data['created_at'],
                        updated_at: response.data.data['updated_at']
                    })

                    console.log([
                        ...response.data.data.transactions,
                        ...res.data.data.map(data => {

                            return {
                                id: null,
                                quantity: data.quantity,
                                transaction_on: data.transaction_on,
                                invoice: data.receipt,
                                type: 'return'
                            }

                        })
                    ]
                        .sort((a, b) => {
                            return new Date(b.updated_at).getTime() - new Date(a.updated_at).getTime();
                        }))

                })

            }
            else {
                commit('getBook', response.data.data)
            }

        }).catch((error) => {


            router.push({ name: 'Books' }).then(() => {

                if (error.response.status === 500) {

                    dispatch('pushAlert', {
                        type: 'error',
                        message: 'Something went wrong, please try again later.'
                    })

                } else  {

                    dispatch('pushAlert', {
                        type: 'error',
                        message: error.response.data.message
                    })

                }

            })
        })
    },

    setBook(context, payload) {

        context.commit('getBook', payload)

    },

    async getBooksStats({ commit }) {

        await axios.get('/api/books/statistics', {
            signal: state.controller.signal
        }).then((response) => {

            commit('getBooksStats', response.data.data)

        }).catch((error) => {



        })

    },

    destroyBook({ commit }) {
        commit('destroyBook')
    }

}

const mutations = {

    setLoadingState(state, payload) {
        state.loading = payload
    },

    getBooks(state, payload) {

        state.books = payload.map((book) => {

            return {
                id: book.id,
                code: book.code,
                title: book.title,
                category: book.category,
                purchase_type: book.type,
                balance: book.balance,
                added_on: new Date(book['created_at'])
            }

        })

    },

    getBook(state, payload) {

        state.book = {
            id: payload.id,
            code: payload.code,
            type: payload.type,
            title: payload.title,
            author: payload.author,
            alternate_title: payload.alternate_title,
            category: payload.category,
            balance: payload.balance,
            supplier: payload.supplier,
            transactions: payload.transactions?.map((transaction) => {
                return {
                    id: transaction.id,
                    invoice: transaction.invoice,
                    quantity: transaction.quantity,
                    type: transaction.type,
                    date: new Date(transaction['transaction_on']),
                }
            }),
            created_at: payload['created_at'],
            updated_at: payload['updated_at']
        }

    },

    getBooksStats(state, payload) {

        state.statistics = payload

    },

    destroyBook(state, payload) {
        state.book = null
    }

}

export default { state, getters, actions, mutations }
