<template>
    <div>
        <template v-if="Object.keys(tests).length === 0">
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
                <h3 class="text-xs-left module-header">Tesztek</h3>

                <div class="text-xs-left" style="margin-bottom: 10px;">
                    <v-btn color="primary" class="white--text round-border" @click="showCreate()">
                        Új teszt létrehozása
                    </v-btn>
                </div>

                <v-layout row wrap>
                    <template v-for="(test, key) in tests">
                        <v-flex md4>
                            <v-card color="primary" class="white--text round-border">
                                <v-card-title primary-title>
                                    <v-list-tile-title class="headline v-title-height">{{ test.name }}</v-list-tile-title>
                                    <v-list-tile-title class="blockquote-footer">Videó URL: {{ test.videoUrl }}</v-list-tile-title>
                                    <v-card-actions class="inline-block">
                                        <v-btn flat dark :to="'/admin/teszt/' + test.id">Kérdések</v-btn>
                                        <v-btn flat dark @click="showEdit(key)">Szerkesztés</v-btn>
                                        <v-btn flat dark @click="showDelete(key)">Törlés</v-btn>
                                    </v-card-actions>
                                </v-card-title>
                            </v-card>
                        </v-flex>
                    </template>

                </v-layout>
                <newtest v-bind='{ show: dialogs.create }'></newtest>
                <deletetest v-bind='{ chosenTest, show: dialogs.delete }'></deletetest>
                <edittest v-bind='{ chosenTest, show: dialogs.edit }'></edittest>
            </v-container>
        </template>
    </div>
</template>

<script>
    import NewTest from './NewTest.vue'
    import EditTest from './EditTest.vue'
    import DeleteTest from './DeleteTest.vue'

    export default {
        data() {
            return {
                tests: {},
                chosenTest: {},
                dialogs: {
                    create: null,
                    delete: null,
                    edit: null
                }
            }
        },
        components: {
            'newtest': NewTest,
            'deletetest': DeleteTest,
            'edittest': EditTest
        },
        created() {
          this.fetchTests();
        },
        computed: {
            getTests() { return this.$store.getters['test/fetchTests'] }
        },
        watch: {
            getTests: function(value) {
                console.log('getTests')
                this.tests = value
            }
        },
        methods: {
            fetchTests() {
                this.$store.dispatch('test/fetchTests', null);
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
            openModal(id) {

            },
            parseData(id) {
                this.chosenTest = {
                    'test': this.tests[id]
                };
            }
        }
    }
</script>