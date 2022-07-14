<template>
  <v-app>
    <v-app :id="id">
      <template v-if="$auth.loggedIn">
        <v-app-bar
          app
          clipped-left
          color="primary"
          dark
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
            <app-avatar :background="false" :name="$auth.user.name" size="30px"/>
          </v-btn>

          <v-btn :text="true" icon @click="logout">
            <v-icon>mdi-power</v-icon>
          </v-btn>
        </v-app-bar>

        <v-navigation-drawer id="sidebar" :mini-variant="drawer" app dark permanent clipped>
          <div v-for="(submenu, key) in $menus.main" :key="key">
            <v-list nav dense>
              <v-list-item
                v-for="(item, index) in submenu"
                :key="index"
                :to="item.to"
              >
                <v-list-item-title>
                  <v-icon>
                    {{ item.icon }}
                  </v-icon>
                  {{ item.title }}
                </v-list-item-title>
              </v-list-item>
            </v-list>
            <v-divider v-if="key + 1 != $menus.main.length"></v-divider>
          </div>
        </v-navigation-drawer>
      </template>
      <v-main>
        <nuxt/>
      </v-main>
    </v-app>
    <vue-toast-group/>
  </v-app>
</template>

<script>

export default {
  name: 'BaseLayout', data() {
    return {
      n: 0,
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
    id() {
      return this.$auth.loggedIn ? 'main' : 'login'
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

<style scoped lang="scss">
#login {
  height: 50%;
  width: 100%;
  position: absolute;
  top: 0;
  left: 0;
  content: '';
  z-index: 1;
  background: #cdcdcd url('/bar.jpg') no-repeat center;
  background-size: cover;

  &::before {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    content: '';
    background: rgba(0, 0, 0, 0.3);
  }
}
</style>
