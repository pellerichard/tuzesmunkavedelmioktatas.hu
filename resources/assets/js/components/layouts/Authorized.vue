<template>
    <div>

        <v-app id="inspire">
            <v-navigation-drawer
                    v-model="drawer"
                    fixed
                    app
            >
                <v-list dense>
                    <!-- Főoldal -->
                    <v-list-tile @click="redirect('/')">
                        <v-list-tile-action>
                            <v-icon>home</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>Főoldal</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <!-- Admin
                    <v-list-tile @click="" v-if="this.permission>=3">
                        <v-list-tile-action>
                            <v-icon>arrow_drop_down</v-icon>
                        </v-list-tile-action>
                        <v-menu :nudge-width="100" v-if="this.permission>=2">
                            <v-list-tile-title class="list-hotfix" no-action slot="activator">
                                Admin
                            </v-list-tile-title>
                            <v-list>
                                <v-list-tile @click="redirect(1)">
                                    <v-list-tile-title :to="'/admin/cegek'">Cégek</v-list-tile-title>
                                </v-list-tile>
                                <v-list-tile @click="redirect(2)">
                                    <v-list-tile-title>Tesztek</v-list-tile-title>
                                </v-list-tile>
                            </v-list>
                        </v-menu>
                    </v-list-tile>
                    -->

                    <template v-for="item in items">
                        <v-list-group
                                v-if="item.children && permission >= item.requiredPermission"
                                v-model="item.model"
                                :key="item.text"
                                :prepend-icon="item.model ? item.icon : item['icon-alt']"
                                append-icon=""
                        >
                            <v-list-tile slot="activator">
                                <v-list-tile-content>
                                    <v-list-tile-title>
                                        {{ item.text }}
                                    </v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-list-tile
                                    v-for="(child, i) in item.children"
                                    :key="i"
                                    @click="redirect(child.path)"
                                    v-if="permission >= child.requiredPermission"
                            >
                                <v-list-tile-action v-if="child.icon">
                                    <v-icon>{{ child.icon }}</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-content>
                                    <v-list-tile-title>
                                        {{ child.text }}
                                    </v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </v-list-group>
                    </template>

                    <!-- Hogyan működik a rendszer? -->
                    <v-list-tile @click="redirect('/hogyan-mukodik-a-rendszer')">
                        <v-list-tile-action>
                            <v-icon>question_answer</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>Hogyan működik a rendszer?</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                    <!-- Kijelentkezés -->
                    <v-list-tile @click="logout()">
                        <v-list-tile-action>
                            <v-icon>exit_to_app</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>Kijelentkezés</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>


                    <!--<router-link class="p-2 text-dark" :to="'/'">Főoldal</router-link>
                        <router-link class="p-2 text-dark" :to="'/beallitasok'">Beállítások</router-link>
                        <a class="dropdown-toggle btn btn-outline-info" href="#" id="navbardrop" data-toggle="dropdown" v-if="this.permission>=2">
                            Admin
                        </a>
                        <div class="dropdown-menu">
                            <router-link class="dropdown-item" v-if="this.permission>=2" :to="'/admin/cegek'">Cégek</router-link>
                            <router-link class="dropdown-item" v-if="this.permission>=2" :to="'/admin/tesztek'">Tesztek</router-link>
                        </div>
                        -->

                </v-list>
            </v-navigation-drawer>
            <v-toolbar color="primary" dark fixed app>
                <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
                <v-toolbar-title>Tűz és Munkavédelmi Oktatás</v-toolbar-title>
            </v-toolbar>
            <v-content>
                <v-container fluid fill-height class="background">
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
                drawer: null,
                items: [{
                    icon: 'keyboard_arrow_up',
                    'icon-alt': 'keyboard_arrow_down',
                    text: 'Admin',
                    model: false,
                    requiredPermission: 3,
                    children: [
                        {icon: 'card_travel', text: 'Cégek', path: '/admin/cegek', requiredPermission: 3},
                        {icon: 'assignment_turned_in', text: 'Tesztek', path: '/admin/tesztek', requiredPermission: 3}
                    ]
                }]
            }
        },
        created() {
            console.log('Authorized.vue');
        },
        computed: {
            permission() { return localStorage.getItem('permission'); }
        },
        methods: {
            redirect(state) {
                this.$router.push(state);
            },
            logout() {
                this.$store.dispatch('account/logout');
            }
        }
    }
</script>