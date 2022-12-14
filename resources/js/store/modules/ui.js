const state = {

    navigationOpened: false,
    contentLoading: false,

}

const getters = {

}

const actions = {

    toggleNavigation(context, payload) {

        context.commit('toggleNavigation', payload)

    },

    toggleContentLoading(context, payload) {

        context.commit('toggleContentLoading', payload)

    }

}

const mutations = {

    toggleNavigation(state, payload) {

        if (payload) {
            state.navigationOpened = payload
            return
        }

        state.navigationOpened = !state.navigationOpened

    },

    toggleContentLoading(state, payload) {

        state.contentLoading = payload

    }

}

export default { state, getters, actions, mutations }
