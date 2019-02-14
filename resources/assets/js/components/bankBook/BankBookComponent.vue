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
                                    <v-flex sm3>
                                        <v-menu
                                            ref="menu1"
                                            :close-on-content-click="false"
                                            v-model="menu1"
                                            :nudge-right="40"
                                            :return-value.sync="startDate"
                                            lazy
                                            transition="scale-transition"
                                            offset-y
                                            full-width
                                            min-width="290px"
                                        >
                                            <v-text-field
                                                slot="activator"
                                                v-model="startDate"
                                                prepend-icon="event"
                                                readonly
                                                label="From"
                                                hide-details
                                            ></v-text-field>
                                            <v-date-picker v-model="startDate" no-title scrollable>
                                                <v-spacer></v-spacer>
                                                <v-btn flat color="primary" @click="menu1 = false">Cancel</v-btn>
                                                <v-btn flat color="primary" @click="$refs.menu1.save(startDate)">OK</v-btn>
                                            </v-date-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex sm3 pl-1>
                                        <v-menu
                                            ref="menu2"
                                            :close-on-content-click="false"
                                            v-model="menu2"
                                            :nudge-right="40"
                                            :return-value.sync="endDate"
                                            lazy
                                            transition="scale-transition"
                                            offset-y
                                            full-width
                                            min-width="290px"
                                        >
                                            <v-text-field
                                                slot="activator"
                                                v-model="endDate"
                                                prepend-icon="event"
                                                readonly
                                                label="To"
                                                hide-details
                                            ></v-text-field>
                                            <v-date-picker v-model="endDate" no-title scrollable>
                                                <v-spacer></v-spacer>
                                                <v-btn flat color="primary" @click="menu2 = false">Cancel</v-btn>
                                                <v-btn flat color="primary" @click="$refs.menu2.save(endDate)">OK</v-btn>
                                            </v-date-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex sm1 pl-1 class="text-xs-center">
                                        <v-btn slot="activator" dark icon @click="requestPasser">
                                            <v-icon>search</v-icon>
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-list-tile>
                        </v-card-actions>
                    </v-card>
                </v-flex>
                <v-flex xs12 sm12 md12 mt-4>
                    <v-card color="dark" light>
                        <v-data-table :headers="headers" :items="records" class="elevation-1">
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.date }}</td>
                                <td>{{ props.item.description }}</td>
                                <td>{{ props.item.cIn }}</td>
                                <td>{{ props.item.cOut }}</td>
                                <td>{{ props.item.balance }}</td>
                            </template>
                            <template slot="no-data">
                                <v-alert :value="true" color="error" icon="warning">
                                    Sorry, nothing to display here :(
                                </v-alert>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </v-app>
</template>

<script>
    export default {
        name: "BankBookComponent",
        data () {
            return {
                compName: "Bank Book",
                startDate: new Date().toISOString().substr(0,10),
                endDate: new Date().toISOString().substr(0,10),
                startDate1: new Date(),
                endDate1: new Date(),
                menu1: false,
                menu2: false,
                headers: [
                    {
                        text: "Date",
                        sortable: false,
                        align: 'left',
                        value: 'date'
                    },
                    {
                        text: "Description",
                        sortable: false,
                        align: 'left',
                        value: 'description'
                    },
                    {
                        text: "Deposit",
                        sortable: false,
                        align: 'left',
                        value: 'cIn'
                    },
                    {
                        text: "Withdraw",
                        sortable: false,
                        align: 'left',
                        value: 'cOut'
                    },
                    {
                        text: "Balance",
                        sortable: false,
                        align: 'left',
                        value: 'balance'
                    }
                ],
                records: [
                    {
                        value: false,
                        date: '2019/01/01',
                        description: "repayment by user 1",
                        cIn: '1000',
                        cOut: '0',
                        balance: '1000'
                    },
                    {
                        value: false,
                        date: '2019/01/01',
                        description: "repayment by user 1",
                        cIn: '1000',
                        cOut: '0',
                        balance: '1000'
                    },
                    {
                        value: false,
                        date: '2019/01/01',
                        description: "repayment by user 1",
                        cIn: '1000',
                        cOut: '0',
                        balance: '1000'
                    },
                    {
                        value: false,
                        date: '2019/01/01',
                        description: "repayment by user 1",
                        cIn: '1000',
                        cOut: '0',
                        balance: '1000'
                    },
                    {
                        value: false,
                        date: '2019/01/01',
                        description: "repayment by user 1",
                        cIn: '1000',
                        cOut: '0',
                        balance: '1000'
                    },
                    {
                        value: false,
                        date: '2019/01/01',
                        description: "repayment by user 1",
                        cIn: '1000',
                        cOut: '0',
                        balance: '1000'
                    },
                    {
                        value: false,
                        date: '2019/01/01',
                        description: "repayment by user 1",
                        cIn: '1000',
                        cOut: '0',
                        balance: '1000'
                    },
                    {
                        value: false,
                        date: '2019/01/01',
                        description: "repayment by user 1",
                        cIn: '1000',
                        cOut: '0',
                        balance: '1000'
                    },
                    {
                        value: false,
                        date: '2019/01/01',
                        description: "repayment by user 1",
                        cIn: '1000',
                        cOut: '0',
                        balance: '1000'
                    },
                ]
            }
        },
        methods: {
            requestPasser: function () {
                if (this.setEndTimestamp > this.setStartTimestamp) {
                    console.log(this.setStartTimestamp);
                    console.log(this.setEndTimestamp);
                    console.log("end date is grater than start date");
                } else {
                    console.log("end date is lower than start date");
                }
            }
        },
        computed: {
            setStartTimestamp: function () {
                return this.startDate1.setHours(0,0,0,0);
            },
            setEndTimestamp: function () {
                return this.endDate1.setHours(23,59,59,99);
            },
            foramtDatePickerSDate() {
                return this.startDate1.toISOString().substr(0,10);
            },
            formatDatePickerEDate() {
                return this.endDate1.toISOString().substr(0,10);
            }
        }
    }
</script>

<style scoped>

</style>
