<template>
    <div id="app" class="background">
        <div>
            <notifications group="notify" />
            <div v-if="isLoggedIn">
                <template v-if="this.permission==1">
                    <user></user>
                </template>
                <template v-else>
                    <authorized></authorized>
                </template>
            </div>
            <div v-else>
                <not-authorized></not-authorized> <!-- Emiatt van dupla render -->
            </div>
        </div>
    </div>
</template>


<script>
    import NotAuthorized from './components/layouts/NotAuthorized.vue'
    import Authorized from './components/layouts/Authorized.vue'
    import User from './components/layouts/User.vue'

    export default {
        name: 'app',
        components: {
            'not-authorized': NotAuthorized,
            'authorized': Authorized,
            'user': User
        },
        data() {
            return {
                changed: this.isLoggedIn
            }
        },
        computed: {
            isLoggedIn() { return localStorage.getItem('permission')!=null; },
            permission() { return localStorage.getItem('permission') }
        },
        created() {
            console.log('App.vue')
            this.$store.dispatch('account/isLoggedIn');
        }
    }
</script>
