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
                            <v-text-field prepend-icon="perm_contact_calendar" v-model="testinput.question" class="form-control" id="name" placeholder="* Kérdés címe" type="text" disabled></v-text-field>
                        </div>
                        <!-- Dinamikus mezők -->
                        <v-radio-group v-model="testinput.rightAnswer">
                            <template v-for="(input, index) in testinput.options">
                                <v-layout row align-center>
                                    <v-flex xs8>
                                        <v-text-field prepend-icon="perm_contact_calendar" v-model="input.question" class="form-control" id="name" placeholder="* A mező kérdése" type="text" disabled></v-text-field>
                                    </v-flex>
                                    <v-flex xs2 offset-sm1>
                                        <v-radio :key="index" :value="index" class="text-xs-center" justify-center disabled></v-radio>
                                    </v-flex>
                                </v-layout>
                            </template>
                        </v-radio-group>
                    </v-card-text>
                    <v-card-actions class="inline-block">
                        <button type="button" class="btn btn-danger" @click="closeDialog()">Bezárás</button>
                        <button type="button" class="btn btn-success" @click="deleteTestInput()">Kérdés törlése</button>
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
                    cacheQuestion: null,
                    options: {}
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
            deleteTestInput() {
                this.$store.dispatch('test/deleteTestInput', {
                    testinput: this.testinput
                });
                this.closeDialog()
            },
            closeDialog() {
                this.$parent.$data.dialogs = {};
            }
        }
    }
</script>