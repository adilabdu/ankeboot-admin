import axios from "axios";
import router from "../../router"
import { formatDate } from "../../utils"

const state = {

    files: [],
    isLoggedIn: true,
    controller: new AbortController()

}

const actions = {

    setLoadingState(context, payload) {
        context.commit('setLoadingState', payload)
    },

    setLoggedInState(context, payload) {
        context.commit('setLoggedInState', payload)
    },

    async getDriveFiles({ commit }) {

        axios.get('/api/google/drive/files', {
            signal: state.controller.signal
        })
            .then((response) => {
                commit('getDriveFiles', response.data.data)
            })
            .catch((error) => {
                alert(`Error with getDriveFiles ${JSON.stringify(error)}`)
                commit('setLoggedInState', false)
            })

    },

    cancelGoogleRequest() {

        state.controller.abort()
        state.controller = new AbortController()

    },

}

const mutations = {

    setLoadingState(state, payload) {
        state.loading = payload
    },

    setLoggedInState(state, payload) {

        state.isLoggedIn = payload

    },

    getDriveFiles(state, payload) {

        state.files = payload.files

    },

}

export default { state, actions, mutations }
