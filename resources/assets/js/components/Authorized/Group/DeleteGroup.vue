<template>
    <div>
        <v-layout row justify-center>
            <v-dialog
                    v-model="showing"
                    max-width="290"
                    persistent
            >
                <v-card>
                    <v-card-title class="headline">Csoport törlése</v-card-title>
                    <v-card-text>
                        <!-- Inputok -->
                        <div class="row">
                            <v-text-field prepend-icon="perm_contact_calendar" disabled v-model="group.name" class="form-control" id="name" placeholder="* Csoport név" type="text"></v-text-field>
                        </div>
                    </v-card-text>
                    <v-card-actions class="inline-block">
                        <button type="button" class="btn btn-danger" @click="closeDialog()">Bezárás</button>
                        <button type="button" class="btn btn-success" @click="destroy()">Csoport törlése</button>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
    </div>
</template>

<script>
    export default {
        props: ['chosenGroup','show'],
        data() {
            return {
                group: {
                    name: null
                },
                company: {
                    id: null
                },
                companyId: null,
                showing: null
            }
        },
        methods: {
            destroy() {
                this.$store.dispatch('group/destroy', {
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
            onGroupSelect() { return this.chosenGroup },
            onShow() { return this.show }
        },
        watch: {
            onGroupSelect: function(value) {
                console.log(value)
                /**
                 * @Felhasználó
                 */


                this.group.id = value.group.id;
                this.group.name = value.group.name;

                this.company.id = value.company.id;

                /**
                 * @Cég (Frissülni fog real-time, az applikációt nem zavarhatja.)
                 */
                // this.company = value.company;
            },
            onShow: function(value) {
                this.showing = value;
            }
        }
    }
</script>