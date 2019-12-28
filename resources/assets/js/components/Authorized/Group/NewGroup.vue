<template>
    <div>
        <v-layout row justify-center>
            <v-dialog
                    v-model="showing"
                    max-width="290"
                    persistent
            >
                <v-card>
                    <v-card-title class="headline">Új munkatárs beosztási csoport</v-card-title>
                    <v-card-text>
                        <!-- Inputok -->
                        <div class="row">
                            <v-text-field prepend-icon="perm_contact_calendar" v-model="group.name" class="form-control" id="name" placeholder="* Csoport név" type="text"></v-text-field>
                        </div>
                    </v-card-text>
                    <v-card-actions class="inline-block">
                        <button type="button" class="btn btn-danger" @click="closeDialog()">Bezárás</button>
                        <button type="button" class="btn btn-success" @click="store()">Csoport létrehozása</button>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
    </div>
</template>

<script>
    export default {
        props: ['company','show'],
        data() {
            return {
                group: {
                    name: null
                },
                showing: null
            }
        },
        methods: {
            store() {
                this.$store.dispatch('group/store', {
                    group: this.group,
                    companyId: this.company.id
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