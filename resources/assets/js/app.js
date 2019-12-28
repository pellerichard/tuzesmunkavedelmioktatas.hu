require('./bootstrap');

import Vue from 'vue'
import Vuetify from 'vuetify'
import VueRouter from 'vue-router'
import VueCookies from 'vue-cookies'
import VueEditor from "vue2-editor";
import Notifications from 'vue-notification'
import VueVideoPlayer from 'vue-video-player'
import VeeValidate from 'vee-validate';
import 'video.js/dist/video-js.css'
import 'babel-polyfill'
import App from './App.vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faCoffee } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import huLang from "./languages/hu.js"


library.add(faCoffee)

Vue.component('font-awesome-icon', FontAwesomeIcon)

Vue.config.productionTip = false

Vue.use(VueRouter);
Vue.use(VueCookies);
Vue.use(VueEditor);
Vue.use(Notifications);
Vue.use(Vuetify);
Vue.use(VueVideoPlayer)

Vue.use(VeeValidate, {
    locale: 'hu',
    dictionary: {
        'hu': huLang
    }
});

import { router } from './router.js';
import store from './store.js';

import 'vuetify/dist/vuetify.min.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'

new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App)
}).$mount('#app');