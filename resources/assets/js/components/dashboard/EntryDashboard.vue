<template>
    <v-app>
        <v-container fluid grid-list>
            <v-layout column wrap>
                <v-flex xs12 sm12 md12>
                    <v-card dark color="primary" class="text-lg-start">
                        <v-card-actions>
                            <v-list-tile class="grow">
                                <v-list-tile-content>
                                    <v-list-tile-title>
                                        {{ compName }}
                                    </v-list-tile-title>
                                </v-list-tile-content>
                                <v-layout align-center justify-end>
                                    <v-flex sm3 offset-sm1>
                                        <v-menu
                                            ref="menu"
                                            :close-on-content-click="false"
                                            v-model="menu"
                                            :nudge-right="40"
                                            :return-value.sync="date"
                                            lazy
                                            transition="scale-transition"
                                            offset-y
                                            full-width
                                            min-width="290px"
                                        >
                                            <v-text-field
                                                slot="activator"
                                                v-model="date"
                                                prepend-icon="event"
                                                readonly
                                                hide-details
                                            ></v-text-field>
                                            <v-date-picker v-model="date" no-title scrollable>
                                                <v-spacer></v-spacer>
                                                <v-btn flat color="primary" @click="menu = false">Cancel</v-btn>
                                                <v-btn flat color="primary" @click="selectedDate(date)">OK</v-btn>
                                            </v-date-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                            </v-list-tile>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </v-app>
</template>

<script>
    export default {
        name: "EntryDashboard",
        data() {
            return {
                compName: "Main Dashboard",
                menu: false,
                date: new Date().toISOString().substr(0, 10),
            }
        },
        methods: {
            selectedDate(date) {
                console.log(date)
            }
        }
    }
</script>

<style scoped>

</style>
