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
                                        <v-btn slot="activator" dark icon @click="getNewLoans">
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
                        <v-data-table :headers="headers" :items="new_loans" class="elevation-1" :search="search">
                            <template slot="items" slot-scope="props">
                                <tr>
                                    <td>{{ props.item.loan_no }}</td>
                                    <td>{{ props.item.start_date }}</td>
                                    <td>{{ props.item.end_date }}</td>
                                    <td>{{ props.item.cus_no }}</td>
                                    <td>{{ props.item.cus_name }}</td>
                                    <td>{{ props.item.loan_amount }}</td>
                                    <td>{{ props.item.interest }}</td>
                                    <td>{{ props.item.duration }}</td>
                                    <td>{{ props.item.installment }}</td>
                                    <td>{{ props.item.total_amount }}</td>
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
                                    <td colspan="2">
                                        <strong>{{ labelTotalLoans }}</strong>
                                    </td>
                                    <td>
                                        {{getNoOfLoans}}
                                    </td>
                                    <td colspan="4">
                                        <strong>{{labelTotalLoanAmount}}</strong>
                                    </td>
                                    <td>
                                        {{Number(getTotalLoanAmt).toLocaleString()}}
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
        // change this to select route to view new loans
        name: "NewLoanReportComponent",
        data() {
            return {
                compName:"New Loan Report",
                startDate: new Date().toISOString().substr(0,10),
                endDate: new Date().toISOString().substr(0,10),
                startDate1: new Date(),
                endDate1: new Date(),
                menu1: false,
                menu2: false,
                search:[],
                new_loans:[],
                headers: [
                    {
                        text: 'Loan No',
                        align: 'left',
                        sortable: false,
                        value: 'loan_no'
                    },
                    {
                        text: 'Start Date',
                        align: 'left',
                        sortable: false,
                        value: 'start_date'
                    },
                    {
                        text: 'End Date',
                        align: 'left',
                        sortable: false,
                        value: 'end_date'
                    },
                    {
                        text: 'Cus No',
                        align: 'left',
                        sortable: false,
                        value: 'cus_no'
                    },
                    {
                        text: 'Customer Name',
                        align: 'center',
                        sortable: false,
                        value: 'cus_name'
                    },

                    {
                        text: 'Loan Amount',
                        align: 'center',
                        sortable: false,
                        value: 'loan_amount'
                    },
                    {
                        text: 'Interest',
                        align: 'left',
                        sortable: false,
                        value: 'interest'
                    },
                    {
                        text: 'Days',
                        align: 'left',
                        sortable: false,
                        value: 'duration'
                    },
                    {
                        text: 'Installment',
                        align: 'left',
                        sortable: false,
                        value: 'installment'
                    },
                    {
                        text: 'Total Amount',
                        align: 'left',
                        sortable: false,
                        value: 'total_amount'
                    },
                ],
                labelTotalLoanAmount:"Total Loan Amount",
                labelTotalLoans:"No of Loans"
            }
        },
        created() {
            this.getNewLoans();
        },
        methods:{
            getNewLoans() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/NLReport', {
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
                this.populateNewLoanData(data.new_loans);
            },
            flushData() {
                this.new_loans.splice(0,this.new_loans.length);
            },
            populateNewLoanData(new_loans) {
                new_loans.forEach((loan)=>{
                    this.new_loans.push({
                        'loan_no':loan.loan_no,
                        'start_date':loan.start_date,
                        'end_date':loan.end_date,
                        'cus_no':loan.customer_no,
                        'cus_name':loan.name,
                        'loan_amount':loan.loan_amount,
                        'interest':loan.interest_rate,
                        'duration':loan.duration,
                        'installment':loan.installment_amount,
                        'total_amount':loan.total_loan_amount,
                    })
                })
            },
        },
        computed: {
            getNoOfLoans() {
                return this.new_loans.length;
            },
            getTotalLoanAmt () {
                let c = 0;
                this.new_loans.forEach((item) => {
                    c += item.total_amount;
                });
                return c;
            }
        }
    }
</script>

<style scoped>

</style>
