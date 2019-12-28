import store from '../store.js';
import Vue from 'vue';

export default {
    namespaced: true,
    state: {
        loggedIn: false,
        account: {},
        error: {}
    },
    getters: {
        isLoggedIn: (state) => { return state.loggedIn },
        fetchAccount: (state) => { return state.account }
    },
    mutations: {
    },
    actions: {
        isLoggedIn(context) {
            if(window.$cookies.isKey("account")!=false) { // Ha be van lépve
                context.state.loggedIn = 1
            }
            axios.post('/felhasznalo/isLoggedIn', null, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    console.log(response)
                    console.log('store.js => isLoggedIn() => ' + response.data.loggedIn);
                    context.state.loggedIn = response.data.loggedIn;
                })
                .catch(error => {
                    console.log(error)
                })
        },
        sendLogin(context, input) {
            axios.post('/felhasznalo/login', input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    if(response.data.success && response.data.expireTime && response.data.account) {
                        context.state.loggedIn = true;
                        localStorage.setItem('permission', response.data.role);
                        localStorage.setItem('expireTime', response.data.expireTime);

                        this.dispatch('error/notify', {
                            type: 'success',
                            title: 'Értesítés:',
                            msg: 'Sikeresen bejelentkeztél a felhasználódba!'
                        });

                        if(response.data.role==1) {
                            window.location.href = '/teszt';
                        } else if(response.data.role==2) {
                            window.location.href = '/';
                        } else if(response.data.role==3) {
                            window.location.href = '/admin/cegek';
                        } else {
                            window.location.href = '/bejelentkezes';
                        }
                    } else {
                        this.dispatch('error/notify', {
                            type: 'error',
                            title: 'Hiba:',
                            msg: response.data.msg
                        });
                    }
                })
                .catch(error => {
                    console.log(error)
                });
        },
        sendRegister(context, input) {
            axios.post('/felhasznalo/register', input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    if(response.data.success) {
                        context.state.loggedIn = true
                        setTimeout(function() {
                            alert('Sikeres regisztráció!');
                            router.push('/bejelentkezes')
                        }, 500);
                    } else {
                        alert('Hiba a regisztráció során!');
                    }
                })
                .catch(error => {
                    console.log(error)
                });
        },
        sendForgotPassword(context, input) {
            axios.post('/felhasznalo/forgot-password', input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    if(response.data.success) {
                        Vue.prototype.$notify({
                            group: 'error',
                            title: 'Siker:',
                            text: 'Sikeresen megváltoztattad a jelszavad.',
                            type: 'success',
                        });
                        setTimeout(function() {
                            router.push('/bejelentkezes')
                        }, 500);
                    } else {
                        Vue.prototype.$notify({
                            group: 'error',
                            title: 'Hiba:',
                            text: response.data.msg,
                            type: 'error',
                        });
                    }
                })
                .catch(error => {
                    console.log(error)
                });
        },
        logout(context) {
            axios.post('/felhasznalo/logout', null, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    //if(response.data.success) {
                    context.state.loggedIn = false;
                    localStorage.removeItem('permission');
                    localStorage.removeItem('expireTime');
                    window.location.href = '/bejelentkezes';
                    //} else {
                    //   alert('Nem vagy bejelentkezve!');
                    //}
                })
                .catch(error => {
                    console.log(error)
                });
        },
        sendSettings(context, input) {
            axios.post('/felhasznalo/save-settings', input, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    if(response.data.success) {
                        alert(response.data.msg);
                    } else {
                        alert(response.data.msg);
                    }
                })
                .catch(error => {
                    console.log(error)
                });
        },
        fetchAccount(context, input) {
            axios.get('/felhasznalo/' + input, null, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                .then(response => {
                    console.log('fetchAccount')
                    console.log(context.state.account)
                    context.state.account = response.data.account;
                })
                .catch(error => {
                    console.log(error)
                });
        }
    }
};