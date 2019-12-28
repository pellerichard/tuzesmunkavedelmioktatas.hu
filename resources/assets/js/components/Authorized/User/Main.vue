<template>
    <div class="text-xs-center">
        <template v-if="Object.keys(question).length === 0">

            <v-app style="background: rgba(0,0,0,0);">
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
        <template v-else-if="!question.success && question.msg && !delayNextTest">

            <v-app style="background: rgba(0,0,0,0);">
                <v-container fill-height>
                    <v-layout row wrap align-center>
                        <v-flex class="text-xs-center">
                            <div>
                                <v-icon color="primary" x-large dark>info</v-icon>
                            </div>
                            <div>
                                <div class="white--text text-shadow">
                                    A kérdőíveket sikeresen kitöltötted.
                                </div>
                                <div class="white--text text-shadow">
                                    {{ question.msg }}
                                </div>
                            </div>
                            <div>
                                <v-btn color="info" @click="retryTest()" small>Újrapróbálkozás</v-btn>
                            </div>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-app>

        </template>
        <template v-else-if="!state">
            <!-- Teszt beküldésekor töltő képernyő -->
            <template v-if="delayNextTest">
                <v-app style="background: rgba(0,0,0,0);">
                    <v-container fill-height>
                        <v-layout row wrap align-center>
                            <v-flex class="text-xs-center">
                                <div>
                                    <v-progress-circular
                                        :size="150"
                                        color="info"
                                        indeterminate
                                    ></v-progress-circular>
                                </div>
                                <div class="white--text text-shadow">
                                    Kérdőívek lekérdezése folyamatban ...
                                </div>
                            </v-flex>

                        </v-layout>
                    </v-container>
                </v-app>
            </template>
            <template v-else>
                <v-flex xs12 sm6 offset-sm3>
                    <!-- Ha nem kezdte el a tesztet még -->
                    <template v-if="!testStarted">
                        <h3 class="white--text text-shadow">Üdvözlöm!</h3>
                        <p class="white--text text-shadow">Ezen a weboldalon tekintheti meg a tűz és munkavédelmi oktatást! 1-2 perces videókat fog látni majd utána pár egyszerű kérdést.</p>
                        <p class="white--text text-shadow">Kérem figyelmesen tekintse meg a videókat és kérem a legjobb tudása szerint töltse ki a tesztet.</p>
                        <p class="white--text text-shadow">Az "Oktatás megkezdése" gombbal indítja el a tűz és munkavédelmi oktatást!</p>
                        <v-btn color="info" @click="startTest()">Oktatás megkezdése</v-btn>
                    </template>
                    <template v-else>
                        <h3 class="module-header">{{ question.name }}</h3>
                        <video-player  class="video-player-box"
                                       ref="videoPlayer"
                                       :options="playerOptions"
                                       :playsinline="true"
                                       @play="onPlayerPlay($event)"
                                       @pause="onPlayerPause($event)"
                                       @statechanged="playerStateChanged($event)"
                                       @ready="playerReadied">
                        </video-player>
                        <v-btn color="info" @click="finishTest()" dark large >Következő lépés</v-btn>
                    </template>

                </v-flex>
            </template>
        </template>
        <template v-else>
            <v-flex xs12 sm6 offset-sm3>
                <v-container
                        fluid
                        grid-list-md
                >


                    <v-card dark class="user-v-card round-border">
                        <h4>Válaszolj a következő kérdésekre</h4>
                    </v-card>

                    <!-- Vissza -->
                    <div class="text-sm-left">
                        <v-btn color="primary" class="white--text round-border" @click="backToVideo()">Vissza a videóra</v-btn>
                    </div>

                    <template v-for="(question, index) in questions">
                        <div style="padding-top: 10px">
                            <v-card dark class="user-v-card round-border">
                                <v-card-title primary-title>
                                    <div>
                                        <h3 class="headline mb-0">{{ question.question }}</h3>
                                        <div>
                                            <v-radio-group v-model="selectedOptions[index]">
                                                <v-radio v-for="(option, key) in question.options" :key="key" :value="key" :label="option"></v-radio>
                                            </v-radio-group>
                                        </div>
                                    </div>
                                </v-card-title>
                            </v-card>
                        </div>
                    </template>
                </v-container>
                <!-- Teszt beküldése -->
                <v-btn color="info" @click="sendTest()">Következő lépés</v-btn>
            </v-flex>
        </template>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                question: {},
                answers: {},
                state: 0,
                selectedOptions: {},
                testStarted: false,
                delayNextTest: false,
                playerOptions: {
                    // videojs options
                    muted: false,
                    language: 'hu',
                    height: '360',
                    width: '640',
                    controls: true,
                    fluid: false,
                    autoplay: true,
                    volume: 0.5,
                    responsive: true,
                    playbackRates: [],
                    sources: [{
                        type: "video/mp4",
                        src: ''
                    }],
                    controlBar: {
                        remainingTimeDisplay: true,
                        playToggle: {},
                        progressControl: {},
                        fullscreenToggle: {},
                        volumeMenuButton: {
                            inline: false,
                            vertical: true
                        }
                    }
                }
            }
        },
        methods: {
            backToVideo() {
                this.state = false;
                this.delayNextTest = true;
                this.loadNextTest()
            },
            fetchQuestion() {
                console.log('fetchedQuestion -> ' + this.fetchedQuestion)
                this.$store.dispatch('test/fetchQuestion', null);
            },
            startTest() {
                if(!this.testStarted) {
                    this.testStarted = 1;
                }
            },
            finishTest() {
                if(!this.state) {
                    this.state = 1;
                }
            },
            retryTest() {
                this.delayNextTest = true;
                this.loadNextTest()
            },
            sendTest() {
                console.log('length -> ' + Object.keys(this.selectedOptions).length + ' length 2 -> ' + this.questions.length)
                if(Object.keys(this.selectedOptions).length==this.questions.length) {
                    this.delayNextTest = true;
                    this.$store.dispatch('test/sendTest', {
                        question: this.question,
                        answers: this.selectedOptions
                    });
                    this.state = 0;
                    this.selectedOptions = {};
                    this.loadNextTest()
                } else {
                    console.log('Nem töltötted ki az összeset.')
                }
            },
            loadNextTest() {
                setTimeout(function() {
                    this.delayNextTest = false
                    this.fetchQuestion()
                }.bind(this), 3000)
            },
            onPlayerPlay(player) {
                //console.log('player play!', player)
            },
            onPlayerPause(player) {
                // console.log('player pause!', player)
            },
            // ...player event

            // or listen state event
            playerStateChanged(playerCurrentState) {
                // console.log('player current update state', playerCurrentState)
            },

            // player is ready
            playerReadied(player) {
                console.log('the player is readied', player)
                // you can use it to do something...
                // player.[methods]
            }
        },
        watch: {
            getQuestion: function(value) {
                console.log('getQuestion')
                console.log(value)
                this.question = value
                this.playerOptions.sources[0].src = value.videoUrl;
            }
        },
        computed: {
            getQuestion() { return this.$store.getters['test/fetchQuestion'] },
            questions() { return this.question.inputs },
            player() {
                return this.$refs.videoPlayer.player
            }
        },
        mounted() {
            this.fetchQuestion()
        }
    }
</script>

<style>
    .video-js {
        width: 100% !important;
    }
</style>