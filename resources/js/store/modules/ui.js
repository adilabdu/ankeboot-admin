const state = {

    navigationOpened: false,
    contentLoading: false,
    minimizeNavigation: false

}

const getters = {

}

const actions = {

    toggleNavigation(context, payload) {

        context.commit('toggleNavigation', payload)

    },

    toggleNavigationMinimize(context, payload) {

        context.commit('toggleNavigationMinimize', payload)

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

    toggleNavigationMinimize(state, payload) {

        if (payload) {
            state.minimizeNavigation = payload
            return
        }

        state.minimizeNavigation = !state.minimizeNavigation

    },

    toggleContentLoading(state, payload) {

        state.contentLoading = payload

    }

}

export default { state, getters, actions, mutations }
