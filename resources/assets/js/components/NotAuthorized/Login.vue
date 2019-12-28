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
                                    <v-toolbar-title class="center-v-toolbar no-overflow">Bejelentkezés</v-toolbar-title>
                                </v-toolbar>
                                <v-card-text>
                                    <v-text-field prepend-icon="email" name="email" v-model="account.email" label="E-mail cím" placeholder=" " type="text" @keyup.enter="sendLogin()"></v-text-field>
                                    <v-text-field id="password" prepend-icon="lock" v-model="account.password" name="password" label="Jelszó" placeholder=" " type="password" @keyup.enter="sendLogin()"></v-text-field>
                                    <div class="checkbox mb-3">
                                        <v-checkbox
                                                v-model="account.keepalive"
                                                label="Maradjak bejelentkezve"
                                        ></v-checkbox>
                                    </div>
                                </v-card-text>
                                <v-card-actions>
                                    <v-layout align-center>
                                        <v-flex>
                                            <div>
                                                <v-btn color="primary" block large @click="sendLogin()">Bejelentkezés</v-btn>
                                            </div>
                                            <!--<div>
                                                <v-btn color="error" block :to="'/elfelejtett-jelszo'">Elfelejtetted a jelszavad?</v-btn>
                                            </div>-->
                                        </v-flex>
                                    </v-layout>
                                </v-card-actions>
                            </v-card>

                            <div style="margin-top: 25px;">
                                <center>
                                    <p class="text-muted">Hibát észleltél, vagy elfelejtetted a jelszavad? Itt tudod jelenteni: <a href="mailto:oktatas@emailcimed.hu">oktatas@emailcimed.hu</a></p>
                                </center>
                            </div>

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
                  email:null,
                  password:null,
                  keepalive:false
              }
          }
        },
        methods: {
            sendLogin() {
                this.$store.dispatch('account/sendLogin', this.account);
            }
        },
        created() {
            console.log('Login.vue')
        }
    }
</script>