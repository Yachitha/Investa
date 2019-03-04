<!--suppress JSUnresolvedVariable -->
<template>
    <v-app>
        <v-container fluid grid-list-md>
            <v-layout row wrap>
                <v-flex xs12 sm12 md12>
                    <v-card dark color="primary" class="text-lg-start">
                        <v-card-actions>
                            <v-list-tile class="grow">
                                <v-list-tile-content>
                                    <v-list-tile-title>
                                        {{ labelCustomerLoan }}
                                    </v-list-tile-title>
                                </v-list-tile-content>
                                <v-layout align-center justify-end>
                                    <v-flex sm7 md7 offset-sm1>
                                        <v-text-field
                                            v-model="search"
                                            label="Search Customer by Name or Number"
                                            color="white"
                                            light
                                            hide-details
                                            @keydown="searchKeyListener"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex sm1 md1>
                                        <v-btn slot="activator" dark icon @click="searchDetails">
                                            <v-icon>search</v-icon>
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-list-tile>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout row wrap>
                <v-flex d-flex>
                    <v-card color="dark" light>
                        <v-card-title>
                            <span class="subheading">{{ labelLoanDetails }}</span>
                            <v-spacer></v-spacer>
                            <v-btn dark color="primary" @click="newLoan">New Loan</v-btn>
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            <v-container fluid grid-list-md class="pt-0 mr-0 ml-0">
                                <v-layout wrap>
                                    <v-flex xs6 sm3 md3>
                                        <v-text-field
                                            label="Loan Number"
                                            disabled
                                            v-model="loanNo"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm3 md3>
                                        <v-menu
                                            ref="menu2"
                                            :close-on-content-click="false"
                                            v-model="menu2"
                                            :nudge-right="40"
                                            :return-value.sync="startDate"
                                            lazy
                                            transition="scale-transition"
                                            offset-y
                                            full-width
                                            min-width="290px"
                                            :disabled="isDisabled"
                                        >
                                            <v-text-field
                                                slot="activator"
                                                v-model="startDate"
                                                prepend-icon="event"
                                                readonly
                                                label="Start Date"
                                                :disabled="isDisabled"
                                            ></v-text-field>
                                            <v-date-picker v-model="startDate" no-title scrollable>
                                                <v-spacer></v-spacer>
                                                <v-btn flat color="primary" @click="menu2 = false">
                                                    Cancel
                                                </v-btn>
                                                <v-btn flat color="primary"
                                                       @click="$refs.menu2.save(startDate)">OK
                                                </v-btn>
                                            </v-date-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs6 sm3 md3>
                                        <v-select
                                            color="light"
                                            label="Select Duration"
                                            :disabled="isDisabled"
                                            :items="durations"
                                            item-text="id"
                                            item-value="id"
                                            v-model="selectedDuration"
                                            @change="onDurationChange">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs6 sm3 md3>
                                        <v-flex d-flex>
                                            <v-text-field
                                                label="End Date"
                                                v-model="endDate"
                                                disabled
                                            ></v-text-field>
                                        </v-flex>
                                    </v-flex>
                                    <v-flex xs6 sm3 md3>
                                        <v-select
                                            color="light"
                                            label="sales Rep"
                                            :disabled="isDisabled"
                                            :items="salesRepNames"
                                            item-text="name"
                                            item-value="id"
                                            v-model="selectedSalesRep">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs6 sm2 md2>
                                        <v-text-field
                                            label="Commission(%)"
                                            v-model="commission"
                                            :disabled="isDisabled"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm2 md2>
                                        <v-text-field
                                            label="Commission Amount"
                                            v-model="commAmount"
                                            :disabled="isDisabled"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm2 md2>
                                        <v-select
                                            color="light"
                                            label="Type"
                                            :disabled="isDisabled"
                                            :items="types"
                                            item-value="id"
                                            item-text="name"
                                            v-model="selectedType">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs6 sm3 md3>
                                        <v-text-field
                                            label="Loan Amount"
                                            :disabled="isDisabled"
                                            v-model="loanAmount"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm3 md3>
                                        <v-text-field
                                            label="Loan Interest %"
                                            :disabled="isDisabled"
                                            v-model="loanInterest"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm3 md3>
                                        <v-text-field
                                            label="Days"
                                            v-model="loanDays"
                                            :disabled="isDisabled"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm3 md3>
                                        <v-text-field
                                            label="Payment Installment"
                                            v-model="paymentInstallment"
                                            :disabled="isDisabled"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm3 md3>
                                        <v-text-field
                                            label="Total Loan"
                                            v-model="totalLoan"
                                            :disabled="isDisabled"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex d-flex>
                                        <v-layout justify-end>
                                            <v-btn dark outline color="primary" v-show="showCancelBtn">Cancel</v-btn>
                                            <v-btn dark color="primary" v-show="showSaveBtn">Save</v-btn>
                                        </v-layout>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-divider light></v-divider>
                        <v-card-actions>
                            <v-btn dark color="orange" @click="editLoan">Edit</v-btn>
                            <v-btn dark color="red">Delete</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout row wrap>
                <v-flex d-flex>
                    <v-card color="dark" light>
                        <v-card-title>
                            <span class="subheading">{{ labelLoans }}</span>
                        </v-card-title>
                        <v-card-text>
                            <v-data-table :headers="headers" :items="loans" class="elevation-1">
                                <template slot="items" slot-scope="props">
                                    <td>{{ props.item.no }}</td>
                                    <td>{{ props.item.amount }}</td>
                                    <td>{{ props.item.interest }}</td>
                                    <td>{{ props.item.daysCount }}</td>
                                    <td>{{ props.item.installment }}</td>
                                    <td>{{ props.item.total }}</td>
                                    <td>{{ props.item.due }}</td>
                                </template>
                                <template slot="no-data">
                                    <v-alert :value="true" color="error" icon="warning">
                                        Sorry, nothing to display here :(
                                    </v-alert>
                                </template>
                            </v-data-table>
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout row justify-center>
                <v-dialog v-model="dialog" max-width="400" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            {{ dialogTitle }}
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            {{ dialogMessage }}
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-1" @click="dialog=false" flat="flat">Ok</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
        </v-container>
    </v-app>
</template>

<script>
    export default {
        name: "CustomerLoanComponent",
        data() {
            return {
                labelCustomerLoan: "Customer Loan",
                labelLoanDetails: "Existing Loan Details",
                labelLoans: "Existing Loans",
                customerNo: '',
                customerName: "",
                rules: {
                    required: value => !!value || 'Required.',
                    customerNo: value => {
                        const pattern = /^[0-9]{1,5}$/;
                        return pattern.test(value) || 'Invalid Customer Number'
                    },
                    customerName: value => {
                        const pattern = /^[A-Za-z]$/;
                        return pattern.test(value) || 'Invalid Customer Name'
                    }
                },
                labelSearchCustomer: "Search Customer",
                headers: [
                    {
                        text: 'Loan No',
                        align: 'left',
                        sortable: false,
                        value: 'no'
                    },
                    {
                        text: 'Loan Amount',
                        align: 'left',
                        sortable: true,
                        value: 'amount'
                    },
                    {
                        text: 'Interest(%)',
                        align: 'left',
                        sortable: false,
                        value: 'interest'
                    },
                    {
                        text: 'Days',
                        align: 'left',
                        sortable: false,
                        value: 'daysCount'
                    },
                    {
                        text: 'Installment',
                        align: 'left',
                        sortable: false,
                        value: 'installment'
                    },
                    {
                        text: 'Total Loan',
                        align: 'left',
                        sortable: false,
                        value: 'total'
                    },
                    {
                        text: 'Due Payment',
                        align: 'left',
                        sortable: false,
                        value: 'due'
                    }
                ],
                loans: [],
                loanNo: '',
                startDate: new Date().toISOString().substr(0, 10),
                menu2: false,
                dayCount: '30',
                endDate: new Date().toISOString().substr(0, 10),
                salesRepNames: [],
                selectedSalesRep: [
                    {
                        id: '',
                        name: ''
                    }
                ],
                commission: '',
                commAmount: '',
                types: [
                    {
                        id: '1',
                        name: 'cash'
                    },
                    {
                        id: '2',
                        name: 'bank'
                    },
                    {
                        id: '3',
                        name: 'both'
                    },
                ],
                selectedType: {
                    id: '1',
                    name: 'cash'
                },
                loanAmount: '',
                loanInterest: '',
                loanDays: '',
                paymentInstallment: '',
                totalLoan: '',
                search: '',
                durations: [
                    {
                        id: '30'
                    },
                    {
                        id: '60'
                    },
                    {
                        id: '100'
                    },
                ],
                selectedDuration: {
                    id: '30'
                },
                dueAmounts:[],
                dataReceived: false,
                isDisabled: false,
                showSaveBtn: false,
                showCancelBtn: false,
                dialog: false,
                dialogTitle:'',
                dialogMessage: ''
            }
        },
        methods: {
            searchDetails() {
                console.log(this.search);
                this.getData()
            },
            getData() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/getLoanDetailsForCustomer', {
                    parameter: this.search
                }).then((response) => {
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
                            this.dataReceived = true;
                            this.checkLoansEmpty(response.data.loans);
                            this.setSalesReps(response.data.salesReps);
                        }

                    }
                }).catch((error) => {
                    this.$Progress.fail();
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
            setSalesReps(salesReps) {
                salesReps.forEach((salesRep)=>{
                    this.salesRepNames.push({
                        id: salesRep.id,
                        name: salesRep.name
                    });
                });
            },
            checkLoansEmpty(loans) {
                if (loans.length === 0) {
                    this.$notify({
                        group: 'general',
                        title: 'Error',
                        type: 'error',
                        text: "No Loans available",
                        fontsize: '20px'
                    });
                    //create pop up function call
                } else {
                    this.populateLoanData(loans);
                }
            },
            populateLoanData(loans) {
                this.flushLoanData();
                this.populateInTable(loans);
                this.populateInExistingLoan(loans[loans.length-1]);
            },
            populateInTable(loans) {
                console.log(loans);
                loans.forEach((loan)=>{
                    this.loans.push({
                        no: loan.loan_no,
                        amount: loan.loan_amount,
                        interest: loan.interest_rate,
                        daysCount: loan.no_of_installments,
                        installment: loan.installment_amount,
                        total: loan.total_loan_amount,
                        due: loan.due_amount
                    });
                });
            },
            populateInExistingLoan(loan) {
                this.loanNo = loan.loan_no;
                this.startDate = new Date(loan.start_date).toISOString().substr(0,10);
                this.endDate = new Date(loan.end_date).toISOString().substr(0,10);
                this.setDuration(loan.duration);
                this.loanAmount = loan.loan_amount;
                this.loanInterest = loan.interest_rate;
                this.paymentInstallment = loan.installment_amount;
                this.loanDays = loan.duration;

            },
            flushLoanData () {
                this.loans.splice(0,this.loans.length);
            },
            setDuration(duration) {
                console.log(duration);
                this.selectedDuration.id = duration;
                console.log(this.selectedDuration.id);
            },
            disableFields() {
                this.isDisabled = true;
            },
            newLoan() {
                if(this.dataReceived) {
                    this.flushExistingLoanData();
                    this.getLoanNumber();
                    this.enableFields();
                    this.showBtns();
                } else {
                    this.$notify({
                        group: 'general',
                        title: 'Error',
                        type: 'error',
                        text: "Search Customer First",
                        fontsize: '20px'
                    });
                    this.showDialog("No Customer Specified", "You do not selected any customer to give a Loan");
                }
            },
            flushExistingLoanData() {
                this.startDate = new Date().toISOString().substr(0,10);
                this.selectedDuration.id = '';
                this.endDate = new Date().toISOString().substr(0,10);
                this.selectedSalesRep.id = '';
                this.selectedSalesRep.name = '';
                this.commission = '';
                this.commAmount = '';
                this.selectedType.id = '1';
                this.selectedType.name = 'cash';
                this.loanAmount = '';
                this.loanInterest='';
                this.loanDays = '';
                this.paymentInstallment = '';
                this.totalLoan = '';
            },
            enableFields() {
                this.isDisabled = false;
            },
            showBtns() {
                this.showSaveBtn = true;
                this.showCancelBtn = true;
            },
            editLoan() {
                if(this.dataReceived) {
                    this.enableFields();
                    this.showBtns();
                } else {
                    this.$notify({
                        group: 'general',
                        title: 'Error',
                        type: 'error',
                        text: "Search Customer First",
                        fontsize: '20px'
                    });
                    this.showDialog("No Customer Specified", "You do not selected any customer to edit");
                }
            },
            showDialog (title, message) {
                this.dialogTitle = title;
                this.dialogMessage = message;
                this.dialog = true;
            },
            getLoanNumber() {
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.get('/getLoanNumber').then((response) => {
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
                            console.log(response.data.loan_no);
                            this.populateLoanNumber(response.data.loan_no);
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
            populateLoanNumber(number) {
                this.loanNo = number;
            },
            searchKeyListener(e) {
                if (e.keyCode === 13) {
                    this.searchDetails();
                }
            },
            onDurationChange() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/calculateEndDate', {
                    start_date: this.startDate,
                    duration: this.selectedDuration
                }).then((response) => {
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
                            this.setEndDate(response.data.end_date);
                        }

                    }
                }).catch((error) => {
                    this.$Progress.fail();
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
            setEndDate(end_date) {
                this.endDate = new Date(end_date).toISOString().substr(0,10);
            }
        },
        mounted() {
            console.log(this.dayCount);
        },
        created() {
            this.disableFields();
        }
    }
</script>

<style scoped>

</style>
