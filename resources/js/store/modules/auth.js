import axios from "axios";

const state = {

    isAuthenticated: false,
    user: {},

}

const actions = {

    setUser(context, payload) {
        context.commit('setUser', payload)
    },

    destroyUser(context, payload) {
        context.commit('destroyUser')
    },

    getAuthenticatedUser(context, payload) {
        context.commit('getAuthenticatedUser')
    }

}

const mutations = {

    setUser(state, payload) {
        state.user = payload
        state.isAuthenticated = true
    },

    destroyUser(state, payload) {
        state.user = {}
        state.isAuthenticated = false
    },

    getAuthenticatedUser(state, payload) {

        axios.get('/api/user').then(response => {

            state.user = response.data
            state.isAuthenticated = true

        }).catch(error => {

            state.user = {}
            state.isAuthenticated = false

        })

    }

}

export default { state, actions, mutations }
