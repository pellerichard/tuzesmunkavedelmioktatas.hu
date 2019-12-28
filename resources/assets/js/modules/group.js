import store from '../store.js';
import Vue from 'vue';

export default {
    namespaced: true,
    state: {
        groups: {}
    },
    getters: {
        fetchGroup: (state) => { return state.groups }
    },
    mutations: {
    },
    actions: {
        store(context, input) {
            axios.post('/felhasznaloi-csoportok', input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    console.log('group.js -> store')
                    /**fetchGroup
                     * @ A cég real-time frissítése.
                     */
                    context.dispatch('fetchGroup', {
                        companyId: input.companyId
                    });
                })
                .catch(error => {
                    console.log(error)
                });
        },
        update(context, input) {
            axios.put('/felhasznaloi-csoportok/' + input.group.id, input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    console.log('group.js -> update')
                    /**
                     * @ A cég real-time frissítése.
                     */
                    context.dispatch('fetchGroup', {
                        companyId: input.companyId
                    });
                })
                .catch(error => {
                    console.log(error)
                });
        },
        destroy(context, input) {
            axios.delete('/felhasznaloi-csoportok/' + input.group.id, input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    console.log('group.js -> destroy')
                    /**
                     * @ A cég real-time frissítése.
                     */
                    context.dispatch('fetchGroup', {
                        companyId: input.companyId
                    });
                })
                .catch(error => {
                    console.log(error)
                });
        },
        fetchGroup(context, input) {
            console.log('fetchGroup - Actual')
            console.log(input)

            axios.post('/felhasznaloi-csoportok/csoportok/' + ((typeof input.companyId!=="undefined") ? input.companyId : input.companyRef), input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    console.log('fetchGroup')
                    console.log(response.data.groups)
                    context.state.groups = response.data.groups;
                })
                .catch(error => {
                    console.log(error)
                });
        },
        resetGroup(context) {
            console.log('resetGroup')
            context.state.groups = {};
        }
    }
};