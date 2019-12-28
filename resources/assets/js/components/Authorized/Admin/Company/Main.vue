<template>
    <div>
        <template v-if="loaded === false">

            <v-app style="background: rgba(0,0,0,0);">
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

                <h3 class="text-xs-left module-header">Bejegyzett cégek</h3>
                <div class="text-xs-left" style="margin-bottom: 10px;">
                    <v-btn color="primary" class="white--text round-border" @click="showCreate()">
                        Új cég létrehozása
                    </v-btn>
                </div>

                <v-layout row wrap>
                    <template v-for="(company, key) in companies">
                        <v-flex md4>
                            <v-card color="primary" class="white--text round-border">
                                <v-card-title primary-title>
                                    <v-list-tile-title class="headline v-title-height">{{ company.name }}</v-list-tile-title>
                                    <v-list-tile-title class="blockquote-footer caption">Licensz: {{ company.licenseDate }}</v-list-tile-title>
                                    <v-list-tile-title class="blockquote-footer caption">Munkatársak: {{ Object.keys(company.con).length }} fő</v-list-tile-title>
                                    <template v-if="company.countDiaries !== 0 || company.countDiaries === 'undefined'">
                                        <v-list-tile-title class="blockquote-footer caption"><i class="fas fa-info-circle"></i> Kiküldött naplók száma: {{ company.countDiaries }} db</v-list-tile-title>
                                        <v-list-tile-title class="blockquote-footer caption"><i class="fas fa-calendar"></i> Kiküldés dátuma: {{ company.latestDiaryDate }}</v-list-tile-title>
                                    </template>
                                    <template v-else>
                                        <v-list-tile-title class="blockquote-footer caption"><i class="fas fa-info-circle"></i> Nincs kiküldött napló.</v-list-tile-title>
                                    </template>
                                    <div style="margin-top: 10px;"><i class="fas fa-comments"></i><strong> Cég leírása</strong></div>
                                    <v-list-tile-title v-html="company.content"></v-list-tile-title>

                                </v-card-title>
                                <v-card-actions class="inline-block">
                                    <v-btn flat dark :to="'/admin/cegek/' + company.ref">Munkatársak</v-btn>
                                    <v-btn flat dark @click="showEdit(key)">Szerkesztés</v-btn>
                                    <v-btn flat dark @click="showDelete(key)">Törlés</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-flex>
                    </template>
                </v-layout>
            </v-container>
        </template>
        <newcompany v-bind='{ show: dialogs.create }'></newcompany>
        <editcompany v-bind='{ chosenCompany, show: dialogs.edit }'></editcompany>
        <deletecompany v-bind='{ chosenCompany, show: dialogs.delete }'></deletecompany>
    </div>
</template>

<script>
    import NewCompany from './NewCompany.vue'
    import EditCompany from './EditCompany.vue'
    import DeleteCompany from './DeleteCompany.vue'

    export default {
        data() {
            return {
                loaded: false,
                companies: {},
                chosenCompany: {},
                dialogs: {
                    create: null,
                    edit: null,
                    delete: null
                }
            }
        },
        components: {
            'newcompany': NewCompany,
            'editcompany': EditCompany,
            'deletecompany': DeleteCompany
        },
        created() {
            this.fetchCompanies();
        },
        computed: {
            getCompanies() { return this.$store.getters['company/fetchCompanies'] }
        },
        watch: {
            getCompanies: function(value) {
                this.companies = value;
                this.loaded = true;
            }
        },
        methods: {
            fetchCompanies() {
                this.$store.dispatch('company/fetchCompanies', {
                    fetchAll: true}
                );
            },
            showEdit(id) {
                this.parseData(id)
                this.dialogs = {};
                this.dialogs.edit = true;
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
            parseData(id) {
                this.chosenCompany = {
                    'company': this.companies[id]
                };
            }
        }
    }
</script>