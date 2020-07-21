<template>
    <v-app>
        <v-container fluid>
            <v-layout row>
                <v-flex xs12 sm12 md12>
                    <v-card dark color="primary" class="text-lg-start">
                        <v-card-actions>
                            <v-list-tile class="grow">
                                <v-list-tile-content>
                                    <v-list-tile-title>
                                        {{ listName }}
                                    </v-list-tile-title>
                                </v-list-tile-content>
                                <v-layout align-center justify-end>
                                    <v-flex sm3 offset-sm1>
                                        <v-select color="light" label="Route" hide-details :items="routes" item-text="name"
                                                  item-value="id" v-model="selectedId" @change="selectRoute">
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
                        <v-data-table :headers="headers" :items="loans" class="elevation-1" :search="search">
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.cus_no }}</td>
                                <td>{{ props.item.loan_no }}</td>
                                <td>{{ props.item.loan_amount }}</td>
                                <td>{{ props.item.days }}</td>
                                <td>{{ props.item.interest }}</td>
                                <td>{{ props.item.total_loan_amount }}</td>
                                <td>{{ props.item.due_amount }}</td>
                            </template>
                            <template slot="footer">
                                <tr :style="{backgroundColor:'orange'}">
                                    <td colspan="1">
                                        <strong>{{ labelTotalLoanAmount }}</strong>
                                    </td>
                                    <td>
                                        {{total_loan_amt}}
                                    </td>
                                    <td colspan="2">
                                        <strong>{{ labelTotalWithInt }}</strong>
                                    </td>
                                    <td>
                                        {{totalValue}}
                                    </td>
                                    <td colspan="1">
                                        <strong>{{ labelTotalProfit }}</strong>
                                    </td>
                                    <td>
                                        {{totalProfit}}
                                    </td>
                                </tr>
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
        name: "CustomerLoanListComponent",
        data () {
            return{
                listName: "Loan List",
                headers: [
                    {
                        text: 'Customer No',
                        align: 'left',
                        sortable: false,
                        value: 'cus_no'
                    },
                    {
                        text: 'Loan No',
                        align: 'left',
                        sortable: false,
                        value: 'loan_no'
                    },
                    {
                        text: 'Loan Amount',
                        align: 'left',
                        sortable: false,
                        value: 'loan_amount'
                    },
                    {
                        text:'Days',
                        align:"left",
                        sortable:false,
                        value:'days'
                    },
                    {
                        text:'Interest Rate',
                        align:"left",
                        sortable:false,
                        value:'interest'
                    },
                    {
                        text:'Total Loan Amount',
                        align:"left",
                        sortable:false,
                        value:'total_loan_amount'
                    },
                    {
                        text:'Due Loan Amount',
                        align:"left",
                        sortable:false,
                        value:'due_amount'
                    }
                ],
                loans: [],
                sortLoan: [],
                search: [],
                routes: [
                    {
                        name:"ALL",
                        id: -1
                    }
                ],
                selectedId: 0,
                labelTotalLoanAmount:"Loans Out",
                total_loan_amt:0.0,
                totalValue:0.0,
                labelTotalWithInt:"Total Loan Amount(with interest)",
                totalProfit:0.0,
                labelTotalProfit:"Total Profit"
            }
        },

        methods: {
            getData() {
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.get('/getLoanList').then((response) => {
                    if (response.status === 200) {
                        if (!response.data.error) {
                            response.data.routes.forEach((route) => {
                                this.pushDefaultRoute(route.name, route.id)
                            });
                            response.data.loans.forEach((loanSet) => {
                                loanSet.loans.forEach((item) => {
                                    this.init(item.customer_no, item.loan_no, item.loan_amount, item.route_id, item.duration, item.interest_rate, item.total_loan_amount, item.due_amount);
                                })
                            });
                            this.getTotalLoansOut();
                        }
                    } else {
                        this.$notify({
                            group: 'auth',
                            title: 'Error',
                            type: 'error',
                            text: "error occurred!",
                            fontsize: '20px',
                            position: 'top center'
                        });
                    }
                }).catch((error) => {
                    console.log(error);
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
            init(cus_no, loan_no, loan_amount, route_id, days, interest_rate, total_loan_amount, due_amount) {
                this.loans.push({
                    cus_no: cus_no,
                    loan_no: loan_no,
                    loan_amount: loan_amount,
                    route_id: route_id,
                    days: days,
                    interest: interest_rate,
                    total_loan_amount: total_loan_amount,
                    due_amount:due_amount
                });
                this.sortLoan.push({
                    cus_no: cus_no,
                    loan_no: loan_no,
                    loan_amount: loan_amount,
                    route_id: route_id,
                    days: days,
                    interest: interest_rate,
                    total_loan_amount: total_loan_amount,
                    due_amount:due_amount
                })
            },
            pushDefaultRoute(name, id) {
                this.routes.push({
                    name: name,
                    id: id
                })
            },
            selectRoute(id) {
                if (id === -1) {
                    this.loans = this.sortLoan;
                    this.getTotalLoansOut();
                } else {
                    this.loans = this.sortLoan.filter((customer) => {
                        return customer.route_id === id;
                    });
                    this.getTotalLoansOut();
                }
            },
            getTotalLoansOut(){
                let totalLoan = 0;
                let totalLoanInterest = 0;
                this.loans.forEach((loan)=>{
                    totalLoan+=loan.loan_amount;
                    totalLoanInterest+=loan.total_loan_amount;
                });
                this.total_loan_amt = totalLoan;
                this.totalValue = totalLoanInterest;
                this.totalProfit = totalLoanInterest - totalLoan;
                this.totalProfit = Number(this.totalProfit.toFixed(2));
                this.totalProfit = Number(this.totalProfit.toFixed(2));
            }
        },
        created() {
            this.getData()
        }
    }
</script>

<style scoped>

</style>
