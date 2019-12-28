<template>
    <div>
        <v-layout row justify-center>
            <v-dialog
                    v-model="showing"
                    max-width="600"
                    persistent
            >
                <v-card>
                    <v-card-title class="headline v-title-height">{{ testinput.cacheQuestion }} - Kérdés szerkesztése</v-card-title>
                    <v-card-text>
                        <!-- Inputok -->
                        <h3>Teszt mező adatai</h3>
                        <div class="form-group">
                            <v-text-field prepend-icon="perm_contact_calendar" v-model="testinput.question" class="form-control" id="name" placeholder="* Kérdés címe" type="text"></v-text-field>
                        </div>
                        <!-- Dinamikus mezők -->
                        <v-radio-group v-model="testinput.rightAnswer">
                            <template v-for="(input, index) in testinput.options">
                                <v-layout row align-center>
                                    <v-flex xs8>
                                        <v-text-field prepend-icon="perm_contact_calendar" v-model="input.question" class="form-control" id="name" placeholder="* A mező kérdése" type="text"></v-text-field>
                                    </v-flex>
                                    <v-flex xs2 offset-sm1>
                                        <v-radio :key="index" :value="index" class="text-xs-center" justify-center></v-radio>
                                    </v-flex>
                                    <v-flex xs2>

                                        <div class="text-xs-left">
                                            <v-btn fab dark small color="primary" @click="deleteRow(index)">
                                                <v-icon dark>delete</v-icon>
                                            </v-btn>
                                        </div>

                                    </v-flex>
                                </v-layout>
                            </template>
                        </v-radio-group>
                        <div class="text-xs-left" style="margin-bottom: 10px;">
                            <v-btn color="primary" class="white--text round-border" @click="addRow()">
                                Új mező hozzáadása
                            </v-btn>
                        </div>
                    </v-card-text>
                    <v-card-actions class="inline-block">
                        <button type="button" class="btn btn-danger" @click="closeDialog()">Bezárás</button>
                        <button type="button" class="btn btn-success" @click="editTestInput()">Kérdés mentése</button>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
    </div>
</template>

<script>
    export default {
        props: ['chosenTest', 'show'],
        data() {
            return {
                testinput: {
                    id: null,
                    question: null,
                    rightAnswer: null,
                    options: {},
                    cacheQuestion: null
                },
                showing: null
            }
        },
        computed: {
            onTestInputSelect() { return this.chosenTest },
            onShow() { return this.show }
        },
        watch: {
            onTestInputSelect: function(value) {
                console.log('watch')
                /**
                 * @Teszt input
                */
                console.log(value.testinput)
                this.testinput.id = value.testinput.id;
                this.testinput.question = value.testinput.question;
                this.testinput.cacheQuestion = value.testinput.question;
                this.testinput.rightAnswer = value.testinput.rightAnswer;
                var options = value.testinput.options;
                var cacheOptions = [];
                options.forEach(function (value, key) {
                    cacheOptions.push({
                        question: value
                    });
                });
                this.testinput.options = cacheOptions;
            },
            onShow: function(value) {
                this.showing = value;
            }
        },
        methods: {
            editTestInput() {
                this.$store.dispatch('test/editTestInput', {
                    testinput: this.testinput,
                    testId: this.$route.params.id
                });
                this.closeDialog()
            },
            addRow() {
                console.log('addRow')
                this.testinput.options.push({
                    question: ''
                });
            },
            deleteRow(index) {
                /**
                 * @TODO Első elemet le kell kérdezni majd arra beállítani a rádió inputnak az értékét.
                 */
                this.testinput.options.splice(index,1)
            },
            closeDialog() {
                this.$parent.$data.dialogs = {};
            }
        }
    }
</script>