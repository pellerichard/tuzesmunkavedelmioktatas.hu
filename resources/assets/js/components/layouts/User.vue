<template>
    <div>

        <v-app id="inspire">
            <v-navigation-drawer
                    v-model="drawer"
                    fixed
                    app
            >
                <v-list dense>
                    <v-list-tile @click="">
                        <v-list-tile-action>
                            <v-icon>exit_to_app</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title @click="logout()">Kijelentkezés</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </v-navigation-drawer>
            <v-toolbar color="primary" dark fixed app>
                <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
                <v-toolbar-title>Tűz és Munkavédelmi Oktatás</v-toolbar-title>
            </v-toolbar>
            <v-content class="background">
                <v-container fluid fill-height>
                    <v-layout>
                        <v-flex text-xs-center fill-height>
                            <router-view></router-view>
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
                drawer: false
            }
        },
        methods: {
            logout() {
                this.$store.dispatch('account/logout');
            }
        }
    }
</script>