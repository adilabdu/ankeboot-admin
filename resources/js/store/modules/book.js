import axios from "axios";

const state = {

    books: null,
    book: null,
    statistics: null

}

const getters = {

}

const actions = {

    async getBooks({ commit }) {

        console.log('Running getBooks actions from book store')
        await axios.get('/api/books').then((response) => {
            commit('getBooks', response.data.data)
        })

    },

    async getBook({ commit }, payload) {

        await axios.get('/api/books/', {
            params: {
                id: payload
            }
        }).then((response) => {
            console.log('response from getBook actions ', response.data.data)
            commit('getBook', response.data.data)
        })

    },

    async getBooksStats({ commit }) {

        await axios.get('/api/books/stats').then((response) => {
            commit('getBooksStats', response.data.data)
        })

    }

}

const mutations = {

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

        state.book = payload

    },

    getBooksStats(state, payload) {

        state.statistics = payload

    }

}

export default { state, getters, actions, mutations }
