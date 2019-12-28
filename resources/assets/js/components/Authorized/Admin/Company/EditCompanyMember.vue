<template>
    <div>

        <v-layout row justify-center>
            <v-dialog
                    v-model="showing"
                    max-width="290"
                    persistent
            >
                <v-card>
                    <v-card-title class="headline">Személy szerkesztése</v-card-title>
                    <v-card-text>
                        <!-- Csoportok -->
                        <template v-if="groupsLoaded == false">
                            <v-layout align-center>
                                <v-flex class="text-xs-center">
                                    <v-progress-circular
                                            :size="100"
                                            color="info"
                                            indeterminate
                                    ></v-progress-circular>
                                </v-flex>
                            </v-layout>
                        </template>
                        <template v-else>

                            <v-select
                                    v-model="account.educationReason"
                                    v-validate="'required'"
                                    :error-messages="errors.collect('educationReason')"
                                    data-vv-as="educationReason"
                                    :items="educationReason"
                                    id="educationReason"
                                    name="educationReason"
                                    placeholder="Az oktatás indoka."
                                    hint="* Az oktatás indoka."
                                    prepend-icon="lock"
                                    item-text="text"
                                    item-value="id"
                                    single-line
                            ></v-select>

                            <v-combobox
                                    prepend-icon="notifications"
                                    :items="groups"
                                    item-text="name"
                                    v-model="selectedGroups"
                                    label="Jogosultsági csoportok"
                                    multiple
                                    chips
                                    return-object
                            ></v-combobox>
                            <!-- Inputok -->
                            <div class="row">
                                <div class="col-md-6">
                                    <v-text-field prepend-icon="perm_contact_calendar" v-model="account.lastName" class="form-control" id="lastName" placeholder="* Vezetéknév" type="text"></v-text-field>
                                </div>
                                <div class="col-md-6">
                                    <v-text-field prepend-icon="perm_contact_calendar" v-model="account.firstName" class="form-control" id="firstName" placeholder="* Keresztnév" type="text"></v-text-field>
                                </div>
                            </div>
                            <div class="form-group">
                                <v-text-field prepend-icon="email" v-model="account.email" class="form-control" id="email" placeholder="* E-mail cím" type="text" disabled></v-text-field>
                            </div>
                        </template>
                    </v-card-text>
                    <v-card-actions class="inline-block">
                        <button type="button" class="btn btn-danger" @click="closeDialog()">Bezárás</button>
                        <button type="button" class="btn btn-success" @click="editCompanyMember()">Mentés</button>
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
                showing: null,
                groups: [],
                selectedGroups: [],
                groupsLoaded: false,
                educationReason: [
                    {id: 1, text: 'Munkába állás'},
                    {id: 2, text: 'Ismétlődő oktatás'},
                    {id: 3, text: 'Tűzvédelmi helyzet megváltozása'},
                    {id: 4, text: 'Új technológia bevezetése'},
                    {id: 5, text: 'Egyéni védőeszközök használata'},
                    {id: 6, text: 'Kockázatértékelésből adódó követelmények'},
                    {id: 7, text: 'Munkakör megváltozása'}
                ],
            }
        },
        computed: {
            onCompanySelect() { return this.chosenCompany },
            getGroups() { return this.$store.getters['group/fetchGroup']},
            onShow() { return this.show }
        },
        watch: {
            onCompanySelect: function(value) {
                console.log('watch')
                /**
                 * @Felhasználó
                 */
                this.account.id = value.con.users.id;
                this.account.firstName = value.con.users.firstName;
                this.account.lastName = value.con.users.lastName;
                this.account.email = value.con.users.email;
                this.account.groups = value.con.users.groups;
                this.account.educationReason = value.con.users.educationReason;

                /**
                 * @Cég (Frissülni fog real-time, az applikációt nem zavarhatja.)
                 */
                this.company = value.company;
            },
            onShow: function(value) {
                this.fetchGroup();
                this.showing = value;
            },
            getGroups: function(value) {
                console.log('## getGroups ##')
                console.log(Object.keys(value).length)
                if(Object.keys(value).length === 0) {
                    this.groupsLoaded = true;
                    return;
                }
                let tempGroups = [];
                /** @Az összes csoport egy tömbbe tétele */
                value.forEach(function (value, key) {
                    tempGroups.push({id: value.id, name: value.name});
                });
                /** @A felhasználó csoportjain átfutunk egy ciklussal, majd hozzárendeljük a nevét. */
                let cUserGroups = Object.values(this.account.groups);
                console.log('#cUserGroups#')
                console.log(cUserGroups)
                let userGroups = [];
                tempGroups.forEach(function (value, key) {
                    var found = cUserGroups.find(function(element) {
                        console.log('element -> ' + element + ' value.id -> ' + value.id)
                        return element == value.id;
                    });
                    console.log('found -> ' + found)
                    if(found) {
                        userGroups.push({id: value.id, name: value.name});
                    }
                });

                console.log('#GROUPS#')
                this.groups = Object.values(tempGroups);
                console.log(this.groups)
                console.log('#USER GROUPS#')
                this.selectedGroups = Object.values(userGroups);
                console.log(this.selectedGroups)
                this.groupsLoaded = true;
            }
        },
        methods: {
            fetchGroup() {
                this.groupsLoaded = false;
                console.log(this.$route.params.ref)
                this.$store.dispatch('group/fetchGroup', {
                    companyRef: this.company.ref
                });
            },
            editCompanyMember() {
                this.$store.dispatch('company/editCompanyMember', {
                    account: this.account,
                    companyId: this.company.id,
                    groups: this.selectedGroups,
                    fetchAll: true
                });
                this.closeDialog()
            },
            closeDialog() {
                this.$parent.$data.dialogs = {};
            }
        }
    }
</script>