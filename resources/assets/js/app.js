import Vue from 'vue'
import VueRouter from 'vue-router'
import axios from 'axios'
import BootstrapVue from 'bootstrap-vue'
import VueWait from 'vue-wait'
import Snotify from 'vue-snotify'
import VTooltip from 'v-tooltip'
import VueScrollTo from 'vue-scrollto'
import 'vue-awesome/icons'
import Icon from 'vue-awesome/components/Icon'
import { routes } from './routes'
import store from './store/store'
import App from './App.vue'

Vue.use(VueRouter)
Vue.use(BootstrapVue)
Vue.use(VueWait)
Vue.use(Snotify, {
    toast: {
        timeout: 3000,
        titleMaxLength: 64,
        bodyMaxLength: 512
    }
})
Vue.use(VTooltip)
Vue.use(VueScrollTo)

Vue.component('icon', Icon)

axios.defaults.baseURL = 'http://newcanteen.loc/api'

Vue.filter('withLeadingZero', value => value.toString().length === 1 ? `0${value}` : value)
Vue.filter('moneyOutput', value => value / 100)

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')

window.Vue = require('vue')

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const router = new VueRouter({
    mode: 'history',
    routes
})

const wait = new VueWait({
    useVuex: true,
    vuexModuleName: 'vuex-loading-module',
    accessorName: '$l'
})

const app = new Vue({
    el: '#app',
    router,
    store,
    wait,
    render: h => h(App)
})
