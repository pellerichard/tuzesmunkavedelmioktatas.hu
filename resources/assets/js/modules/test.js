import store from '../store.js';
import { router } from '../router.js';
import Vue from 'vue';

export default {
    namespaced: true,
    state: {
        tests: {},
        test: {},
        question: {}
    },
    getters: {
        fetchTests: (state) => { return state.tests },
        fetchTest: (state) => { return state.test },
        fetchQuestion: (state) => { return state.question }
    },
    mutations: {
    },
    actions: {
        sendTest(context, input) {
            axios.post('/felhasznalo/sendTest', input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    if(response.data.success) {
                        console.log('sendTest');
                        context.dispatch('fetchQuestion', null);
                    } else {
                        alert('Nincsenek kérdéseid!')
                    }
                })
                .catch(error => {

                });
        },
        fetchQuestion(context, input) {
            axios.post('/felhasznalo/fetchQuestion', input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    if(response.data.success && response.data.data) {
                        console.log('fetchQuestion');
                        console.log('response.data.data - Van kérdésed')
                        context.state.question = response.data.data;
                    } else {
                        console.log('fetchQuestion');
                        console.log('response.data.data - Nincs kérdésed')
                        context.state.question = response.data;
                    }
                })
                .catch(error => {
                    console.log(error)
                });
        },
        addTest(context, input) {
            axios.post('/admin/teszt', input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    console.log('addTest')
                    console.log(response)
                    /**
                     * @ A teszt real-time frissítése.
                     */
                    context.dispatch('fetchTests',
                        null
                    );
                })
                .catch(error => {
                    console.log(error)
                });
        },
        fetchTest(context, input) {
            axios.post('/admin/teszt/fetchTest', input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    console.log('fetchTest')
                    console.log(response.data.test)
                    context.state.test = response.data.test;
                })
                .catch(error => {
                    console.log(error)
                });
        },
        fetchTests(context, input) {
            axios.post('/admin/teszt/fetchTests', input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    console.log('fetchTests')
                    console.log(response.data.tests)
                    context.state.tests = response.data.tests;
                })
                .catch(error => {
                    console.log(error)
                });
        },
        editTest(context, input) {
            axios.put('/admin/teszt/' + input.test.id, input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    console.log('editTest')
                    console.log(response)
                    /**
                     * @ A teszt real-time frissítése.
                     */
                    context.dispatch('fetchTests',
                        null
                    );
                })
                .catch(error => {
                    console.log(error)
                });
        },
        deleteTest(context, input) {
            axios.delete('/admin/teszt/' + input.test.id, input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    console.log('deleteTest')
                    console.log(response)
                    /**
                     * @ A teszt real-time frissítése.
                     */
                    context.dispatch('fetchTests',
                        null
                    );
                })
                .catch(error => {
                    console.log(error)
                });
        },
        addTestInput(context, input) {
            axios.post('/admin/testinput', input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    console.log('addTestInput')
                    console.log(response.data)

                    /**
                     * @ A teszt real-time frissítése.
                     */
                    context.dispatch('fetchTest', {
                        testId: router.currentRoute.params.id
                    });
                })
                .catch(error => {
                    console.log(error)
                });
        },
        deleteTestInput(context, input) {
            axios.delete('/admin/testinput/' + input.testinput.id, input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    console.log('deleteTestInput')
                    console.log(response)

                    /**
                     * @ A teszt real-time frissítése.
                     */
                    context.dispatch('fetchTest', {
                        testId: router.currentRoute.params.id
                    });
                })
                .catch(error => {
                    console.log(error)
                });
        },
        editTestInput(context, input) {
            axios.put('/admin/testinput/' + input.testinput.id, input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    console.log('editTestInput')
                    console.log(response)

                    /**
                     * @ A teszt real-time frissítése.
                     */
                    context.dispatch('fetchTest', {
                        testId: router.currentRoute.params.id
                    });
                })
                .catch(error => {
                    console.log(error)
                });
        }
    }
};