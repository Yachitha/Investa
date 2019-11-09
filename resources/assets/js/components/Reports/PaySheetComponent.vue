<!--suppress ALL -->
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
                                            <v-text-field slot="activator" v-model="date" label="Payment Month"
                                                          prepend-icon="event" readonly hide-details></v-text-field>
                                            <v-date-picker v-model="date" type="month" scrollable>
                                                <v-spacer></v-spacer>
                                                <v-btn flat color="primary" @click="menu = false">Cancel</v-btn>
                                                <v-btn flat color="primary" @click="$refs.menu.save(date)">OK</v-btn>
                                            </v-date-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                            </v-list-tile>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout row wrap>
                <v-flex d-flex xs12 sm4 md4 lg4 mt-2>
                    <v-card color="dark" light>
                        <v-layout column wrap pr-1 pl-2>
                            <v-flex d-flex>
                                <v-layout row align-center>
                                    <v-flex d-flex mt-3 sm3 md3>
                                        <p>{{ labelSalesRepName }}</p>
                                    </v-flex>
                                    <v-flex sm9 md9 offset-sm1 pb-2>
                                        <v-select color="light" label="sales rep name" single-line hide-details
                                                  :items="salesReps" item-text="name" item-value="id" @change="onChangeRep">
                                        </v-select>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex>
                                <v-layout row align-center>
                                    <v-flex d-flex mt-3 sm3 md3>
                                        <p>{{ labelBasicSalary }}</p>
                                    </v-flex>
                                    <v-flex sm9 md9 offset-sm1 pb-2>
                                        <v-text-field solo outline hide-details disabled v-model="basic_salry"
                                                      height="12"></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex>
                                <v-layout row align-center>
                                    <v-flex d-flex mt-3 sm3 md3>
                                        <p>{{ labelLoanCommission }}</p>
                                    </v-flex>
                                    <v-flex sm9 md9 offset-sm1 pb-2>
                                        <v-text-field solo outline hide-details disabled v-model="loan_commission"
                                                      height="12"></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex>
                                <v-layout row align-center>
                                    <v-flex d-flex mt-3 sm3 md3>
                                        <p>{{ labelTotal }}</p>
                                    </v-flex>
                                    <v-flex sm9 md9 offset-sm1 pb-2>
                                        <v-text-field solo outline hide-details disabled v-model="total"
                                                      height="12"></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex>
                                <v-layout row align-center>
                                    <v-flex d-flex mt-3 sm3 md3>
                                        <p>{{ labelPayments }}</p>
                                    </v-flex>
                                    <v-flex sm9 md9 offset-sm1 pb-2>
                                        <v-text-field solo outline hide-details disabled v-model="payment"
                                                      height="12"></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex>
                                <v-layout row align-center>
                                    <v-flex d-flex mt-3 sm3 md3>
                                        <p>{{ labelTotalSalary }}</p>
                                    </v-flex>
                                    <v-flex sm9 md9 offset-sm1 pb-2>
                                        <v-text-field solo outline hide-details disabled v-model="total_salary"
                                                      height="12"></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-divider light></v-divider>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-btn color="green darken-1" dark @click="onclickAddPayment">Add Payment</v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-divider light></v-divider>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2 mb-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-data-table :headers="headers" :items="payments" class="elevation-1">
                                            <template slot="items" slot-scope="props">
                                                <td>{{ props.item.date }}</td>
                                                <td class="text-xs-left">{{ props.item.payment }}</td>
                                            </template>
                                        </v-data-table>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-card>
                </v-flex>
                <v-flex d-flex xs12 sm8 md8 mt-2>
                    <v-card color="dark" light>
                        <v-layout column wrap pl-1 pr-1>
                            <v-flex d-felx>
                                <v-data-table :headers="mainTableHeaders" :items="employeeCollections" class="elevation-1">
                                    <template slot="items" slot-scope="props">
                                        <td>{{ props.item.code }}</td>
                                        <td>{{ props.item.loanNo }}</td>
                                        <td>{{ props.item.customerName }}</td>
                                        <td>{{ props.item.loanAmount }}</td>
                                        <td>{{ props.item.percentage }}</td>
                                        <td>{{ props.item.commission }}</td>
                                    </template>
                                    <template slot="footer">
                                        <tr :style="{backgroundColor:'orange'}">
                                            <td colspan="2">
                                                <strong>{{ labelTotalLoanAmount }}</strong>
                                            </td>
                                            <td>
                                                {{total_loan_amt}}
                                            </td>
                                            <td colspan="2">
                                                <strong>{{ labelTotalLoanCommission }}</strong>
                                            </td>
                                            <td>
                                                {{total_commission_amt}}
                                            </td>
                                        </tr>
                                    </template>
                                </v-data-table>
                            </v-flex>
                        </v-layout>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout row justify-center>
                <v-dialog v-model="makePaymentDialog" max-width="700" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            {{addLoanPayment}}
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-flex>
                            <v-layout row mt-1 ml-4>
                                <v-flex>
                                    <v-radio-group v-model="type_id" row>
                                        <template v-slot:label>
                                            <div>Please select a <strong>payment method: </strong></div>
                                        </template>
                                        <v-radio label="Cash" :value=1 color="indigo"></v-radio>
                                        <v-radio label="Bank" :value=2 color="indigo"></v-radio>
                                        <v-radio label="Both" :value=-1 color="indigo"></v-radio>
                                    </v-radio-group>
                                </v-flex>
                            </v-layout>
                            <v-layout column mt-2>
                                <v-flex ml-4 mr-4>
                                    <v-menu
                                        ref="menu2"
                                        :close-on-content-click="false"
                                        v-model="menu2"
                                        :nudge-right="40"
                                        :return-value.sync="paymentDate"
                                        lazy
                                        transition="scale-transition"
                                        offset-y
                                        full-width
                                        min-width="290px"
                                    >
                                        <v-text-field
                                            slot="activator"
                                            v-model="paymentDate"
                                            prepend-icon="event"
                                            readonly
                                            label="Payment Date"
                                        ></v-text-field>
                                        <v-date-picker v-model="paymentDate" no-title scrollable>
                                            <v-spacer></v-spacer>
                                            <v-btn flat color="primary" @click="menu2 = false">Cancel</v-btn>
                                            <v-btn flat color="primary" @click="$refs.menu2.save(paymentDate)">OK
                                            </v-btn>
                                        </v-date-picker>
                                    </v-menu>
                                </v-flex>
                                <v-flex d-flex ml-4 mr-4>
                                    <v-text-field outline label="Cash" placeholder="Cash" clearable v-model="cash_amount" v-show="cash"></v-text-field>
                                    <v-select outline color="light" label="bank account" single-line
                                        :items="bank_accounts" item-text="no" item-value="id" @change="onChangeAcc" v-show="bank">
                                    </v-select>
                                    <v-text-field outline label="Bank" placeholder="Bank" clearable v-model="bank_amount" v-show="bank"></v-text-field>
                                    <v-text-field outline label="Cheque No" placeholder="Cheque_no" clearable v-model="cheque_no" v-show="bank"></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red" dark @click="makePaymentDialog=false">Cancel</v-btn>
                            <v-btn color="green darken-1" @click="submitPayment" flat="flat">OK</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
        </v-container>
    </v-app>
