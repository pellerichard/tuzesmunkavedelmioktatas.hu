<template>
    <div>

        <v-app id="inspire" class="background">
            <v-content>
                <v-container fluid fill-height>
                    <v-layout align-center justify-center>
                        <v-flex xs12 sm7 md3>

                            <div class="text-xs-center">
                                <img class="mb-4 logo" src="/images/logo.png">
                            </div>

                            <v-card class="elevation-12">
                                <v-toolbar dark color="primary">
                                    <v-toolbar-title class="center-v-toolbar no-overflow">Elfelejtett jelszó</v-toolbar-title>
                                </v-toolbar>
                                <v-card-text>
                                    <v-text-field prepend-icon="email" id="inputEmail" name="email" v-model="account.email" label="E-mail cím" placeholder=" " type="text"></v-text-field>
                                </v-card-text>
                                <v-card-actions>
                                    <v-layout align-center>
                                        <v-flex>
                                            <div>
                                                <v-btn block color="primary" @click="sendForgotPassword()">Új jelszó ígénylése</v-btn>
                                            </div>
                                            <div>
                                                <v-btn color="error" block :to="'/bejelentkezes'">Vissza a bejelentkezésre</v-btn>
                                            </div>
                                        </v-flex>
                                    </v-layout>
                                </v-card-actions>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-content>
        </v-app>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                account: {
                    email:null
                }
            }
        },
        methods: {
            sendForgotPassword() {
                this.$store.dispatch('account/sendForgotPassword', this.account);
            }
        },
        created() {
            console.log('Login.vue')
        }
    }
</script>