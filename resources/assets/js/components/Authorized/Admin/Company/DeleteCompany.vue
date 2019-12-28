<template>
    <div>

        <v-layout row justify-center>
            <v-dialog
                    v-model="showing"
                    max-width="600"
                    persistent
            >
                <v-card>
                    <v-card-title class="headline">Cég törlése</v-card-title>
                    <v-card-text>
                        <!-- Inputok -->
                        <v-layout row>
                            <v-flex xs6>
                                <v-text-field prepend-icon="perm_contact_calendar" v-model="company.name" class="form-control" id="name" placeholder="* Cég neve" type="text" disabled></v-text-field>
                            </v-flex>
                            <v-flex xs6>
                                <v-text-field prepend-icon="perm_contact_calendar" v-model="company.licenseDate" class="form-control" id="license" placeholder="* Licensz" type="text" disabled></v-text-field>
                            </v-flex>
                        </v-layout>
                        <v-text-field prepend-icon="perm_contact_calendar" v-html="company.content" class="form-control" id="content" type="text" disabled></v-text-field>
                        <!--
                            <h3>Cégtulajdonos adatai</h3>
                            <v-layout row>
                                <v-flex xs6>
                                    <v-text-field prepend-icon="perm_contact_calendar" v-model="company.account.lastName" class="form-control" id="lastName" placeholder="* Vezetéknév" type="text"></v-text-field>
                                </v-flex>
                                <v-flex xs6>
                                    <v-text-field prepend-icon="perm_contact_calendar" v-model="company.account.firstName" class="form-control" id="firstName" placeholder="* Keresztnév" type="text"></v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-text-field prepend-icon="email" v-model="company.account.email" class="form-control" id="email" placeholder="* E-mail cím" type="text"></v-text-field>
                        -->
                    </v-card-text>
                    <v-card-actions class="inline-block">
                        <button type="button" class="btn btn-danger" @click="closeDialog()">Bezárás</button>
                        <button type="button" class="btn btn-success" @click="deleteCompany()" data-dismiss="modal">Cég törlése</button>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
    </div>
</template>

<script>
    export default {
        props: ['chosenCompany', 'show'],
        data() {
            return {
                customToolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }]
                ],
                company: {
                    account: {
                        firstName: null,
                        lastName: null,
                        email: null,
                        phoneNumber: null
                    },
                    id: null,
                    name: null,
                    license: null,
                    content: null,
                    licenseDate: null
                },
                licenseTime: [
                    {id: 0, text: 'Válassz ki egy időt.', time: 0},
                    {id: 1, text: '1 hónap', time: ((3600*24*30) * 1)},
                    {id: 2, text: '2 hónap', time: ((3600*24*30) * 2)},
                    {id: 3, text: '3 hónap', time: ((3600*24*30) * 3)},
                    {id: 4, text: '4 hónap', time: ((3600*24*30) * 4)},
                    {id: 5, text: '5 hónap', time: ((3600*24*30) * 5)},
                    {id: 6, text: '6 hónap', time: ((3600*24*30) * 6)},
                    {id: 7, text: '7 hónap', time: ((3600*24*30) * 7)},
                    {id: 8, text: '8 hónap', time: ((3600*24*30) * 8)},
                    {id: 9, text: '9 hónap', time: ((3600*24*30) * 9)},
                    {id: 10, text: '10 hónap', time: ((3600*24*30) * 10)},
                    {id: 11, text: '11 hónap', time: ((3600*24*30) * 11)},
                    {id: 12, text: '12 hónap', time: ((3600*24*30) * 12)},
                ],
                showing: null
            }
        },
        computed: {
            onCompanySelect() { return this.chosenCompany },
            onShow() { return this.show }
        },
        watch: {
            onCompanySelect: function(value) {
                console.log(value)
                /**
                 * @Felhasználó
                 */
                this.company.id = value.company.id;
                this.company.name = value.company.name;
                this.company.licenseDate = value.company.licenseDate
                this.company.content = value.company.content;
                /**
                 * @Cég (Frissülni fog real-time, az applikációt nem zavarhatja.)
                 */
                // this.company = value.company;
            },
            onShow: function(value) {
                this.showing = value;
            }
        },
        methods: {
            deleteCompany() {
                this.$store.dispatch('company/deleteCompany', {
                    company: this.company
                });
                this.closeDialog()
            },
            closeDialog() {
                this.$parent.$data.dialogs = {};
            }
            /*addCompanyMember() {
                this.$store.dispatch('addCompanyMember', {
                    account: this.account,
                    companyId: this.company.id,
                    fetchAll: true
                });
            }*/
        }
    }
</script>