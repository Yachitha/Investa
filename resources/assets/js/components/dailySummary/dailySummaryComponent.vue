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
            <v-layout row class="mt-2">
                <v-flex xs12 sm12 md3 lg12>
                    <v-card dark color="special" class="text-white text-md-center">
                        <v-card-text class="text-simple">
                            Cash Book
                            <br>
                            {{c_book_amt}}
                        </v-card-text>
                    </v-card>
                </v-flex>
                <v-flex xs12 sm12 md3 lg12 ml-1>
                    <v-card dark color="special" class="text-white text-md-center">
                        <v-card-text class="text-simple">
                            Bank Book
                            <br>
                            {{b_book_amt}}
                        </v-card-text>
                    </v-card>
                </v-flex>
                <v-flex xs12 sm12 md3 lg12 ml-1>
                    <v-card dark color="special" class="text-white text-md-center">
                        <v-card-text class="text-simple">
                            Total Collections
                            <br>
                            {{total_col}}
                        </v-card-text>
                    </v-card>
                </v-flex>
                <v-flex xs12 sm12 md3 lg12 ml-1>
                    <v-card dark color="special" class="text-white text-md-center">
                        <v-card-text class="text-simple">
                            Expenses
                            <br>
                            {{total_ex}}
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout row mt-2>
                <v-flex xs12 sm12 md9 lg9>
                    <v-card light color="dark">
                        <v-card-title class="blue--text text-lg-start">New Loans</v-card-title>
                        <v-data-table :headers="headers" :items="new_loans" class="elevation-1">
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.no }}</td>
                                <td>{{ props.item.name }}</td>
                                <td>{{ props.item.nic }}</td>
                                <td>{{ props.item.loan_amount }}</td>
                                <td>{{ props.item.interest_rate }}</td>
                                <td>{{ props.item.total_loan_amount }}</td>
                                <td>{{ props.item.route }}</td>
                                <td>{{ props.item.sRep }}</td>
                            </template>
                            <template slot="no-data">
                                <v-alert :value="true" color="error" icon="warning">
                                    Sorry, nothing to display here :(
                                </v-alert>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-flex>
                <v-flex xs12 sm12 md3 lg3 ml-1>
                    <v-layout column>
                        <v-flex xs12 sm12 md12 lg12>
                            <v-card>
                                <v-layout row class="pt-3 special">
                                    <v-flex xs12 sm12 md6>
                                        <v-card-title class="white--text text-lg-start">Loan Payments</v-card-title>
                                        <v-card-text class="white--text">Total: {{routeCol}}</v-card-text>
                                    </v-flex>
                                    <v-spacer></v-spacer>
                                    <v-flex xs12 sm12 md4 class="pr-2">
                                        <v-select outline label="Route" :items="routes"
                                                  item-text="name" item-value="id" v-model="selectedId"
                                                  @change="selectRoute" height="12"></v-select>
                                    </v-flex>
                                </v-layout>
                                <v-layout row>
                                    <v-flex xs12 sm12 md12 lg12>
                                        <v-data-table :items="filteredCollections" :headers="rep_hed" class="elevation-1">
                                            <template slot="items" slot-scope="props">
                                                <tr @click="onClickRepItem(props.item)">
                                                    <td>{{ props.item.cust_no }}</td>
                                                    <td>{{ props.item.loan_no }}</td>
                                                    <td>{{ props.item.amount }}</td>
                                                </tr>
                                            </template>
                                            <template slot="no-data">
                                                <v-alert :value="true" color="error" icon="warning">
                                                    Sorry, nothing to display here :(
                                                </v-alert>
                                            </template>
                                        </v-data-table>
                                    </v-flex>
                                </v-layout>
                            </v-card>
                        </v-flex>
                        <v-flex xs12 sm12 md12 lg12 mt-1>
                            <v-card dark color="secondary">
                                <v-layout row>
                                    <v-flex xs12 sm12 md6>
                                        <v-card-title class="white--text text-lg-start">Expenses</v-card-title>
                                    </v-flex>
                                </v-layout>
                                <v-layout row>
                                    <v-data-table :headers="ex_headers" :items="expenses" class="elevation-1">
                                        <template slot="items" slot-scope="props">
                                            <td>{{ props.item.id }}</td>
                                            <td>{{ props.item.description }}</td>
                                            <td>{{ props.item.amount }}</td>
                                        </template>
                                        <template slot="no-data">
                                            <v-alert :value="true" color="error" icon="warning">
                                                Sorry, nothing to display here :(
                                            </v-alert>
                                        </template>
                                    </v-data-table>
                                </v-layout>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-flex>
            </v-layout>
            <v-layout row mt-2>
                <v-flex wrap>
                    <v-card class="bg-gradient">
                        <v-layout row p-2>
                            <v-flex>
                                <v-card-title class="white--text summary-text">Summary</v-card-title>
                            </v-flex>
                        </v-layout>
                        <v-layout row p-2>
                            <v-flex xs12 sm12 md4>
                                <v-card-text class="summary-sub-text">Cash In</v-card-text>
                                <v-divider></v-divider>
                                <v-list dense class="bg-special white--text">
                                    <v-list-tile>
                                        <v-list-tile-content>Received Payments:</v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{total_col}}</v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </v-flex>
                            <v-divider class="mx-3" inset vertical></v-divider>
                            <v-flex xs12 sm12 md4>
                                <v-card-text class="summary-sub-text">Cash Out</v-card-text>
                                <v-divider></v-divider>
                                <v-list dense class="bg-special white--text">
                                    <v-list-tile light>
                                        <v-list-tile-content>Total Expenses:</v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{total_ex}}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile light>
                                        <v-list-tile-content>Loans Given:</v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{new_loans_total}}</v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </v-flex>
                            <v-divider class="mx-3" inset vertical></v-divider>
                            <v-flex xs12 sm12 md4>
                                <v-card-text class="summary-sub-text">Balance</v-card-text>
                                <v-divider></v-divider>
                                <v-list dense class="bg-special white--text">
                                    <v-list-tile light>
                                        <v-list-tile-content>Final Balance:(Cash In - Cash Out)</v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{calculateFinalBal}}</v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </v-flex>
                        </v-layout>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout row justify-center>
                <v-dialog v-model="editItemDialog" max-width="400" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            Edit payment amount: {{itemCusNo}}
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            <v-text-field v-model="repAmount" solo label="amount"></v-text-field>
                        </v-card-text>
                        <v-card-actions class="text-center">
                            <v-btn color="green darken-1" @click="editItemSubmit" flat="flat">OK</v-btn>
                            <v-btn color="red darken-1" @click="editItemDialog=false" flat="flat">Cancel</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
        </v-container>
    </v-app>
