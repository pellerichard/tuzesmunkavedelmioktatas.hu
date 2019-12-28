<template>
    <div>
        <v-layout row justify-center>
            <v-dialog
                    v-model="showing"
                    max-width="290"
                    persistent
            >
                <v-card>
                    <v-card-title class="headline">Új személy felvétele</v-card-title>
                    <v-card-text>
                        <!-- Inputok -->
                        <div class="row">

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

                            <div class="col-md-6">
                                <v-text-field
                                        prepend-icon="perm_contact_calendar"
                                        v-model="account.lastName"
                                        class="form-control"
                                        v-validate="'required'"
                                        :error-messages="errors.collect('lastName')"
                                        data-vv-as="munkatárs vezetéknév"
                                        id="lastName"
                                        name="lastName"
                                        placeholder="* Vezetéknév"
                                        type="text">
                                </v-text-field>
                            </div>
                            <div class="col-md-6">
                                <v-text-field
                                        prepend-icon="perm_contact_calendar"
                                        v-model="account.firstName"
                                        class="form-control"
                                        v-validate="'required'"
                                        :error-messages="errors.collect('firstName')"
                                        data-vv-as="munkatárs keresztnév"
                                        id="firstName"
                                        name="firstName"
                                        placeholder="* Keresztnév"
                                        type="text">
                                </v-text-field>
                            </div>
                        </div>
                        <div class="form-group">
                            <v-text-field
                                    prepend-icon="email"
                                    v-model="account.email"
                                    class="form-control"
                                    v-validate="'required|email'"
                                    :error-messages="errors.collect('email')"
                                    data-vv-as="munkatárs e-mail"
                                    id="email"
                                    name="email"
                                    placeholder="* E-mail cím"
                                    type="text">
                            </v-text-field>
                        </div>
                    </v-card-text>
                    <v-card-actions class="inline-block">
                        <button type="button" class="btn btn-danger" @click="closeDialog()">Bezárás</button>
                        <button type="button" class="btn btn-success" @click="addCompanyMember()">Személy hozzáadása</button>
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
                account: {
                    firstName: null,
                    lastName: null,
                    email: null,
                    phoneNumber: null,
                    educationReason: 0
                },
                educationReason: [
                    {id: 1, text: 'Munkába állás'},
                    {id: 2, text: 'Ismétlődő oktatás'},
                    {id: 3, text: 'Tűzvédelmi helyzet megváltozása'},
                    {id: 4, text: 'Új technológia bevezetése'},
                    {id: 5, text: 'Egyéni védőeszközök használata'},
                    {id: 6, text: 'Kockázatértékelésből adódó követelmények'},
                    {id: 7, text: 'Munkakör megváltozása'}
                ],
                showing: null
            }
        },
        methods: {
            addCompanyMember() {

                this.$validator.validateAll().then(isValid => {
                    if (isValid) {
                        this.$store.dispatch('company/addCompanyMember', {
                            account: this.account,
                            companyId: this.company.id,
                            fetchAll: false
                        }).then(response => {
                            if(response.success) {
                                this.closeDialog();
                            } else {
                                /** @Egyedi hibaüzenet a backendről. */
                                this.$store.dispatch('error/notify', {
                                    type: 'error',
                                    title: 'Hiba:',
                                    msg: 'Hiba történt az űrlap beküldése során!'
                                });
                                if(typeof response.validator !== 'undefined' && response.validator.fieldName && response.validator.msg) {
                                    this.$validator.errors.add({
                                        field: response.validator.fieldName,
                                        msg: response.validator.msg
                                    });
                                }
                            }
                        }, error => {
                            this.$store.dispatch('error/notify', {
                                type: 'error',
                                title: 'Hiba:',
                                msg: 'A szerver oldalon probléma történt, kérlek értesítsd az oldal üzemeltetőjét. Hibakód: #02'
                            });
                        });
                    } else {
                        this.$store.dispatch('error/notify', {
                            type: 'error',
                            title: 'Hiba:',
                            msg: 'Hiányosan töltötted ki az űrlapot!'
                        });
                    }
                });
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