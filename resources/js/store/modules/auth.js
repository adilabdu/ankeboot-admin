import axios from "axios"

const state = {
    user: {},
    isAuthenticated: false,
    isLoading: false,
    error: null
}

const getters = {

    getUser() {
        return state.user
    },

    getAuthStatus() {
        return state.isAuthenticated
    },

    getLoadingStatus() {
        return state.isLoading
    },

    getError() {
        return state.error
    }

}

const actions = {

    async login(context, credentials) {
        context.commit("loading", true)
        try {
            await axios.get("/sanctum/csrf-cookie")
            await axios.post("/api/auth/login", credentials)
                .then(response => {
                    context.commit("login", response.data.data.user)
                })
                .catch(error => {
                    context.commit("error", error)
                })
                .finally(() => context.commit("loading", false))
        }
        catch {
            context.commit("loading", false)
        }
    },

    async logout(context) {
        context.commit("loading", true)
        await axios.post("/api/auth/logout")
            .then(response => {
                context.commit("logout")
            })
            .catch(error => {
                context.commit("error", error)
            })
            .finally(() => context.commit("loading", false))
    },

    async getUser(context) {
        context.commit("loading", true)
        await axios.get("/api/user")
            .then(response => {
                context.commit("login", response.data)
            })
            .catch(error => {
                // context.commit("error", error)
            })
            .finally(() => context.commit("loading", false))
    }

}

const mutations = {

    login: (state, payload) => {
        state.isAuthenticated = true
        state.user = payload
        state.error = false
    },

    logout: (state) => {
        state.user = null
        state.isAuthenticated = false
        state.error = null
    },

    error: (state, payload) => {
        state.error = payload
    },

    loading: (state, payload) => {
        state.isLoading = payload
    }

}

export default {
    state, getters, actions, mutations
}
