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
                                    <v-flex sm3 md3 offset-sm1>
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
                                            max-width="290px"
                                            min-width="290px"
                                        >
                                            <v-text-field slot="activator" v-model="date" label="Select Month"
                                                          prepend-icon="event" readonly hide-details></v-text-field>
                                            <v-date-picker v-model="date" type="month" scrollable>
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
                        <v-data-table :headers="headers" :items="sheets" class="elevation-1" :search="search">
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.date }}</td>
                                <td>{{ props.item.bfAmount }}</td>
                                <td>{{ props.item.loanPayment }}</td>
                                <td>{{ props.item.supCashLoan }}</td>
                                <td>{{ props.item.supChequeLoan }}</td>
                                <td>{{ props.item.extraIncome }}</td>
                                <td>{{ props.item.income }}</td>
                                <td>{{ props.item.loanCash }}</td>
                                <td>{{ props.item.loanCheque }}</td>
                                <td>{{ props.item.salary }}</td>
                                <td>{{ props.item.supCashPay }}</td>
                                <td>{{ props.item.supChequePay }}</td>
                                <td>{{ props.item.extraExpenses }}</td>
                                <td>{{ props.item.expenses }}</td>
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
            <v-layout row mt-2>
                <v-flex wrap>
                    <v-card class="bg-gradient">
                        <v-layout row>
                            <v-flex xs12 sm12 md3>
                                <v-list class="dense bg-special white--text">
                                    <v-list-tile>
                                        <v-list-tile-content>Loan Payment</v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{loanPayment}}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>S/Cash Loan</v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{supCashLoan}}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>S/Cheque Loan</v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{supCheLoan}}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>Extra Income</v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{extraIncome}}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>Income</v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{income}}</v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </v-flex>
                            <v-divider class="mx-3" inset vertical></v-divider>
                            <v-flex xs12 sm12 md3>
                                <v-list class="dense bg-special white--text">
                                    <v-list-tile>
                                        <v-list-tile-content>Loan Cash</v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{loanCash}}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>Loan Cheque</v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{loanCheque}}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>Salary Payment</v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{salaryPayment}}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>S/Cash Payment</v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{supCashPayment}}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>S/Cheque Payment</v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{supChequePayment}}</v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </v-flex>
                            <v-divider class="mx-3" inset vertical></v-divider>
                            <v-flex xs12 sm12 md3>
                                <v-list class="dense bg-special white--text">
                                    <v-list-tile>
                                        <v-list-tile-content>Extra Expenses</v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{extraExpenses}}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>Expenses</v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{expenses}}</v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </v-flex>
                            <v-divider class="mx-3" inset vertical></v-divider>
                            <v-flex xs12 sm12 md3>
                                <v-list class="dense bg-special white--text">
                                    <v-list-tile>
                                        <v-list-tile-content>Monthly Income</v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{monthlyIncome}}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>Monthly Deposits</v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{monthlyDeposits}}</v-list-tile-content>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile>
                                        <v-list-tile-content>Monthly Profit</v-list-tile-content>
                                        <v-list-tile-content class="align-end">{{monthlyProfit}}</v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </v-flex>
                        </v-layout>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </v-app>
</template>

