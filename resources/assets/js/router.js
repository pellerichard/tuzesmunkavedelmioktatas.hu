import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuex from 'vuex'

Vue.use(VueRouter);
Vue.use(Vuex);

/**
 * @Felhasználó
 * @NotAuthorized
*/

let Login = require('./components/NotAuthorized/Login.vue');
let ForgotPassword = require('./components/NotAuthorized/ForgotPassword.vue');

/**
 * @Felhasználó
 * @Authorized
*/

let Main = require('./components/Authorized/User/Main.vue');
let Settings = require('./components/Authorized/Settings.vue');
let HowDoesTheSystemWork = require('./components/Authorized/User/HowDoesTheSystemWork.vue');
let Company = require('./components/Authorized/Company/Main.vue');
let ShowCompany = require('./components/Authorized/Company/ShowCompany.vue');
let UserNotification = require('./components/Authorized/Company/UserNotification.vue');
let ShowGroup = require('./components/Authorized/Group/ShowGroup.vue');
/**
 * @Admin
 * @Cégek
*/
let AdminCompany = require('./components/Authorized/Admin/Company/Main.vue');
let ShowAdminCompany = require('./components/Authorized/Admin/Company/ShowCompany.vue');
let AdminUsers = require('./components/Authorized/Admin/AdminUsers.vue');
let AdminSettings = require('./components/Authorized/Admin/Settings.vue');
let AdminTests = require('./components/Authorized/Admin/Test/AdminTests.vue');
let AdminInputs = require('./components/Authorized/Admin/Test/AdminInputs.vue');

export const routes = [
    /**
     * @Felhasználó
     *
     * @Jogosultságok
     * null => Nincs bejelentkezve
     * 1 => Végfelhasználó
     * 2 => Cégvezető
     * 3 => Admin
    */
    { path: '/', component: Company, meta: { permission: 2 } },
    { path: '/teszt', component: Main, meta: { permission: 1 } },
    { path: '/bejelentkezes', component: Login, meta: { permission: null, loggedIn: false } },
    { path: '/elfelejtett-jelszo', component: ForgotPassword, meta: { permission: null, loggedIn: false } },
    { path: '/beallitasok', component: Settings, meta: { permission: 1 } },
    { path: '/alkalmazott-ertesites/:token', component: UserNotification, meta: { permission: null } },
    { path: '/cegeim/:ref', component: ShowCompany, meta: { permission: 2 } },
    { path: '/hogyan-mukodik-a-rendszer', component: HowDoesTheSystemWork, meta: { permission: 2 } },
    { path: '/felhasznaloi-csoportok/:ref', component: ShowGroup, meta: { permission: 2 } },
    /**
     * @Admin
    */
    { path: '/admin/cegek', component: AdminCompany, meta: { permission: 3 } },
    { path: '/admin/felhasznalok', component: AdminUsers, meta: { permission: 3 } },
    { path: '/admin/beallitasok', component: AdminSettings, meta: { permission: 3 } },
    { path: '/admin/cegek/:ref', component: ShowAdminCompany, meta: { permission: 3 } },
    { path: '/admin/tesztek', component: AdminTests, meta: { permission: 3 } },
    { path: '/admin/teszt/:id', component: AdminInputs, meta: { permission: 3 } }
];

export const router = new VueRouter({
    mode: 'history',
    routes
});

const LOGGED_IN_FALSE = null;
const LOGGED_IN_TRUE = 1;

const PERMISSION_NORMAL = 1;
const PERMISSION_COMPANY_OWNER = 2;
const PERMISSION_ADMIN = 3;

router.beforeEach((to, from, next) => {
    var userPermission = localStorage.getItem('permission');
    var userExpireTime = localStorage.getItem('expireTime');
    var toRoute = to.fullPath;
    var toPermission = to.meta.permission;

    function redirectUser() {
        if(userPermission==null) {
            next('/bejelentkezes');
        } else if(userPermission==1) {
            next('/teszt');
        } else if(userPermission==2) {
            next('/');
        } else if(userPermission==3) {
            next('/admin/cegek');
        }
    }

    console.log({
        userPermission: userPermission,
        toRoute: toRoute,
        toPermission: toPermission
    });

    /** @Ellenőrzés hogy lejárt-e a bejelentkezési tokenje a felhasználónak. */
    if(userPermission!=null) {
        var now = (Date.now() / 1000).toFixed(0);
        if(now >= parseInt(userExpireTime)) {
            /** @Túllépte az időkorlátot, így kijelentkeztetjük. */
            console.log('Kiléptetjük');
            localStorage.removeItem('permission');
            localStorage.removeItem('expireTime');

            userPermission = null;
            userExpireTime = 0;
        }
    }

    /** @Ha van jogosultsága az oldal meglátogatásához. */
    if(userPermission>=toPermission) {
        /** @Ha az oldalra csak úgy léphet be, ha nincs bejelentkezve. */
        if (to.meta.hasOwnProperty('loggedIn') && userPermission!=null) {
            /** @Ha nincs jogosultsága, visszairányítjuk egy előző oldalra. */
            redirectUser();
        } else {
            next();
        }
    } else {
        /** @Ha nincs jogosultsága, visszairányítjuk egy előző oldalra. */
        redirectUser();
    }
});