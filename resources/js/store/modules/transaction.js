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

        console.log('Running getBooks actions from book store')
        await axios.get('/api/transactions').then((response) => {
            commit('getTransactions', response.data.data)
        })

    },

    async getTransactionsStats({ commit }) {

        await axios.get('/api/transactions/stats').then((response) => {
            commit('getTransactionsStats', response.data.data)
        })

    }

}

const mutations = {

    getTransactions(state, payload) {

        state.transactions = payload.map((transaction) => {

            console.log({
                invoice: transaction.invoice,
                book_id: transaction.book.id,
                book_code: transaction.book.code,
                book_title: transaction.book.title,
                transaction_type: transaction.type,
                quantity: transaction.quantity,
                transaction_date: new Date(transaction['transaction_on']),
            })

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
