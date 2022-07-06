<template>
  <v-app>
    <vue-toast-group name="default" />
    <v-component :is="layout" />
  </v-app>
</template>

<script>
import DefaultLayout from './app'
import BlankLayout from './blank'

export default {
  name: 'BaseLayout',
  components: { DefaultLayout, BlankLayout },
  computed: {
    layout() {
      return this.$auth.loggedIn ? 'DefaultLayout' : 'BlankLayout'
    },
  },
}
</script>
