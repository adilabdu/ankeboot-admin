const state = {

    alerts: [],
    persistingAlerts: 0

}

const getters = {

}

const actions = {

    pushAlert(context, payload) {

        context.commit('pushAlert', payload)

    }

}

const mutations = {

    pushAlert(state, payload) {

        state.alerts.unshift(payload)
        setTimeout(() => {
            state.alerts.pop()
        }, 5000)

    }

}

export default { state, getters, actions, mutations }
