<template>
    <v-app>
        <v-container fluid>
            <v-layout wrap>
                <v-flex md12>
                    <v-card dark color="primary" class="text-lg-start">
                        <v-card-actions>
                            <v-list-tile class="grow">
                                <v-list-tile-content>
                                    <v-list-tile-title>
                                        {{ reportName }}
                                    </v-list-tile-title>
                                </v-list-tile-content>
                                <v-layout align-center justify-end>
                                    <v-flex sm3 offset-sm1>
                                        <v-select color="light" label="Route"
                                                  single-line hide-details
                                                  :items="routes"
                                                  v-model="routesDef"
                                                  item-text="name"
                                                  item-value="id"
                                                  @change="changeRoute">
                                        </v-select>
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
                        <v-data-table :headers="headers" :items="out_loans" class="elevation-1" :search="search">
                            <template slot="items" slot-scope="props">
                                <tr :class="{'warning': props.item.is_exp===true}">
                                    <td>{{ props.item.loan_no }}</td>
                                    <td>{{ props.item.cus_no }}</td>
                                    <td>{{ props.item.cus_name }}</td>
                                    <td>{{ props.item.start_date }}</td>
                                    <td>{{ props.item.end_date }}</td>
                                    <td>{{ props.item.loan_amount }}</td>
                                    <td>{{ props.item.interest }}</td>
                                    <td>{{ props.item.duration }}</td>
                                    <td>{{ props.item.total_amount }}</td>
                                    <td>{{ props.item.due_amount }}</td>
                                    <td>{{ props.item.expired }}</td>
                                </tr>
                            </template>
                            <template slot="no-data">
                                <v-alert :value="true" color="error" icon="warning">
                                    Sorry, nothing to display here :(
                                </v-alert>
                            </template>
                            <template slot="footer">
                                <tr :style="{backgroundColor:'orange'}">
                                    <td colspan="3">
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
        name: "CustomerOutstandingReportComponent",
        data() {
            return {
                reportName: "Customer Outstanding Report",
                date: new Date().toISOString().substr(0, 10),
                menu: false,
                routes: [
                    {
                        id: -1,
                        name: "ALL"
                    }
                ],
                routesDef: {
                    id: -1,
                    name: "ALL"
                },
                search: [],
                out_loans: [],
                headers: [
                    {
                        text: 'Loan No',
                        align: 'left',
                        sortable: false,
                        value: 'loan_no'
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
                        text: 'Total Amount',
                        align: 'left',
                        sortable: false,
                        value: 'total_amount'
                    },
                    {
                        text: 'Due Amount',
                        align: 'left',
                        sortable: false,
                        value: 'due_amount'
                    },
                    {
                        text: 'Exp',
                        align: 'left',
                        sortable: false,
                        value: 'expired'
                    },
                ],
                labelTotalLoans:"No of Loans",
                labelTotalLoanAmount:"Total Loan Amount",
            }
        },
        created() {
            this.getInitialData();
            this.getOutstandingLoanDetailsForRoute(-1);
        },
        methods: {
            getInitialData() {
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.get('/getInitialDataCOReport').then((response) => {
                    if (response.status === 200) {
                        if (response.data.error) {
                            this.$notify({
                                group: 'auth',
                                title: 'Error',
                                type: 'error',
                                text: response.data.message,
                                fontsize: '20px'
                            });
                        } else {
                            console.log(response.data);
                            this.populateInitialData(response.data);
                        }
                    }
                }).catch((error) => {
                    this.$notify({
                        group: 'auth',
                        title: 'Error',
                        type: 'error',
                        text: "error occurred!",
                        fontsize: '20px',
                        position: 'top center'
                    });
                });
            },
            populateInitialData(data) {
                data.routes.forEach((route) => {
                    this.routes.push({id: route.id, name: route.name});
                });
            },
            changeRoute(route_id) {
                console.log(route_id);
                this.getOutstandingLoanDetailsForRoute(route_id);
            },
            getOutstandingLoanDetailsForRoute(route_id) {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/COReportByRoute', {
                    route_id: route_id
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
                this.populateOutstandingLoanData(data.outstanding_loans);
            },
            flushData() {
                this.out_loans.splice(0,this.out_loans.length);
            },
            populateOutstandingLoanData(outstanding_loans) {
                outstanding_loans.forEach((loan)=>{
                    this.out_loans.push({
                        'loan_no':loan.loan_no,
                        'cus_no':loan.customer_no,
                        'cus_name':loan.name,
                        'start_date':loan.start_date,
                        'end_date':loan.end_date,
                        'loan_amount':loan.loan_amount,
                        'interest':loan.interest_rate,
                        'duration':loan.duration,
                        'total_amount':loan.total_loan_amount,
                        'due_amount':loan.due_amount,
                        'expired':loan.exp_dates,
                        'is_exp':loan.is_exp
                    })
                })
            },
        },
        computed:{
            getNoOfLoans() {
                return this.out_loans.length;
            },
            getTotalLoanAmt() {
                let c = 0;
                this.out_loans.forEach((item) => {
                    c += item.total_amount;
                });
                return c;
            }
        }
    }
</script>

<style scoped>

</style>
