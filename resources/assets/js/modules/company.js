import store from '../store.js';
import Vue from 'vue';

/**
 * @Modules Implementáció
 *
 * @Erre alapozva alakítsd át a getters: metódust, továbbá írd vissza az összes metódusnál a (state, input)-ot, (context, input)-ra, majd a Main.vue-t lesd meg.
 *
 * @lastModDate "2018.11.24 2:03"
 */

export default {
    namespaced: true,
    state: {
        companies: {},
        company: {}
    },
    getters: {
        fetchCompanies: (state) => { return state.companies },
        fetchCompany: (state) => { return state.company }
    },
    mutations: {
    },
    actions: {
        fetchCompanies(context, input) {
            axios.post('/felhasznalo/fetchCompanies', input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    console.log('fetchCompanies')
                    context.state.companies = response.data.companies;
                    console.log(context.state.companies)
                })
                .catch(error => {
                    console.log(error)
                });
        },
        fetchCompany(context, input, filter) {
            axios.post('/felhasznalo/fetchCompany', {data: input, filter: filter}, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    console.log('fetchCompany');
                    console.log(response.data.company);
                    context.state.company = response.data.company;
                    context.state.groups = response.data.groups;
                    context.state.company._filter = response.data.filter;
                    console.log(context.state.groups)
                })
                .catch(error => {
                    console.log(error)
                });
        },
        addCompanyMember(context, input) {
            return new Promise((resolve, reject) => {
                axios.post('/felhasznalo/cegeim/uj-munkatars', input, {headers: {'Content-type': 'application/x-www-form-urlencoded'}}).then(response => {
                    if (response.data.success) {
                        console.log('addCompanyMember')
                        console.log(context.account)

                        /**
                         * @ A cég real-time frissítése.
                         */

                        this.dispatch('error/notify', {
                            type: 'success',
                            title: 'Értesítés:',
                            msg: response.data.msg
                        });

                        context.dispatch('fetchCompany', {
                            fetchAll: input.fetchAll,
                            companyId: input.companyId
                        });
                        resolve(response.data)
                    } else {
                        if (!response.data.validator) {
                            this.dispatch('error/notify', {
                                type: 'error',
                                title: 'Hiba:',
                                msg: response.data.msg
                            });
                        }
                        resolve(response.data)
                    }
                }, error => {
                    console.log(error)
                    reject(error)
                });
            });
        },
        deleteCompanyMember(context, input) {
            axios.post('/felhasznalo/cegeim/torles-munkatars', input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    console.log('deleteCompanyMember')
                    console.log(context.account)

                    /**
                     * @ A cég real-time frissítése.
                     */
                    context.dispatch('fetchCompany', {
                        fetchAll: input.fetchAll,
                        companyId: input.companyId
                    });
                })
                .catch(error => {
                    console.log(error)
                });
        },
        editCompanyMember(context, input) {
            axios.post('/felhasznalo/cegeim/szerkesztes-munkatars', input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    console.log('editCompanyMember')
                    console.log(context.account)

                    /**
                     * @ A cég real-time frissítése.
                     */
                    context.dispatch('fetchCompany', {
                        fetchAll: input.fetchAll,
                        companyId: input.companyId
                    });
                })
                .catch(error => {
                    console.log(error)
                });
        },
        addCompany(context, input) {
            return new Promise((resolve, reject) => {
                axios.post('/admin/ceg', input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }}).then(response => {
                    if(response.data.success) {
                        console.log('addCompany')
                        console.log(response.data)

                        this.dispatch('error/notify', {
                            type: 'success',
                            title: 'Értesítés:',
                            msg: 'Sikeresen létrehoztad a(z) ' + input.company.name + ' nevű céget!'
                        });

                        /**
                         * @ A cég real-time frissítése.
                         */
                        context.dispatch('fetchCompanies', {
                            fetchAll: true
                        });
                        resolve(response.data)
                    } else {
                        if(!response.data.validator) {
                            this.dispatch('error/notify', {
                                type: 'error',
                                title: 'Hiba:',
                                msg: response.data.msg
                            });
                        }
                        resolve(response.data)
                    }
                }, error => {
                    console.log(error)
                    reject(error)
                });
            });
        },
        editCompany(context, input) {
            axios.put('/admin/ceg/' + input.company.id, input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    console.log('editCompany')
                    console.log(response)
                    /**
                     * @ A cég real-time frissítése.
                     */
                    context.dispatch('fetchCompanies', {
                        fetchAll: true
                    });
                })
                .catch(error => {
                    console.log(error)
                });
        },
        deleteCompany(context, input) {
            axios.delete('/admin/ceg/' + input.company.id, input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    console.log('deleteCompany')
                    console.log(response)
                    /**
                     * @ A cég real-time frissítése.
                     */
                    context.dispatch('fetchCompanies', {
                        fetchAll: true
                    });
                })
                .catch(error => {
                    console.log(error)
                });
        },
    }
};