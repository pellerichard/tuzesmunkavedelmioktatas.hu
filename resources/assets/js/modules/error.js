import store from '../store.js';
import Vue from 'vue';

export default {
    namespaced: true,
    state: {
        error: {}
    },
    getters: {
        fetchError: (state) => { return state.error }
    },
    mutations: {
    },
    actions: {
        notify(context, payload) {
            Vue.prototype.$notify({
                group: 'notify',
                title: payload.title,
                text: payload.msg,
                type: payload.type,
            });
        }
    }
};