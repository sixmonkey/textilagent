<template>
  <v-app :style="{ background: $vuetify.theme.themes[theme].background }">
    <v-app-bar
      app
      clipped-left
      color="primary"
      dark
      dense
      src="/background.jpg"
    >
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>

      <v-toolbar-title>
        <v-icon>mdi-{{ icon }}</v-icon>
        {{ title }}
      </v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn icon>
        <v-icon>mdi-magnify</v-icon>
      </v-btn>

      <v-btn icon>
        <ui-avatar :background="false" :name="$auth.user.name" size="30px" />
      </v-btn>

      <v-btn :text="true" icon @click="logout">
        <v-icon>mdi-power</v-icon>
      </v-btn>
    </v-app-bar>

    <v-navigation-drawer :mini-variant="drawer" app clipped dark permanent>
      <menu-list :menu="$menus.main" />
    </v-navigation-drawer>

    <v-main>
      <v-container fluid class="pa-10 align-baseline fill-height">
        <nuxt />
      </v-container>
    </v-main>

    <v-footer dark padless>
      <v-col class="text-center text-caption text-no-wrap" cols="12">
        &copy;{{ new Date().getFullYear() }} —
        <strong>{{ $config.app.title }}</strong>
      </v-col>
    </v-footer>
  </v-app>
</template>

<script>
export default {
  name: 'DefaultLayout',
  data() {
    return {
      drawer: true,
      fixed: false,
    }
  },
  computed: {
    title() {
      return this.$route.meta.title ?? 'Unknown Title'
    },
    icon() {
      return this.$route.meta.icon ?? 'help-circle'
    },
    theme() {
      return this.$vuetify.theme.dark ? 'dark' : 'light'
    },
  },
  methods: {
    async logout() {
      await this.$auth.logout()
      this.$toast.success('Successfully logged out. Bye bye!')
      await this.$router.push('/login')
    },
  },
}
</script>
