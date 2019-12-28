<template>
    <div>


        <v-layout row justify-center>
            <v-dialog
                    v-model="showing"
                    max-width="290"
                    persistent
            >
                <v-card>
                    <v-card-title class="headline v-title-height">{{ company.name }} - Személy törlése</v-card-title>
                    <v-card-text>
                        Biztosan törölni szeretnéd ezt a felhasználót?
                        <!-- Inputok -->
                        <div class="row">
                            <div class="col-md-6">
                                <v-text-field prepend-icon="perm_contact_calendar" v-model="account.lastName" class="form-control" id="lastName" placeholder="* Vezetéknév" type="text" disabled></v-text-field>
                            </div>
                            <div class="col-md-6">
                                <v-text-field prepend-icon="perm_contact_calendar" v-model="account.firstName" class="form-control" id="firstName" placeholder="* Keresztnév" type="text" disabled></v-text-field>
                            </div>
                        </div>
                        <div class="form-group">
                            <v-text-field prepend-icon="email" v-model="account.email" class="form-control" id="email" placeholder="* E-mail cím" type="text" disabled></v-text-field>
                        </div>
                    </v-card-text>
                    <v-card-actions class="inline-block">
                        <button type="button" class="btn btn-danger" @click="closeDialog()">Nem</button>
                        <button type="button" class="btn btn-danger" @click="deleteCompanyMember()">Igen</button>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
    </div>
</template>

<script>
    export default {
        props: ['chosenCompany','show'],
        data() {
            return {
                account: {},
                company: {},
                showing: null
            }
        },
        methods: {
            deleteCompanyMember() {
                this.$store.dispatch('company/deleteCompanyMember', {
                    account: this.account,
                    companyId: this.company.id,
                    fetchAll: true
                });
                this.closeDialog()
            },
            closeDialog() {
                this.$parent.$data.dialogs = {};
            }
        },
        computed: {
            onCompanySelect() { return this.chosenCompany },
            onShow() { return this.show }
        },
        watch: {
            onCompanySelect: function(value) {
                console.log(value)
                this.account = value.con.users;
                this.company = value.company;
            },
            onShow: function(value) {
                this.showing = value;
            }
        }
    }
</script>