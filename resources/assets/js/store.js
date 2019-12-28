import Vue from 'vue'
import Vuex from 'vuex'
import VueRouter from 'vue-router'
import VueCookies from 'vue-cookies'
import Notifications from 'vue-notification'

import { router } from './router.js'

Vue.use(VueRouter);
Vue.use(VueCookies);
Vue.use(Notifications);

import error from './modules/error.js'
import account from './modules/account.js'
import test from './modules/test.js'
import company from './modules/company.js'
import group from './modules/group.js'

export default new Vuex.Store({
    modules: {
        account,
        test,
        company,
        group,
        error
    },
    state: {
    },
    getters: {
    },
    mutations: {
    }
});