<template>
    <div>
        <h1>Beállítások</h1>
        <div class="row">
            <div class="col-md-6">
                <h3>Jelszó megváltoztatása</h3>
                <div class="form-group">
                    <input type="password" v-model="account.password" class="form-control" id="password" placeholder="* Jelszó">
                </div>
                <div class="form-group">
                    <input type="password" v-model="account.password_again" class="form-control" id="password_again" placeholder="* Jelszó megismétlése">
                </div>
                <button type="submit" class="btn btn-primary" @click="sendSettings(0)">Jelszó megváltoztatása</button>
            </div>
            <div class="col-md-6">
                <h3>Adataid</h3>
                <div class="form-group">
                    <input type="text" v-model="account.email" class="form-control" id="email" placeholder="* E-mail cím" disabled>
                </div>
                <div class="form-group">
                    <input type="text" v-model="account.lastName" class="form-control" id="lastName" placeholder="* Vezetéknév">
                </div>
                <div class="form-group">
                    <input type="text" v-model="account.firstName" class="form-control" id="firstName" placeholder="* Keresztnév">
                </div>
                <div class="form-group">
                    <input type="text" v-model="account.phoneNumber" class="form-control" id="phoneNumber" placeholder="* Telefonszám">
                </div>
                <button type="submit" class="btn btn-primary" @click="sendSettings(1)">Adatok mentése</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                account: {
                    password: null,
                    password_again: null,
                    firstName: null,
                    lastName: null,
                    phoneNumber: null,
                    email: null
                }
            }
        },
        created() {
          this.fetchAccount();
        },
        computed: {
            getAccount() { return this.$store.getters['account/fetchAccount'] }
        },
        watch: {
            getAccount: function(value) {
                this.account.firstName = value.firstName;
                this.account.lastName = value.lastName;
                this.account.phoneNumber = value.phoneNumber;
                this.account.email = value.email;
                console.log(value)
            }
        },
        methods: {
            fetchAccount() {
                this.$store.dispatch('account/fetchAccount', 0);
            },
            sendSettings(state) {
                this.$store.dispatch('account/account/sendSettings', {
                    account: this.account,
                    state
                });
            }
        }
    }
</script>