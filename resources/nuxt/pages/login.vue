<template>
    <v-container fill-height>
      <v-layout align-center justify-center>
        <v-flex lg4 md5 sm8 xs12>
          <v-card class="elevation-1 pa-3">
            <app-form
              ref="form"
              autocomplete="off"
              @submit.prevent="login"
            >
              <v-card-text>
                <div class="layout column align-center">
                  <h1 class="flex my-4 primary--text">
                    {{ $config.app.title }}
                  </h1>
                </div>
                <app-form-input
                  v-model="credentials.email"
                  append-icon="mdi-account"
                  auto
                  label="Login"
                  type="text"
                  rules="required|email"
                  name="email"
                />
                <app-form-input
                  v-model="credentials.password"
                  append-icon="mdi-lock"
                  label="Password"
                  type="password"
                  rules="required"
                  name="password"
                />
              </v-card-text>
              <v-card-actions>
                <v-btn :loading="loading" block color="primary" type="submit">
                  Login
                </v-btn>
              </v-card-actions>
            </app-form>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
</template>

<script>
export default {
  name: 'AuthLogin',
  layout: 'blank',
  data: () => ({
    loading: false,
    error: '',
    errors: {
      email: [],
      password: [],
    },
    credentials: {
      email: '',
      password: '',
    },
  }),
  methods: {
    async login() {
      if (!await this.$refs.form.validate()) return

      this.loading = true
      try {
        await this.$auth.loginWith('laravelSanctum', {
          data: this.credentials,
        })
        try {
          // await this.$store.dispatch('nuxtServerInit')
        } catch (e) {
          this.$toast.error(e.message)
        }
        this.$toast.success(
          `Successfully logged in. Welcome ${this.$auth.user.name}!`
        )
      } catch (error) {
        this.loading = false

        this.$refs.form.setErrors(error.response?.data?.errors)

        this.$toast.error(error.response?.data?.message ?? error.message)
      }
    },
  },
}
</script>
