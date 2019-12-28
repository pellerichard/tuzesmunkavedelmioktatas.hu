<template>
    <div>
        <template v-if="Object.keys(test).length === 0">
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
                <h3 class="text-xs-left module-header">{{ test.name }} - Mezők</h3>
                <!-- Új mező -->
                <div class="text-xs-left" style="margin-bottom: 10px;">
                    <v-btn color="primary" class="white--text round-border" @click="showCreate()">
                        Új kérdés létrehozása
                    </v-btn>
                </div>

                <v-layout row wrap>
                    <template v-for="(input, key) in test.inputs">
                        <v-flex md4>
                            <v-card color="primary" class="white--text round-border">
                                <v-card-title primary-title>
                                    <v-list-tile-title class="headline v-title-height">{{ input.question }}</v-list-tile-title>
                                    <template v-for="(answer, key) in input.options">
                                        <v-list-tile-title class="blockquote-footer">
                                                * {{ answer }} <span v-if="key==input.rightAnswer"><v-icon dark>done</v-icon></span><br>
                                        </v-list-tile-title>
                                    </template>
                                    <v-card-actions class="inline-block">
                                        <v-btn flat dark @click="showEdit(key)">Szerkesztés</v-btn>
                                        <v-btn flat dark @click="showDelete(key)">Törlés</v-btn>
                                    </v-card-actions>
                                </v-card-title>
                            </v-card>
                        </v-flex>
                    </template>
                </v-layout>
            </v-container>

            <newtestinput v-bind='{ show: dialogs.create }'></newtestinput>
            <edittestinput v-bind='{ chosenTest, show: dialogs.edit }'></edittestinput>
            <deletetestinput v-bind='{ chosenTest, show: dialogs.delete }'></deletetestinput>
        </template>
    </div>
</template>

<script>
    import NewTestInput from './NewTestInput.vue'
    import DeleteTestInput from './DeleteTestInput.vue'
    import EditTestInput from './EditTestInput.vue'

    import DeleteTest from './DeleteTest.vue'


    export default {
        data() {
            return {
                test: {},
                chosenTest: {},
                dialogs: {
                    create: null,
                    delete: null,
                    edit: null
                }
            }
        },
        components: {
            'newtestinput': NewTestInput,
            'deletetest': DeleteTest,
            'edittestinput': EditTestInput,
            'deletetestinput': DeleteTestInput
        },
        created() {
          this.fetchTest();
        },
        computed: {
            getTest() { return this.$store.getters['test/fetchTest'] }
        },
        watch: {
            getTest: function(value) {
                console.log('getTest')
                console.log(value)
                this.test = value
            }
        },
        methods: {
            fetchTest() {
                this.$store.dispatch('test/fetchTest', {
                    testId: this.$route.params.id
                });
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
                    'testinput': this.test.inputs[id]
                };
            }
        }
    }
</script>