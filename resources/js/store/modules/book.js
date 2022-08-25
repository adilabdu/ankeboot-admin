import axios from "axios";
import { formatDate } from "../../utils"

const state = {

    books: null,
    book: null,
    statistics: null,
    loading: false,

}

const getters = {

}

const actions = {

    setLoadingState(context, payload) {
        context.commit('setLoadingState', payload)
    },

    async getBooks({ commit }) {

        await axios.get('/api/books').then((response) => {
            commit('getBooks', response.data.data)
        })

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

    async getBook({ commit }, payload) {

        await axios.get('/api/books/', {
            params: {
                id: payload
            }
        }).then((response) => {
            commit('getBook', response.data.data)
        })

    },

    async getBooksStats({ commit }) {

        await axios.get('/api/books/statistics').then((response) => {
            commit('getBooksStats', response.data.data)
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
            category: payload.category,
            balance: payload.balance,
            supplier: payload.supplier,
            transactions: payload.transactions.map((transaction) => {
                return {
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
