<template>
    <div>
        <v-layout row justify-center>
            <v-dialog
                    v-model="showing"
                    max-width="600"
                    persistent
            >
                <v-card>
                    <v-card-title class="headline">Új cég létrehozása</v-card-title>
                    <v-card-text>
                        <!-- Inputok -->
                        <v-layout row>
                            <v-flex xs6>
                                <v-text-field
                                        prepend-icon="perm_contact_calendar"
                                        v-model="company.name"
                                        v-validate="'required|min:4'"
                                        :error-messages="errors.collect('name')"
                                        data-vv-as="cégnév"
                                        class="form-control"
                                        name="name"
                                        id="name"
                                        placeholder="* Cég neve"
                                        autocomplete="off"
                                        type="text"></v-text-field>
                            </v-flex>
                            <v-flex xs6>
                                <v-select
                                        v-model="company.license"
                                        v-validate="'required'"
                                        :error-messages="errors.collect('license')"
                                        data-vv-as="licensz"
                                        :items="licenseTime"
                                        id="license"
                                        name="license"
                                        label="* Licensz"
                                        placeholder="Válassz ki egy időt."
                                        hint="* A céghez kötött licensz meghosszabítása."
                                        prepend-icon="lock"
                                        item-text="text"
                                        item-value="time"
                                ></v-select>
                            </v-flex>
                        </v-layout>
                        <vue-editor id="content" v-model="company.content" :editorToolbar="customToolbar" placeholder="A cég leírása."></vue-editor>
                        <h3>Cégtulajdonos adatai</h3>
                        <v-layout row>
                            <v-flex xs6>
                                <v-text-field
                                        prepend-icon="perm_contact_calendar"
                                        v-model="company.account.lastName"
                                        v-validate="'required'"
                                        :error-messages="errors.collect('lastName')"
                                        data-vv-as="cégvezető vezetéknév"
                                        class="form-control"
                                        id="lastName"
                                        name="lastName"
                                        placeholder="* Vezetéknév"
                                        type="text"></v-text-field>
                            </v-flex>
                            <v-flex xs6>
                                <v-text-field
                                        prepend-icon="perm_contact_calendar"
                                        v-model="company.account.firstName"
                                        v-validate="'required'"
                                        :error-messages="errors.collect('firstName')"
                                        data-vv-as="cégvezető keresztnév"
                                        class="form-control"
                                        id="firstName"
                                        name="firstName"
                                        placeholder="* Keresztnév"
                                        type="text"></v-text-field>
                            </v-flex>
                        </v-layout>
                        <v-text-field
                                prepend-icon="email"
                                v-model="company.account.email"
                                v-validate="'required|email'"
                                :error-messages="errors.collect('email')"
                                data-vv-as="cégvezető e-mail cím"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="* E-mail cím"
                                type="text"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="inline-block">
                        <button type="button" class="btn btn-danger" @click="closeDialog()">Bezárás</button>
                        <button type="button" class="btn btn-success" @click="addCompany()" data-dismiss="modal">Cég létrehozása</button>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
    </div>
</template>

<script>
    export default {
        props: ['show'],
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
                    name: null,
                    license: null,
                    content: null
                },
                licenseTime: [
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
        methods: {
            addCompany() {
                this.$validator.validateAll().then(isValid => {
                    if (isValid) {
                        this.$store.dispatch('company/addCompany', {
                            company: this.company
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
                                msg: 'A szerver oldalon probléma történt, kérlek értesítsd az oldal üzemeltetőjét. Hibakód: #01'
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
                this.company = {
                    account: {
                        firstName: null,
                            lastName: null,
                            email: null,
                            phoneNumber: null
                    },
                    name: null,
                    license: null,
                    content: null
                };
                this.$validator.reset();
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