const state = {
  alerts: [],
  persistingAlerts: 0
}

const getters = {}

const actions = {
  pushAlert(context, payload) {
    context.commit('pushAlert', payload)
  },

  popAlert(context, payload) {
    context.commit('popAlert')
  }
}

const mutations = {
  pushAlert(state, payload) {
    state.alerts.unshift(payload)
  },

  popAlert(state) {
    state.alerts.pop()
  }
}

export default { state, getters, actions, mutations }
