<template>
    <div>
        <template v-if="!success && !error">
            <v-progress-circular
                    :size="50"
                    color="info"
                    indeterminate
            ></v-progress-circular>
            <br>
            <div class="white--text text-shadow">
                Levelek kiküldése folyamatban ...
            </div>
        </template>
        <template v-else-if="error">
            <div class="text-xs-center">
                <v-flex>
                    <div>
                        <v-icon color="primary" x-large dark>error</v-icon>
                    </div>
                    <div class="white--text text-shadow">
                        {{ error }}
                    </div>
                    <div class="white--text text-shadow">
                        Kattints <router-link :to="'/'">ide</router-link> a főoldalra kerüléshez.
                    </div>
                </v-flex>
            </div>
        </template>
        <template v-else>
            <div class="text-xs-center">
                <v-icon color="primary" x-large dark>done</v-icon>
                <br>
                <div class="white--text text-shadow">
                    Értesítetted a munkatársaid, hogy végezzék el a feladatot.
                </div>
                <div class="white--text text-shadow">
                    Kattints <router-link :to="'/'">ide</router-link> a főoldalra kerüléshez.
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                success: false,
                error: false
            }
        },
        created() {
            this.employeeNotification();
            setTimeout(function() {
                if(!this.error) {
                    this.success = true;
                }
            }.bind(this), 3000);
        },
        methods: {
            employeeNotification() {
                var token = this.$route.params.token
                axios.post('/send-employee-notification/' + token, token, { headers: { 'Content-type': 'application/x-www-form-urlencoded' }})
                    .then(response => {
                        if(!response.data.success && response.data.msg) {
                            this.error = response.data.msg;
                        }
                    })
                    .catch(error => {
                        console.log(error)
                    });
            }
        }
    }
</script>