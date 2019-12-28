<template>
    <div>
        <template v-if="Object.keys(companies).length === 0">
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


            <v-container
                    fluid
                    grid-list-lg
            >
                <h3 class="text-xs-left module-header">Általad bejegyzett cégek</h3>

                <v-layout row wrap>

                    <template v-for="company in companies">
                        <v-flex md4>
                            <v-card color="primary" class="white--text round-border">
                                <v-card-title primary-title>


                                <v-list-tile-title class="headline v-title-height">{{ company.name }}</v-list-tile-title>
                                <v-list-tile-title class="blockquote-footer caption license-text">Licensz: {{ company.licenseDate }}</v-list-tile-title>
                                <!-- <v-list-tile-title v-html="company.content"></v-list-tile-title> -->


                                </v-card-title>
                                <v-card-actions class="inline-block">
                                    <v-btn flat dark  :to="'/cegeim/' + company.ref">Munkatársak</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-flex>
                    </template>

                </v-layout>
            </v-container>
        </template>
    </div>
</template>

<script>
    import {mapState, mapGetters, mapActions} from 'vuex'

    export default {
        data() {
            return {
                companies: {}
            }
        },
        created() {
            this.fetchCompanies();
        },
        computed: {
            getCompanies() { return this.$store.getters['company/fetchCompanies'] },
        },
        watch: {
            getCompanies: function(value) {
                this.companies = value;
            }
        },
        methods: {
            fetchCompanies() {
                console.log(this.$store)
                this.$store.dispatch('company/fetchCompanies', {fetchAll: false});
            }
        }
    }
</script>