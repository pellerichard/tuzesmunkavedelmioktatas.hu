<template>
    <div>
        <v-layout row justify-center>
            <v-dialog
                    v-model="showing"
                    max-width="600"
                    persistent
            >
                <v-card>
                    <v-card-title class="headline">Új kérdés létrehozása</v-card-title>
                    <v-card-text>
                        <!-- Inputok -->
                        <h3>Teszt mező adatai</h3>
                        <div class="form-group">
                            <v-text-field prepend-icon="perm_contact_calendar" v-model="testinput.question" class="form-control" id="name" placeholder="* Kérdés címe" type="text"></v-text-field>
                        </div>
                        <!-- Dinamikus mezők -->


                        <v-radio-group v-model="selectedOption">
                            <template v-for="(input, index) in testinput.option">
                                <v-layout row align-center>
                                    <v-flex xs8>
                                        <v-text-field prepend-icon="perm_contact_calendar" v-model="input.question" class="form-control" id="name" placeholder="* A mező kérdése" type="text"></v-text-field>
                                    </v-flex>
                                    <v-flex xs2 offset-sm1>
                                        <!--
                                            <input type="radio" id="rightAnswer" :value="index" v-model="testinput.rightAnswer">
                                            -->
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
                        <button type="button" class="btn btn-success" @click="addTestInput()">Kérdés létrehozása</button>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
    </div>
</template>

<script>
    import NewTest from './NewTest.vue'
    import DeleteTest from './DeleteTest.vue'

    export default {
        props: ['show'],
        data() {
            return {
                testinput: {
                    question: null,
                    option: [],
                    rightAnswer: 0
                },
                showing: null,
                selectedOption: null
            }
        },
        methods: {
            addTestInput() {
                this.$store.dispatch('test/addTestInput', {
                    testinput: this.testinput,
                    selectedOption: this.selectedOption,
                    testId: this.$route.params.id
                });
                this.closeDialog()
            },
            closeDialog() {
                this.$parent.$data.dialogs = {};
            },
            addRow() {
                console.log('addRow')
                console.log(this.testinput.option)
                this.testinput.option.push({
                    question: ''
                });
            },
            deleteRow(index) {
                /**
                 * @TODO Első elemet le kell kérdezni majd arra beállítani a rádió inputnak az értékét.
                */
                this.testinput.option.splice(index,1)
            }
        },
        computed: {
            onShow() { return this.show }
        },
        watch: {
            onShow: function(value) {
                this.showing = value;
            }
        }
    }
</script>