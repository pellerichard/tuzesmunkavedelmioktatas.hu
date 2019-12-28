<template>
    <div>

        <v-layout row justify-center>
            <v-dialog
                    v-model="showing"
                    max-width="600"
                    persistent
            >
                <v-card>
                    <v-card-title class="headline">Új teszt létrehozása</v-card-title>
                    <v-card-text>
                        <!-- Inputok -->

                        <v-flex xs12>
                            <v-text-field prepend-icon="perm_contact_calendar" v-model="test.name" class="form-control" id="name" placeholder="* Teszt címe" type="text"></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-text-field prepend-icon="perm_contact_calendar" v-model="test.videoUrl" class="form-control" id="videoUrl" placeholder="* Video URL (Embed)" type="text"></v-text-field>
                        </v-flex>

                    </v-card-text>
                    <v-card-actions class="inline-block">
                        <button type="button" class="btn btn-danger" @click="closeDialog()">Bezárás</button>
                        <button type="button" class="btn btn-success" @click="addTest()">Teszt létrehozása</button>
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
                test: {
                    name: null,
                    videoUrl: null
                },
                showing: null
            }
        },
        methods: {
            addTest() {
                this.$store.dispatch('test/addTest', {
                    test: this.test
                });
                this.closeDialog()
            },
            closeDialog() {
                this.$parent.$data.dialogs = {};
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