<script>
    export default {
        name: "MonthlySheetComponent",
        data() {
            return{
                reportName: "MONTHLY SHEET",
                menu: false,
                date: new Date().toISOString().substr(0, 7),
                search:[],
                headers:[
                    {
                        text: 'Date',
                        align: 'left',
                        sortable: false,
                        value: 'date'
                    },
                    {
                        text: 'BF Amount',
                        align: 'left',
                        sortable: false,
                        value: "bfAmount"
                    },
                    {
                        text: 'Loan Payment',
                        align: 'left',
                        sortable: false,
                        value: 'loanPayment'
                    },
                    {
                        text: 'S/Cash Loan',
                        align: 'left',
                        sortable: false,
                        value: "supCashLoan"
                    },
                    {
                        text: 'S/Cheque Loan',
                        align: 'left',
                        sortable: false,
                        value: 'supChequeLoan'
                    },
                    {
                        text: 'Extra Income',
                        align: 'left',
                        sortable: false,
                        value: "extraIncome"
                    },
                    {
                        text: 'Income',
                        align: 'left',
                        sortable: false,
                        value: 'income'
                    },
                    {
                        text: 'Loan Cash',
                        align: 'left',
                        sortable: false,
                        value: "loanCash"
                    },
                    {
                        text: 'Loan Cheque',
                        align: 'left',
                        sortable: false,
                        value: 'loanCheque'
                    },
                    {
                        text: 'Salary',
                        align: 'left',
                        sortable: false,
                        value: "salary"
                    },
                    {
                        text: 'S/Cash Pay',
                        align: 'left',
                        sortable: false,
                        value: 'supCashPay'
                    },
                    {
                        text: 'S/Cheque Pay',
                        align: 'left',
                        sortable: false,
                        value: "supChequePay"
                    },
                    {
                        text: 'Extra Expenses',
                        align: 'left',
                        sortable: false,
                        value: 'extraExpenses'
                    },
                    {
                        text: 'Expenses',
                        align: 'left',
                        sortable: false,
                        value: "expenses"
                    },
                    {
                        text: 'Total Income',
                        align: 'left',
                        sortable: false,
                        value: "totalIncome"
                    },
                    {
                        text: 'Bank Deposits',
                        align: 'left',
                        sortable: false,
                        value: "bankDeposits"
                    },
                    {
                        text: 'Day Cash',
                        align: 'left',
                        sortable: false,
                        value: "dayCash"
                    },
                    {
                        text: 'Cash Out',
                        align: 'left',
                        sortable: false,
                        value: "cashOut"
                    },
                    {
                        text: 'Balance',
                        align: 'left',
                        sortable: false,
                        value: "balance"
                    },
                    {
                        text: 'Cash In Locker',
                        align: 'left',
                        sortable: false,
                        value: "cashInLocker"
                    },
                    {
                        text: 'Day Profit',
                        align: 'left',
                        sortable: false,
                        value: "dayProfit"
                    }
                ],
                sheets:[],
                loanPayment:0,
                supCashLoan:0,
                supCheLoan:0,
                extraIncome:0,
                income:0,
                loanCash:0,
                loanCheque:0,
                salaryPayment:0,
                supCashPayment:0,
                supChequePayment:0,
                extraExpenses:0,
                expenses:0,
                monthlyIncome:0,
                monthlyDeposits:0,
                monthlyProfit:0
            }
        },
        created() {
            this.getInitialData();
        },
        methods:{
            getInitialData(){
                this.getDataByMonth();
            },
            selectedDate(date){
                this.$refs.menu.save(date);
                this.getDataByMonth();
            },
            getDataByMonth(){
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/getMonthlySheetData', {
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
                            this.publishData(response.data);
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
            flushData(){
                this.sheets.splice(0,this.sheets.length);
            },
            publishData(data){
                this.flushData();
                this.populateSheetData(data.sheets);
                this.populateSummaries(data.summaries);
            },
            populateSheetData(sheets){
                sheets.forEach((sheet)=>{
                    this.sheets.push({
                        'date':sheet.date,
                        'bfAmount':sheet.bf_amount,
                        'loanPayment':sheet.loan_payment,
                        'supCashLoan':sheet.sup_cash_loan,
                        'supChequeLoan':sheet.sup_che_loan,
                        'extraIncome':sheet.e_income,
                        'income':sheet.income,
                        'loanCash':sheet.loan_cash,
                        'loanCheque':sheet.loan_che,
                        'salary':sheet.salary_payment,
                        'supCashPay':sheet.sup_loan_cash_pay,
                        'supChequePay':sheet.sup_loan_che_pay,
                        'extraExpenses':sheet.ex_expenses,
                        'expenses':sheet.expenses,
                        'totalIncome':sheet.total_income,
                        'bankDeposits':sheet.bank_deposits,
                        'dayCash':sheet.day_cash,
                        'cashOut':sheet.cash_out,
                        'balance':sheet.balance,
                        'cashInLocker':sheet.cash_in_locker,
                        'dayProfit':sheet.day_profit
                    });
                });
            },
            populateSummaries(summaries){
                this.loanPayment = summaries.loan_payment;
                this.supCashLoan = summaries.sup_cash_loan;
                this.supCheLoan = summaries.sup_che_loan;
                this.extraIncome = summaries.extra_income;
                this.income = summaries.income;
                this.loanCash = summaries.loan_cash;
                this.loanCheque = summaries.loan_che;
                this.salaryPayment = summaries.salary_payment;
                this.supCashPayment = summaries.sup_cash_pay;
                this.supChequePayment = summaries.sup_che_pay;
                this.extraExpenses = summaries.extra_expenses;
                this.expenses = summaries.expenses;
                this.monthlyIncome = summaries.monthly_income;
                this.monthlyDeposits = summaries.monthly_deposit;
                this.monthlyProfit = summaries.monthly_profit;
            }
        }
    }
</script>

<style scoped>
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
