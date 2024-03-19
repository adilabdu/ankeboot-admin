const state = {
  navigationOpened: false,
  contentLoading: false,
  minimizeNavigation: false,
  pageLoading: {
    status: false,
    percentage: 100
  }
}

const getters = {}

const actions = {
  togglePageLoading(context, payload) {
    context.commit('startLoading', payload)
  },

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
  },

  startLoading(state, payload) {
    let load = null

    if (payload) {
      state.pageLoading.percentage = 100

      load = setInterval(() => {
        state.pageLoading.percentage = state.pageLoading.percentage * 0.85
      }, 1000)
    } else {
      state.pageLoading.percentage = 0
      clearInterval(load)
    }
  }
}

export default { state, getters, actions, mutations }
