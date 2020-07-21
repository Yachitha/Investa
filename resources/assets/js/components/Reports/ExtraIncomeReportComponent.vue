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
                                        <v-btn slot="activator" dark icon @click="getIncomeDetails">
                                            <v-icon>search</v-icon>
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-list-tile>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout row mt-3>
                <v-flex d-flex>
                    <v-card color="dark" light>
                        <v-toolbar flat color="white">
                            <v-flex xs4 sm4 md4>
                                <v-text-field
                                    v-model="search"
                                    append-icon="search"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                            </v-flex>
                        </v-toolbar>
                        <v-data-table :headers="headers" :items="incomes" class="elevation-1" :search="search">
                            <template slot="items" slot-scope="props">
                                <tr>
                                    <td>{{ props.item.id }}</td>
                                    <td>{{ props.item.date }}</td>
                                    <td>{{ props.item.description }}</td>
                                    <td>{{ props.item.amount }}</td>
                                </tr>
                            </template>
                            <template slot="no-data">
                                <v-alert :value="true" color="error" icon="warning">
                                    Sorry, nothing to display here :(
                                </v-alert>
                            </template>
                            <template slot="footer">
                                <tr :style="{backgroundColor:'orange'}">
                                    <td colspan="2">
                                        <strong>Summary</strong>
                                    </td>
                                    <td colspan="1">
                                        <strong>{{ labelTotalIncome }}</strong>
                                    </td>
                                    <td>
                                        {{Number(getTotalIncome).toLocaleString()}}
                                    </td>
                                </tr>
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
        name: "ExtraIncomeReportComponent",
        data() {
            return {
                compName:"EXTRA INCOME SUMMARY REPORT",
                startDate: new Date().toISOString().substr(0,10),
                endDate: new Date().toISOString().substr(0,10),
                startDate1: new Date(),
                endDate1: new Date(),
                menu1: false,
                menu2: false,
                search:[],
                incomes:[],
                headers: [
                    {
                        text: 'Income No',
                        align: 'left',
                        sortable: false,
                        value: 'id'
                    },
                    {
                        text: 'Date',
                        align: 'left',
                        sortable: false,
                        value: 'date'
                    },
                    {
                        text: 'Description',
                        align: 'center',
                        sortable: false,
                        value: 'description'
                    },
                    {
                        text: 'Amount',
                        align: 'left',
                        sortable: false,
                        value: 'amount'
                    }
                ],
                labelTotalIncome:"Total",
            }
        },
        created() {
            this.getIncomeDetails();
        },
        methods:{
            getIncomeDetails() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/incomeDetailsForReport', {
                    start_date: this.startDate,
                    end_date: this.endDate
                }).then((response) => {
                    console.log(response);
                    if (response.status === 200) {
                        if (response.data.error) {
                            this.$Progress.fail();
                            this.$notify({
                                group: 'auth',
                                title: 'Error',
                                type: 'error',
                                text: response.data.message,
                                fontsize: '20px'
                            });
                        } else {
                            this.$Progress.finish();
                            this.populateData(response.data);
                        }
                    }
                }).catch((error) => {
                    this.$notify({
                        group: 'auth',
                        title: 'Error',
                        type: 'error',
                        text: error,
                        fontsize: '20px'
                    });
                });
            },
            populateData(data) {
                this.flushData();
                this.populateIncomeData(data.incomes);
            },
            flushData() {
                this.incomes.splice(0,this.incomes.length);
            },
            populateIncomeData(incomes) {
                incomes.forEach((income)=>{
                    this.incomes.push({
                        'id':income.id,
                        'date':income.transaction_date,
                        'description':income.description,
                        'amount':income.amount
                    })
                })
            },
        },
        computed: {
            getTotalIncome() {
                let c = 0;
                this.incomes.forEach((item) => {
                    c += item.amount;
                });
                return c;
            }
        }
    }
</script>

<style scoped>

</style>