</template>

<script>
    export default {
        name: "dailySummaryComponent",
        data() {
            return {
                compName: "Daily Summary",
                headers: [
                    {
                        text: "Customer No",
                        sortable: false,
                        align: 'left',
                        value: 'no'
                    },
                    {
                        text: "Customer Name",
                        sortable: false,
                        align: 'left',
                        value: 'name'
                    },
                    {
                        text: "NIC",
                        sortable: false,
                        align: 'left',
                        value: 'nic'
                    },
                    {
                        text: "Loan Amount",
                        sortable: false,
                        align: 'left',
                        value: 'lAmount'
                    },
                    {
                        text: "Interest Rate",
                        sortable: false,
                        align: 'left',
                        value: 'IntRate'
                    },
                    {
                        text: "Total Amount",
                        sortable: false,
                        align: 'left',
                        value: 'tAmount'
                    },
                    {
                        text: "Route",
                        sortable: false,
                        align: 'left',
                        value: 'route'
                    },
                    {
                        text: "Sales Rep",
                        sortable: false,
                        align: 'left',
                        value: 'sRep'
                    }
                ],
                salesReps: [],
                new_loans: [],
                repayments: [],
                routes: [{
                    id: -1,
                    name: "ALL"
                }],
                selectedId: {
                    id: -1,
                    name: "ALL"
                },
                rep_hed: [
                    {
                        text: "Customer No",
                        sortable: false,
                        align: 'left',
                        value: 'cust_no'
                    },
                    {
                        text: "Loan No",
                        sortable: false,
                        align: 'left',
                        value: 'loan_no'
                    },
                    {
                        text: "Amount",
                        sortable: false,
                        align: 'left',
                        value: 'amount'
                    },
                ],
                expenses: [],
                ex_headers: [
                    {
                        text: "Expense id",
                        sortable: false,
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: "Description",
                        sortable: false,
                        align: 'left',
                        value: 'description'
                    },
                    {
                        text: "Amount",
                        sortable: false,
                        align: 'left',
                        value: 'amount'
                    },
                ],
                total_ex: 0.00,
                total_col: 0.00,
                c_book_amt: 0.00,
                b_book_amt: 0.00,
                new_loans_total: 0.00,
                colRouteId: -1,
                routeCol: 0,
                editItemDialog: false,
                itemCusNo:0,
                repAmount:0,
                itemLoanNum:0,
                menu: false,
                date: new Date().toISOString().substr(0, 10),
            }
        },
        methods: {
            getData() {
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.get('/getInitialDataDSummary').then((response) => {
                    if (response.status === 200) {
                        console.log(response);
                        if (response.data.error) {
                            this.$notify({
                                group: 'auth',
                                title: 'Error',
                                type: 'error',
                                text: response.data.message,
                                fontsize: '20px'
                            });
                        } else {
                            this.populateSum(response.data.new_loans, response.data.repayments, response.data.routes, response.data.expenses, response.data.extras);
                        }

                    }
                }).catch((error) => {
                    console.log(error);
                    this.$notify({
                        group: 'auth',
                        title: 'Error',
                        type: 'error',
                        text: error,
                        fontsize: '20px'
                    });
                });
            },
            populateSum(loans, repayments, routes, expenses, extras) {
                loans.forEach((loan) => {
                    this.new_loans.push(loan)
                });

                repayments.forEach((repayment) => {
                    this.repayments.push(repayment)
                });

                routes.forEach((route) => {
                    this.routes.push(route)
                });

                expenses.forEach((expense) => {
                    this.expenses.push(expense)
                });

                this.c_book_amt = extras.c_book_amt;
                this.b_book_amt = extras.b_book_amt;
                this.total_col = extras.total_col;
                this.total_ex = extras.total_ex;
                this.new_loans_total = extras.total_loan_amt;
            },
            flushData() {
                this.new_loans.splice(0, this.new_loans.length);
                this.repayments.splice(0, this.repayments.length);
                this.routes.splice(0, this.routes.length);
                this.expenses.splice(0, this.expenses.length);
                this.c_book_amt = 0;
                this.b_book_amt = 0;
                this.total_col = 0;
                this.total_ex = 0;
                this.new_loans_total = 0;
            },
            selectRoute(id) {
                this.colRouteId = id;
            },
            onClickRepItem(item) {
                this.itemCusNo = item.cust_no;
                this.repAmount = item.amount;
                this.itemLoanNum = item.loan_no;
                this.editItemDialog=true;
            },
            editItemSubmit() {
                this.editItemDialog = false;
                this.requestEditRepayment();
            },
            selectedDate(date) {
                this.$refs.menu.save(date);
                this.requestDataByDate();
            },
            requestDataByDate() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/dailySummaryDataByDate', {
                    date: this.date
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
                            this.flushData();
                            this.populateSum(response.data.new_loans, response.data.repayments, response.data.routes, response.data.expenses, response.data.extras);
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
            requestEditRepayment() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/editRepaymentByNum', {
                    date: this.date,
                    loan_no: this.itemLoanNum,
                    new_amount: this.repAmount
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
                            this.$notify({
                                group: 'auth',
                                title: 'Success',
                                type: 'success',
                                text: response.data.message,
                                fontsize: '20px'
                            });
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
            }
        },
        created() {
            this.getData();
        },
        computed:{
            calculateFinalBal: function() {
                return (this.total_col)-(this.total_ex + this.new_loans_total);
            },
            filteredCollections: function () {
                let list = this.repayments;
                let col = 0;
                if(this.colRouteId !== -1){
                    list = list.filter(item => item.route_id === this.colRouteId)
                }
                list.forEach((item)=>{
                    col += parseFloat(item.amount);
                });
                this.routeCol = col;

                return list;
            }
        }
    }
</script>

<style scoped>
    .text-simple {
        font-size: 24px;
        padding: 16px;
        font-weight: bold;
        color: #FFFFFF;
        text-align: center;
    }

    .summary-text {
        font-size: 18px !important;
        padding: 16px;
        font-weight: bold;
        color: #FFFFFF;
    }

    .summary-sub-text {
        font-size: 16px !important;
        padding: 16px;
        font-weight: bold;
        color: #ffffff;
    }

    .bg-special {
         background-repeat: inherit;
         background-position: inherit;
         background-image: inherit;
         background-color: inherit;
         background-attachment: inherit;
     }

    .bg-gradient{
        background: rgb(97,9,163);
        background: linear-gradient(90deg, rgba(97,9,163,0.9836309523809523) 0%, rgba(123,46,200,1) 21%, rgba(122,125,183,1) 100%);
    }
</style>
