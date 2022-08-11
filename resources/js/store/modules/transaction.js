import axios from "axios";

const state = {

    transactions: null,
    transaction: null,
    statistics: null

}

const getters = {

}

const actions = {

    async getTransactions({ commit }) {

        await axios.get('/api/transactions').then((response) => {
            commit('getTransactions', response.data.data)
        })

    },

    async getTransactionsStats({ commit }) {

        await axios.get('/api/transactions/stats').then((response) => {
            commit('getTransactionsStats', response.data.data)
        })

    },

    async postTransaction(context, payload) {

        const transaction_on =
            `${parseInt(payload.date.date).toLocaleString('en-US', { minimumIntegerDigits: 2 })}-${(parseInt(payload.date.month) + 1).toLocaleString('en-US', { minimumIntegerDigits: 2 })}-${payload.date.year}`

        return axios.post('/api/transactions', {
            invoice: payload.invoice,
            transaction_on: transaction_on,
            book_id: payload.book_id,
            type: payload.type ? 'purchase' : 'sale',
            quantity: payload.quantity,
        }).then((response) => {
            return response.data
        }).catch((error) => {
            return error.response.data.message
        })

    },

    async postMultipleTransactions({ commit, dispatch }, payload) {

        dispatch('setLoadingState', true)

        return axios.post('/api/transactions/csv', {
            file: payload.file
        }, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
            .then((response) => {
                return response.data
            }).catch((error) => {
                return error.response.data
            }).finally(() => {
                dispatch('setLoadingState', false)
            })
    },

}

const mutations = {

    getTransactions(state, payload) {

        state.transactions = payload.map((transaction) => {

            return {
                invoice: transaction.invoice,
                book_id: transaction.book.id,
                book_code: transaction.book.code,
                book_title: transaction.book.title,
                transaction_type: transaction.type,
                quantity: transaction.quantity,
                transaction_date: new Date(transaction['transaction_on']),
            }

        })

    },

    getTransactionsStats(state, payload) {

        state.statistics = payload

    }

}

export default { state, getters, actions, mutations }
