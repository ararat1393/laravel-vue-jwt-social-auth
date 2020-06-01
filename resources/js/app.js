require('./bootstrap');
require('./helper/functions');

import Vue from 'vue'
import App from './App.vue';
import axios from 'axios'
import VueAxios from 'vue-axios';
import VueAuth from '@websanova/vue-auth'
import VueRouter from 'vue-router'

import auth from './config/auth'
import router from './config/routes'
import vuetify from './config/vuetify' // path to vuetify export

import store from './store/store'

// Set Vue globally
window.Vue = Vue

// Set Vue router
Vue.router = router
Vue.use(VueRouter)

Vue.use(VueAxios, axios);
axios.defaults.baseURL = process.env.MIX_BASE_URL;

Vue.use(VueAuth, auth)

const app = new Vue({
    el: '#app',
    router,
    store,
    vuetify,
    render: h => h(App),
});
