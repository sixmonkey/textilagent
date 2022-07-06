export const state = () => ({
  units: null,
  currencies: null,
})

export const actions = {
  async nuxtServerInit({ commit }) {
    if (this.$auth.loggedIn) {
      const { data: units } = await this.$axios.get('/units')
      commit('SET_UNITS', units)
      const { data: currencies } = await this.$axios.get('/currencies')
      commit('SET_CURRENCIES', currencies)
    }
  },
}

export const mutations = {
  SET_UNITS(state, data) {
    state.units = data
  },
  SET_CURRENCIES(state, data) {
    state.currencies = data
  },
}
