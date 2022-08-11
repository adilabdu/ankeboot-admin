const state = {

    navigationOpened: false

}

const getters = {

}

const actions = {

    toggleNavigation(context, payload) {

        context.commit('toggleNavigation')

    },

}

const mutations = {

    toggleNavigation(state, payload) {

        state.navigationOpened = !state.navigationOpened

    },

}

export default { state, getters, actions, mutations }
