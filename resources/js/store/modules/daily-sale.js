const state = {
  unsubmitted: null
}

const getters = {}

const actions = {
  setUnsubmitted(context, payload) {
    context.commit('setUnsubmitted', payload)
  }
}

const mutations = {
  setUnsubmitted(state, payload) {
    state.unsubmitted = payload
  }
}

export default { state, getters, actions, mutations }
