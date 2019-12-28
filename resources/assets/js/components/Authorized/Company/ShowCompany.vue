<template>
    <div>
        <template v-if="Object.keys(company).length === 0">
            <v-app style="background: rgba(0,0,0,0)">
                <v-container fill-height>
                    <v-layout row wrap align-center>
                        <v-flex class="text-xs-center">
                            <v-progress-circular
                                    :size="150"
                                    color="info"
                                    indeterminate
                            ></v-progress-circular>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-app>
        </template>
        <template v-else>

            <v-container fluid grid-list-lg>

                <v-card color="primary" class="white--text round-border" style="margin-bottom: 10px;">
                    <v-card-title primary-title>
                        <v-list-tile-title class="headline v-title-height">{{ company.name }}</v-list-tile-title>
                        <v-list-tile-title class="blockquote-footer caption">Licensz: {{ company.licenseDate }}</v-list-tile-title>
                    </v-card-title>
                </v-card>


                <div class="text-xs-left" style="margin-bottom: 10px;">
                    <!-- Új személy felvétele -->
                    <v-btn color="primary" class="white--text round-border" @click="showCreate()">
                        Új munkatárs felvétele
                    </v-btn>
                    <!-- Felhasználói jogosultságok -->
                    <v-btn color="primary" class="white--text round-border" :to="'/felhasznaloi-csoportok/' + company.ref">A munkatársak beosztása</v-btn>

                </div>

                <!-- Szűrő -->
                <v-layout row wrap>
                    <v-flex md3>

                        <v-text-field
                                v-model="filter"
                                flat
                                label="Szűrő"
                                prepend-inner-icon="search"
                                solo
                                hide-details
                                box background-color="#primary"
                        ></v-text-field>
                    </v-flex>
                    <v-flex md1 class="text-xs-left">
                        <v-btn color="primary" class="white--text round-border search-button" @click="filterCompany()">
                            Keresés
                        </v-btn>
                    </v-flex>
                </v-layout>

                <!-- Eredmények száma -->
                <div v-if="company._filter" class="text-xs-left white--text text-shadow">
                    A keresés eredménye {{ Object.keys(company.con).length }} db munkatársat talált.
                </div>

                <v-layout row wrap>
                    <template v-for="(con, key) in company.con">
                        <v-flex md4>
                            <v-card color="primary" class="white--text round-border">
                                <v-card-title primary-title>
                                    <v-list-tile-title class="headline v-title-height">{{ con.users.lastName }} {{ con.users.firstName }}</v-list-tile-title>
                                    <v-list-tile-title class="blockquote-footer">{{ con.users.email }}</v-list-tile-title>
                                    <!-- Csoportok -->
                                    <template v-for="(group, key) in con.users.userGroups">
                                        <span tabindex="-1" class="v-chip v-chip--select-multi v-chip--disabled theme--light combobox"><span class="v-chip__content">{{ group }}</span></span>
                                    </template>
                                </v-card-title>
                                <v-card-actions>
                                    <v-btn flat dark @click="showEdit(key)">Szerkesztés</v-btn>
                                    <v-btn flat dark @click="showDelete(key)">Törlés</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-flex>
                    </template>

                </v-layout>
            </v-container>
            <newcompanymember v-bind='{ company, show: dialogs.create }'></newcompanymember>
            <editcompanymember v-bind='{ chosenCompany, show: dialogs.edit }'></editcompanymember>
            <deletecompanymember v-bind='{ chosenCompany, show: dialogs.delete }'></deletecompanymember>
        </template>
    </div>
</template>

<script>
    import NewCompanyMember from './NewCompanyMember.vue'
    import EditCompanyMember from './EditCompanyMember.vue'
    import DeleteCompanyMember from './DeleteCompanyMember.vue'

    export default {
        components: {
            'newcompanymember': NewCompanyMember,
            'editcompanymember': EditCompanyMember,
            'deletecompanymember': DeleteCompanyMember
        },
        data() {
            return {
                filter: null,
                company: {},
                chosenCompany: {},
                dialogs: {
                    edit: false,
                    delete: false,
                    create: false,
                    groups: false
                }
            }
        },
        created() {
            this.fetchCompany();
        },
        computed: {
            getCompany() { return this.$store.getters['company/fetchCompany'] }
        },
        watch: {
            getCompany: function(value) {
                console.log('asdasd')
                this.company = value;
            }
        },
        methods: {
            filterCompany() {
                this.$store.dispatch('company/fetchCompany', {
                    fetchAll: false,
                    companyRef: this.$route.params.ref,
                    filter: this.filter
                });
            },
            showEdit(id) {
                this.parseData(id)
                this.dialogs = {};
                this.dialogs.edit = true;
            },
            showGroups(id) {
                this.parseData(id)
                this.dialogs = {};
                this.dialogs.groups = true;
            },
            showDelete(id) {
                this.parseData(id)
                this.dialogs = {};
                this.dialogs.delete = true;
            },
            showCreate() {
                this.dialogs = {};
                this.dialogs.create = true;
            },
            fetchCompany() {
                this.$store.dispatch('company/fetchCompany', {
                    fetchAll: false,
                    companyRef: this.$route.params.ref
                });
            },
            parseData(id) {
                this.chosenCompany = {
                    'con' : this.company.con[id],
                    'company': this.company
                };
            }
        }
    }
</script>