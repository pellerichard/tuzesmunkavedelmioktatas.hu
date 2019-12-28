<template>
    <div>
        <template v-if="Object.keys(company).length === 0 || groupsLoaded == false">
            <v-app style="background: rgba(0,0,0,0)">
                <v-container fill-height>
                    <v-layout row wrap align-center>
                        <v-flex class="text-xs-center">
                            <v-progress-circular
                                    :size="150"
                                    color="info"
                                    indeterminate
                            ></v-progress-circular>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-app>
        </template>
        <template v-else>

            <v-container fluid grid-list-lg>

                <v-card color="primary" class="white--text round-border" style="margin-bottom: 10px;">
                    <v-card-title primary-title>
                        <v-list-tile-title class="headline v-title-height">{{ company.name }}</v-list-tile-title>
                        <v-list-tile-title class="blockquote-footer caption">Licensz: {{ company.licenseDate }}</v-list-tile-title>
                    </v-card-title>
                </v-card>


                <div class="text-xs-left" style="margin-bottom: 10px;">
                    <!-- Új felhasználói jogosultság -->
                    <v-btn color="primary" class="white--text round-border" @click="showCreate()">
                        Új munkatárs beosztási csoport
                    </v-btn>
                </div>

                <v-layout row wrap>
                    <template v-for="(group, key) in groups">
                        <v-flex md2>
                            <v-card color="primary" class="white--text round-border">
                                <v-card-title primary-title class="justify-center">
                                    <span tabindex="-1" class="v-chip v-chip--select-multi v-chip--disabled theme--light combobox normal-font-size">
                                        <span class="v-chip__content">
                                            {{ group.name }}
                                        </span>
                                    </span>
                                </v-card-title>
                                <v-card-actions class="inline-block">
                                    <button type="button" class="btn btn-danger combobox-edit" @click="showEdit(key)">Szerkesztés</button>
                                    <button type="button" class="btn btn-success combobox-edit" @click="showDelete(key)">Törlés</button>
                                </v-card-actions>
                            </v-card>
                        </v-flex>
                    </template>
                </v-layout>
            </v-container>
            <newgroup v-bind='{ company, show: dialogs.create }'></newgroup>
            <editgroup v-bind='{ chosenGroup, show: dialogs.edit }'></editgroup>
            <deletegroup v-bind='{ chosenGroup, show: dialogs.delete }'></deletegroup>
        </template>
    </div>
</template>

<script>

    import NewGroup from './NewGroup.vue'
    import EditGroup from './EditGroup.vue'
    import DeleteGroup from './DeleteGroup.vue'

    export default {
        components: {
            'newgroup': NewGroup,
            'editgroup': EditGroup,
            'deletegroup': DeleteGroup
        },
        data() {
            return {
                company: {},
                chosenCompany: {},
                dialogs: {
                    edit: false,
                    delete: false,
                    create: false
                },
                groupsLoaded: false,
                groups: {},
                chosenGroup: {}
            }
        },
        created() {
            this.fetchCompany();
            this.fetchGroup();
        },
        computed: {
            getCompany() { return this.$store.getters['company/fetchCompany'] },
            getGroups() { return this.$store.getters['group/fetchGroup']}
        },
        watch: {
            getCompany: function(value) {
                this.company = value;
            },
            getGroups: function(value) {
                this.groups = value;
                this.groupsLoaded = true;
            }
        },
        methods: {
            showEdit(id) {
                this.parseData(id)
                this.dialogs = {};
                this.dialogs.edit = true;
            },
            showDelete(id) {
                this.parseData(id)
                this.dialogs = {};
                this.dialogs.delete = true;
            },
            showCreate() {
                this.dialogs = {};
                this.dialogs.create = true;
            },
            fetchCompany() {
                this.$store.dispatch('company/fetchCompany', {
                    fetchAll: false,
                    companyRef: this.$route.params.ref
                });
            },
            fetchGroup() {
                console.log(this.$route.params.ref)
                this.$store.dispatch('group/fetchGroup', {
                    companyRef: this.$route.params.ref
                });
            },
            parseData(id) {
                this.chosenGroup = {
                    'company': this.company,
                    'group': this.groups[id]
                };
            }
        }
    }
</script>