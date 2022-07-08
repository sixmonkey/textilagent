import colors from 'vuetify/es5/util/colors'
import menus from './resources/nuxt/config/menus'
import app from './resources/nuxt/config/app'

export default {
    // Target: https://go.nuxtjs.dev/config-target
    target: 'static',

    // srcDir: https://nuxtjs.org/docs/configuration-glossary/configuration-srcdir/
    srcDir: 'resources/nuxt',

    // Global page headers: https://go.nuxtjs.dev/config-head
    head: {
        titleTemplate: '%s - textilexchange',
        title: 'textilexchange',
        meta: [
            {charset: 'utf-8'},
            {name: 'viewport', content: 'width=device-width, initial-scale=1'},
            {hid: 'description', name: 'description', content: ''},
            {name: 'format-detection', content: 'telephone=no'}
        ],
        link: [
            {rel: 'icon', type: 'image/x-icon', href: '/favicon.ico'}
        ]
    },

    // Global CSS: https://go.nuxtjs.dev/config-css
    css: [],

    // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
    plugins: [
        '~/plugins/menuHelper.js',
        '~/plugins/colorHelper.js',
        '~/plugins/pluralHelper.js',
        '~/plugins/vendor/vuetify-toast.js',
        '~/plugins/vendor/vee-validate.js',
    ],

    // Auto import components: https://go.nuxtjs.dev/config-components
    components: [
        {path: '~/components', prefix: 'app'},
        {path: '~/components/form', prefix: 'app-form'},
    ],

    // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
    buildModules: [
        // https://go.nuxtjs.dev/eslint
        '@nuxtjs/eslint-module',
        // https://go.nuxtjs.dev/stylelint
        '@nuxtjs/stylelint-module',
        // https://go.nuxtjs.dev/vuetify
        '@nuxtjs/vuetify',
    ],

    // Modules: https://go.nuxtjs.dev/config-modules
    modules: [
        // https://go.nuxtjs.dev/axios
        '@nuxtjs/axios',
        // https://go.nuxtjs.dev/pwa
        '@nuxtjs/pwa',
        // https://go.nuxtjs.dev/content
        '@nuxt/content',
        // https://auth.nuxtjs.org/
        '@nuxtjs/auth-next',
        // https://github.com/dword-design/nuxt-route-meta
        'nuxt-route-meta',
        // https://github.com/patrickcate/nuxt-jsonapi
        'nuxt-jsonapi',

    ],

    // Axios module configuration: https://go.nuxtjs.dev/config-axios
    axios: {
        // Workaround to avoid enforcing hard-coded localhost:3000: https://github.com/nuxt-community/axios-module/issues/308
        baseURL: 'http://localhost:8888/api',
        credentials: true,
    },

    // https://github.com/patrickcate/nuxt-jsonapi
    jsonApi: {
        baseURL: 'http://localhost:8888/api',
        axiosOptions: {
            withCredentials: true,
        }
    },

    // PWA module configuration: https://go.nuxtjs.dev/pwa
    pwa: {
        manifest: {
            lang: 'en'
        }
    },

    // Content module configuration: https://go.nuxtjs.dev/config-content
    content: {},

    // Vuetify module configuration: https://go.nuxtjs.dev/config-vuetify
    theme: {
        dark: false,
        themes: {
            options: {
                customProperties: true,
            },
            light: {
                primary: colors.blue.darken2,
                accent: colors.grey.darken3,
                secondary: colors.amber.darken3,
                info: colors.teal.lighten1,
                warning: colors.amber.base,
                error: colors.deepOrange.accent4,
                success: colors.green.accent4,
                background: colors.grey.lighten4,
            },
            dark: {
                primary: colors.blue.darken2,
                accent: colors.grey.darken3,
                secondary: colors.amber.darken3,
                info: colors.teal.lighten1,
                warning: colors.amber.base,
                error: colors.deepOrange.accent4,
                success: colors.green.accent3,
                background: colors.grey.darken3,
            },
        },
    },
    router: {
        middleware: ['auth'],
        parseQuery: (query) => {
            return require('qs').parse(query)
        },
        stringifyQuery: (query) => {
            const r = require('qs').stringify(query)
            return r ? '?' + r : ''
        },
    },
    auth: {
        strategies: {
            laravelSanctum: {
                provider: 'laravel/sanctum',
                // url: 'http://165.227.135.24'
                url: 'http://localhost:8888'
            },
        },
        watchLoggedIn: true,
    },
    publicRuntimeConfig: {
        menus,
        app,
    },
    privateRuntimeConfig: {},

    // Build Configuration: https://go.nuxtjs.dev/config-build
    build: {
        transpile: ['vee-validate', 'vue-underscore'],
    }
}