</template>

<script>
    export default {
        name: "PaySheetComponent",
        data() {
            return {
                reportName: "PAY SHEET",
                date: new Date().toISOString().substr(0, 7),
                paymentDate: new Date().toISOString().substr(0, 10),
                menu: false,
                modal: false,
                menu2: false,
                labelSalesRepName: "Sales Rep",
                labelBasicSalary: "Basic Salary",
                labelLoanCommission: "Loan Commission",
                labelTotal: "Total",
                labelPayments: "Payment",
                labelTotalSalary: "Total Salary",
                labelTotalLoanAmount: "Total Loan Amount",
                labelTotalLoanCommission: "Total Commission",
                salesReps: [],
                headers: [
                    {
                        text: 'Payment Date',
                        align: 'left',
                        sortable: false,
                        value: 'date'
                    },
                    {
                        text: 'Payment',
                        value: 'payment',
                        sortable: false,
                        align: "left"
                    }
                ],
                mainTableHeaders: [
                    {
                        text: 'Code',
                        align: 'left',
                        sortable: false,
                        value: 'code'
                    },
                    {
                        text: 'Loan No',
                        align: 'left',
                        sortable: false,
                        value: 'loanNo'
                    },
                    {
                        text: 'Customer Name',
                        align: 'center',
                        sortable: false,
                        value: 'customerName'
                    },
                    {
                        text: 'Loan Amount',
                        align: 'center',
                        sortable: false,
                        value: 'loanAmount'
                    },
                    {
                        text: 'comm(%)',
                        align: 'center',
                        sortable: false,
                        value: 'percentage'
                    },
                    {
                        text: 'Commission',
                        align: 'center',
                        sortable: false,
                        value: 'commission'
                    }
                ],
                payments: [],
                employeeCollections: [],
                basic_salry:0,
                loan_commission:0,
                total:0,
                payment:0,
                total_salary:0,
                makePaymentDialog:false,
                total_loan_amt: 0.0,
                total_commission_amt:0.0,
                addLoanPayment:"Add Salary Payment",
                newPayment:0,
                salesRep_id:0,
                type_id: 1,
                cash_amount:0.0,
                bank_amount:0.0,
                bank_acc_id: -1,
                cheque_no: 0,
                cash:true,
                bank:false,
                bank_accounts:[]
            }
        },
        created () {
            this.getInitialPaySheetData();
        },
        watch: {
            type_id: function (newVal, oldVal) {
                console.log(this.type_id)
                if (newVal === 1) {
                    this.cash = true;
                    this.bank = false;
                } else if(newVal === 2) {
                    this.bank = true;
                    this.cash = false;
                } else {
                    this.cash = true;
                    this.bank = true;
                }
            }
        },
        methods: {
            getInitialPaySheetData(){
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.get('/getInitialDataPaySheet').then((response) => {
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
                data.sales_reps.forEach((rep)=>{
                    this.salesReps.push({id:rep.id,name:rep.name});
                });
                data.bank_accounts.forEach((acc)=>{
                    this.bank_accounts.push({id:acc.id,no:account_no});
                })
            },
            getDataBySalesRep(sales_rep_id) {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/getPayData', {
                    date: this.date,
                    sales_rep_id: sales_rep_id
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
                            this.populatePayData(response.data);
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
            populatePayData(data) {
                this.setBasicSalary(data.basic_salary);
                this.setTotalCommission(data.loan_commission);
                this.setTotal(data.total);
                this.setPayment(data.payment);
                this.setTotalSalary(data.total_salary);
                this.flushPaymentsNCommissions();
                this.setPayments(data.salary_payments);
                this.setCommissions(data.commissions);
            },
            setBasicSalary(salary) {
                this.basic_salry = salary;
            },
            setTotalCommission(total) {
                this.loan_commission = total;
            },
            setTotal(total){
                this.total = total;
            },
            setPayment(payment){
                this.payment = payment;
            },
            setTotalSalary(salary){
                this.total_salary = salary;
            },
            flushPaymentsNCommissions(){
                this.payments.splice(0, this.payments.length);
                this.employeeCollections.splice(0, this.employeeCollections.length);
                this.total_commission_amt = 0.0;
                this.total_loan_amt = 0.0;
            },
            setPayments(payments){
                payments.forEach((payment)=>{
                    this.payments.push({value:false,date: payment.date, payment:payment.amount});
                });
            },
            setCommissions(commissions) {
                let total_loan = 0;
                let total_comm = 0;
                commissions.forEach((commission)=>{
                    this.employeeCollections.push({
                        value:false,
                        code:commission.customer.customer_no,
                        loanNo:commission.loan.loan_no,
                        customerName:commission.customer.name,
                        loanAmount:commission.loan.loan_amount,
                        percentage:commission.commission_rate,
                        commission:commission.commission_amount
                    });
                    total_comm += commission.commission_amount;
                    total_loan += commission.loan.loan_amount;
                });
                this.total_loan_amt = total_loan;
                this.total_commission_amt = total_comm;
            },
            onChangeRep(id) {
                this.salesRep_id = id;
                this.getDataBySalesRep(id);
            },
            submitPayment() {
                if (this.validatePaymentReq()) {
                    this.addSalaryPayment();
                    this.makePaymentDialog = false;
                }
            },
            validatePaymentReq(){
                if (this.type_id === 2 || this.type_id === -1) {
                    if(this.bank_acc_id === -1) {
                        this.$notify({
                            group: 'auth',
                            title: 'Error',
                            type: 'error',
                            text: "please select an account to proceed",
                            fontsize: '20px'
                        });

                        return false;
                    } else {
                        if (this.bank_amount<=0) {
                            this.$notify({
                                group: 'auth',
                                title: 'Error',
                                type: 'error',
                                text: "please enter valid amount",
                                fontsize: '20px'
                            });
                            return false;
                        }
                    }
                } else {
                    if (this.cash_amount <= 0) {
                        this.$notify({
                            group: 'auth',
                            title: 'Error',
                            type: 'error',
                            text: "please enter valid amount",
                            fontsize: '20px'
                        });

                        return false
                    }
                }

                return true;
            },
            addSalaryPayment() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/addSalaryPayment', {
                    date: this.paymentDate,
                    sales_rep_id: this.salesRep_id,
                    payment: parseFloat(this.cash_amount)+parseFloat(this.bank_amount),
                    type_id: this.type_id,
                    cash_amount:parseFloat(this.cash_amount),
                    bank_acc_id:this.bank_acc_id,
                    cheque_no:this.cheque_no
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
                            this.populateNewPayData(response.data);
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
            populateNewPayData(data){
                this.payment = data.payment;
                this.total_salary = this.total-this.payment;

                this.flushPaymentTable();
                this.setPayments(data.payments);
            },
            flushPaymentTable() {
                this.payments.splice(0, this.payments.length);
            },
            onChangeAcc(id) {
                this.bank_acc_id = id;
            },
            onclickAddPayment() {
                if (this.salesRep_id !== 0){
                    this.makePaymentDialog=true;
                } else {
                    this.$notify({
                        group: 'auth',
                        title: 'Error',
                        type: 'error',
                        text: "Please select a sales Rep first",
                        fontsize: '20px'
                    });
                }
            }
        }
    }
</script>

<style scoped>

</style>
