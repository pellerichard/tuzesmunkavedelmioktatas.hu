<template>
    <div>

        <v-layout row justify-center>
            <v-dialog
                    v-model="showing"
                    max-width="600"
                    persistent
            >
                <v-card>
                    <v-card-title class="headline v-title-height">{{ test.name }} - Teszt törlése</v-card-title>
                    <v-card-text>
                        <!-- Inputok -->
                        <v-flex xs12>
                            <v-text-field prepend-icon="perm_contact_calendar" v-model="test.name" class="form-control" id="name" placeholder="* Teszt címe" type="text" disabled></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-text-field prepend-icon="perm_contact_calendar" v-model="test.videoUrl" class="form-control" id="videoUrl" placeholder="* Video URL (Embed)" type="text" disabled></v-text-field>
                        </v-flex>
                    </v-card-text>
                    <v-card-actions class="inline-block">
                        <button type="button" class="btn btn-danger" @click="closeDialog()">Bezárás</button>
                        <button type="button" class="btn btn-success" @click="deleteTest()">Teszt törlése</button>
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
                test: {
                    name: null,
                    videoUrl: null
                },
                showing: null
            }
        },
        computed: {
            onTestSelect() { return this.chosenTest },
            onShow() { return this.show }
        },
        watch: {
            onTestSelect: function(value) {
                /**
                 * @Teszt
                */
                this.test.id = value.test.id;
                this.test.name = value.test.name;
                this.test.videoUrl = value.test.videoUrl;
            },
            onShow: function(value) {
                this.showing = value;
            }
        },
        methods: {
            deleteTest() {
                this.$store.dispatch('test/deleteTest', {
                    test: this.test
                });
                this.closeDialog()
            },
            closeDialog() {
                this.$parent.$data.dialogs = {};
            }
        }
    }
</script>