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
                                            v-model="selectedSalesRep"
                                            @change="setSelectedSalesRepForRequests">
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
                                            disabled
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
                                            v-model="selectedType"
                                            @change="selectPaymentType">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs6 sm3 md3>
                                        <v-text-field
                                            label="Loan Amount"
                                            :disabled="isDisabled"
                                            v-model="loanAmount"
                                            @change="requestInstallmentAndTotal"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm3 md3>
                                        <v-text-field
                                            label="Loan Interest %"
                                            :disabled="isDisabled"
                                            v-model="loanInterest"
                                            @change="requestInstallmentAndTotal"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm3 md3>
                                        <v-text-field
                                            label="Days"
                                            v-model="loanDays"
                                            disabled
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm3 md3>
                                        <v-text-field
                                            label="Payment Installment"
                                            v-model="paymentInstallment"
                                            disabled
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm3 md3>
                                        <v-text-field
                                            label="Total Loan"
                                            v-model="totalLoan"
                                            disabled
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex d-flex>
                                        <v-layout justify-end>
                                            <v-btn dark outline color="primary" @click="newLoanCancelBtnClick" v-show="showCancelBtn">Cancel</v-btn>
                                            <v-btn dark color="primary" @click="saveBtnClick" v-show="showSaveBtn">Save</v-btn>
                                        </v-layout>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-divider light></v-divider>
                        <v-card-actions>
                            <v-btn dark color="orange" v-show="showEditBtn" @click="editLoan">Edit</v-btn>
                            <v-btn dark v-show="showDeleteBtn" @click="deleteLoan" color="red">Delete</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn dark v-show="showClearBtn" @click="clearLoan" color="green">Clear</v-btn>
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
                                    <tr @click="onClickLoan(props.item)">
                                        <td>{{ props.item.no }}</td>
                                        <td>{{ props.item.amount }}</td>
                                        <td>{{ props.item.interest }}</td>
                                        <td>{{ props.item.daysCount }}</td>
                                        <td>{{ props.item.installment }}</td>
                                        <td>{{ props.item.total }}</td>
                                        <td>{{ props.item.due }}</td>
                                    </tr>
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
            <v-layout row justify-center>
                <v-dialog v-model="loanAmountBothDialog" max-width="400" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            {{labelSelectPayments}}
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            <v-text-field
                                label="Cash Amount"
                                v-model="cashAmount"
                            ></v-text-field>
                            <v-text-field
                                label="Bank Amount"
                                v-model="bankAmount"
                            ></v-text-field>
                            <v-text-field
                                label="Cheque Number"
                                v-model="chequeNo"
                            ></v-text-field>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red darken-1" @click="loanAmountBothDialog=false" flat="flat">Cancel</v-btn>
                            <v-btn color="green darken-1" @click="setBankAndCashAmounts" flat="flat">Confirm</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
            <v-layout row justify-center>
                <v-dialog v-model="loanSuccessDialog" max-width="400" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            {{loanSuccessTitle}}
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            {{ loanSuccessMessage }}
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-1" @click="loanSuccessConfirmed" flat="flat">OK</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
            <v-layout row justify-center>
                <v-dialog v-model="loanEditSuccessDialog" max-width="400" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            {{loanEditSuccessTitle}}
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            {{ loanEditSuccessMessage }}
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-1" @click="loanEditSuccessConfirmed" flat="flat">OK</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
            <v-layout row justify-center>
                <v-dialog v-model="newLoanFailDialog" max-width="400" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            {{newLoanFailDialogTitle}}
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            {{ newLoanFailDialogMessage }}
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-1" @click="newLoanFailConfirmed" flat="flat">OK</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
            <v-layout row justify-center>
                <v-dialog v-model="loanDeleteConfirmDialog" max-width="400" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            {{loanDeleteConfirmDialogTitle}}
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            {{ loanDeleteConfirmDialogMessage }}
                        </v-card-text>
                        <v-divider light></v-divider>
                        <v-card-text>
                            <v-checkbox v-model="deletePermanentlyConfirmed" label="delete permanently"></v-checkbox>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="red darken-1" @click="loanDeleteConfirmDialog=false" flat="flat">Cancel</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-1" @click="loanDeleteConfirm" flat="flat">OK</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
            <v-layout row justify-center>
                <v-dialog v-model="loanDeleteSuccessDialog" max-width="400" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            {{loanDeleteSuccessTitle}}
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            {{ loanDeleteSuccessMessage }}
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-1" @click="loanDeleteSuccessConfirm" flat="flat">OK</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
            <v-layout row justify-center>
                <v-dialog v-model="loanClearConfirmDialog" max-width="400" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            {{loanClearConfirmTitle}}
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            {{ loanClearConfirmMessage }}
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="red darken-1" @click="loanClearNotConfirm" flat="flat">Cancel</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-1" @click="loanClearConfirm" flat="flat">OK</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
            <v-layout row justify-center>
                <v-dialog v-model="loanClearSuccessDialog" max-width="400" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            {{loanClearSuccessTitle}}
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            {{ loanClearSuccessMessage }}
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-1" @click="loanClearSuccessConfirm" flat="flat">OK</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
            <v-layout row justify-center>
                <v-dialog v-model="loanRepaymentDetailsDialog" max-width="700" scrollable persistent>
                    <v-card>
                        <v-card-title>
                            <h3>{{ loanRepaymentDetailsDialogTitle }}</h3>
                        </v-card-title>
                        <v-card-text>
                            <v-data-table
                                :headers="loanRepaymentsHeader"
                                :items="loanRepayments"
                                class="elevation-1"
                            >
                                <template slot="items" slot-scope="props">
                                    <tr :class="{'primary': props.item.isSelected}">
                                        <td>{{ props.item.paymentNo }}</td>
                                        <td>{{ props.item.paymentDate }}</td>
                                        <td>{{ props.item.paymentAmount }}</td>
                                    </tr>
                                </template>
                                <template slot="no-data">
                                    <v-alert :value="true" color="error" icon="warning">
                                        This loan has no payments yet :(
                                    </v-alert>
                                </template>
                            </v-data-table>
                        </v-card-text>
                        <v-card-actions>
                            <v-flex text-xs-center>
                                <v-btn color="green darken-1" @click="onclickOkForLoanRepayment" flat="flat">
                                    OK
                                </v-btn>
                            </v-flex>
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
                selectedSalesRep: {
                    id: '',
                    name: ''
                },
                selectedSalesRepForNewLoan: 0,
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
                        id:30
                    },
                    {
                        id:60
                    },
                    {
                        id:100
                    },
                ],
                selectedDuration: {
                    id:30
                },
                dueAmounts: [],
                dataReceived: false,
                isDisabled: false,
                showSaveBtn: false,
                showCancelBtn: false,
                dialog: false,
                dialogTitle: '',
                dialogMessage: '',
                loanAmountBothDialog: false,
                labelSelectPayments: "Enter Bank and Cash Amounts",
                cashAmount: 0,
                bankAmount: 0,
                chequeNo: 0,
                selectedTypeId: 0,
                isEdit: false,
                customerId:'',
                loanSuccessDialog: false,
                loanSuccessMessage: "Customer Loan added Successfully!",
                loanSuccessTitle: "Successfully Added",
                showEditBtn: false,
                showDeleteBtn: false,
                showClearBtn:false,
                loanEditSuccessDialog: false,
                loanEditSuccessTitle: "Successfully Edited",
                loanEditSuccessMessage: "Customer Loan Edited Successfully!",
                loan_id : 0,
                selectedDurationForRequests:0,
                newLoanFailDialog:false,
                newLoanFailDialogTitle: "Fail to add",
                newLoanFailDialogMessage: "This customer already having an ongoing loan",
                loanDeleteConfirmDialog:false,
                loanDeleteConfirmDialogTitle:"Delete this loan?",
                loanDeleteConfirmDialogMessage:"Do you really want to delete the selected loan, the customer will marked as a customer who haven't given any loan",
                deletePermanentlyConfirmed:false,
                loanDeleteSuccessDialog:false,
                loanDeleteSuccessTitle:"Successfully deleted",
                loanDeleteSuccessMessage:"The specified loan was deleted",
                dueAmount:0,
                loanClearConfirmDialog:false,
                loanClearConfirmTitle:"Clear selected loan",
                loanClearConfirmMessage:"",
                loanClearSuccessDialog:false,
                loanClearSuccessTitle:"Loan cleared successfully",
                loanClearSuccessMessage:"specified loan was cleared and you can assign a new loan to this customer",
                loanRepayments: [],
                loanRepaymentsHeader: [
                    {
                        text: 'Payment No',
                        align: 'left',
                        sortable: false,
                        value: 'paymentNo'
                    },
                    {
                        text: 'Payment Date',
                        align: 'left',
                        sortable: false,
                        value: 'paymentDate'
                    },
                    {
                        text: 'Payment Amount',
                        align: 'left',
                        sortable: false,
                        value: 'paymentAmount'
                    }
                ],
                loanRepaymentDetailsDialog:false,
                loanRepaymentDetailsDialogTitle:"Loan Payment Details"
            }
        },
        methods: {
            searchDetails() {
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
                            this.flushExistingLoanData();
                            this.flushLoanData();
                        } else {
                            this.$Progress.finish();
                            this.dataReceived = true;
                            this.setSalesReps(response.data.salesReps);
                            this.checkLoansEmpty(response.data.loans);
                            this.setCustomerId(response.data.customer_id);
                        }

                    }
                }).catch((error) => {
                    this.$Progress.fail();
                    this.$notify({
                        group: 'auth',
                        title: 'Error',
                        type: 'error',
                        text: error,
                        fontsize: '20px'
                    });
                    this.flushExistingLoanData();
                    this.flushLoanData();
                });
            },
            setCustomerId(id) {
                this.customerId = id;
            },
            setSalesReps(salesReps) {
                salesReps.forEach((salesRep) => {
                    this.salesRepNames.push({
                        id: salesRep.id,
                        name: salesRep.name
                    });
                });
            },
            checkLoansEmpty(loans) {
                if (loans.length === 0) {
                    this.showLoanEmptyDialog();
                } else {
                    this.populateLoanData(loans);
                }
            },
            showLoanEmptyDialog() {
                this.dialog = true;
                this.dialogTitle = "No Loans Available";
                this.dialogMessage = "This customer do not have any ongoing loans. You can given one!"
            },
            populateLoanData(loans) {
                this.flushLoanData();
                this.populateInTable(loans);
                this.populateInExistingLoan(loans[loans.length - 1]);
            },
            populateInTable(loans) {
                loans.forEach((loan) => {
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
                this.showExistingLoanBtnS();
                this.loanNo = loan.loan_no;
                this.startDate = new Date(loan.start_date).toISOString().substr(0, 10);
                this.endDate = new Date(loan.end_date).toISOString().substr(0, 10);
                this.setDuration(loan.duration);
                this.loanAmount = loan.loan_amount;
                this.loanInterest = loan.interest_rate;
                this.paymentInstallment = loan.installment_amount;
                this.loanDays = loan.duration;
                this.totalLoan = loan.total_loan_amount;
                this.setLoanCommission(loan.commission[0]);
                this.cashAmount = loan.cash_amount;
                this.bankAmount = loan.bank_amount;
                this.selectedTypeId = loan.selectedTypeId;
                this.loan_id = loan.id;
                this.dueAmount=loan.due_amount;
            },
            setLoanCommission(commission) {
                this.commAmount = commission.commission_amount;
                this.commission = commission.commission_rate;
                this.setSelectedSalesRep(commission.user_id);
            },
            setSelectedSalesRep(id) {
                this.selectedSalesRepForNewLoan = id;
                this.salesRepNames.forEach((salesRepName)=>{
                    if (salesRepName.id===id) {
                        this.selectedSalesRep = Object.assign({},{id:salesRepName.id,name:salesRepName.name});
                    }
                });
            },
            flushLoanData() {
                this.loans.splice(0, this.loans.length);
            },
            setDuration(duration) {
                this.selectedDuration = Object.assign({},{id:duration});
                this.selectedDurationForRequests = duration;
            },
            disableFields() {
                this.isDisabled = true;
            },
            newLoan() {
                this.isEdit=false;
                if (this.dataReceived) {
                    this.flushExistingLoanData();
                    this.getLoanNumber();
                    this.enableFields();
                    this.showNewLoanBtnS();
                    this.hideExistingBtnS();
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
                this.hideExistingBtnS();
                this.startDate = new Date().toISOString().substr(0, 10);
                this.selectedDuration = Object.assign({},{id:30});
                this.selectedDurationForRequests = 0;
                this.endDate = new Date().toISOString().substr(0, 10);
                this.selectedSalesRep = Object.assign({},{id:'',name:''});
                this.selectedSalesRepForNewLoan = 0;
                this.commission = '';
                this.commAmount = '';
                this.selectedType = Object.assign({},{id:'1',name:'cash'});
                this.loanAmount = '';
                this.loanInterest = '';
                this.loanDays = '';
                this.paymentInstallment = '';
                this.totalLoan = '';
                this.cashAmount=0;
                this.bankAmount=0;
                this.loan_id = 0;
                this.dueAmount=0;
            },
            enableFields() {
                this.isDisabled = false;
            },
            showNewLoanBtnS() {
                this.showSaveBtn = true;
                this.showCancelBtn = true;
            },
            editLoan() {
                this.isEdit = true;
                if (this.dataReceived) {
                    this.enableFields();
                    this.showNewLoanBtnS();
                    this.hideExistingBtnS();
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
            showDialog(title, message) {
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
            onDurationChange(e) {
                this.selectedDurationForRequests = this.selectedDuration;
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/calculateEndDate', {
                    start_date: this.startDate,
                    duration: this.selectedDurationForRequests
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
                            this.setPaymentDays(e);
                        }

                    }
                }).catch((error) => {
                    this.$Progress.fail();
                    this.$notify({
                        group: 'auth',
                        title: 'Error',
                        type: 'error',
                        text: error,
                        fontsize: '20px'
                    });
                });
            },
            setPaymentDays(days) {
                this.loanDays = days;
            },
            setEndDate(end_date) {
                this.endDate = new Date(end_date).toISOString().substr(0, 10);
            },
            selectPaymentType(e) {
                if (e === this.types[2].id) {
                    this.loanAmountBothDialog=true
                }
                this.selectedTypeId = e;
            },
            setBankAndCashAmounts() {
                this.loanAmount = parseInt(this.cashAmount) + parseInt(this.bankAmount);
                this.loanAmountBothDialog = false;
            },
            requestInstallmentAndTotal() {
                if (this.loanAmount!=='' && this.loanInterest !== '') {
                    this.setCommissionAmount();
                    this.requestForInstallmentAndTotal();
                }
            },
            requestForInstallmentAndTotal() {
                this.formatCashAndBankAmount();
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/getTotalAndInstallment', {
                    cash_amount: this.cashAmount,
                    bank_amount: this.bankAmount,
                    duration: parseInt(this.selectedDurationForRequests),
                    interest_rate: this.loanInterest
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
                            this.setInstallmentAndTotal(response.data.total_amount, response.data.installment);
                        }

                    }
                }).catch((error) => {
                    console.log(error);
                    this.$Progress.fail();
                    this.$notify({
                        group: 'auth',
                        title: 'Error',
                        type: 'error',
                        text: error,
                        fontsize: '20px'
                    });
                });
            },
            formatCashAndBankAmount() {
                if (this.selectedTypeId === this.types[0].id) {
                    this.cashAmount = this.loanAmount;
                } else if (this.selectedTypeId === this.types[1].id) {
                    this.bankAmount = this.loanAmount;
                }
            },
            setInstallmentAndTotal (total, installment) {
                this.paymentInstallment = installment;
                this.totalLoan = total;
            },
            setCommissionAmount() {
                this.commAmount = this.loanAmount*this.commission/100;
            },
            saveBtnClick() {
                if(!this.isEdit) {
                    this.sendNewLoanCreateRequest();
                } else {
                    this.sendEditLoanRequest();
                }
                this.hideNewLoanBtnS();
            },
            sendNewLoanCreateRequest() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/addCustomerLoan', {
                    cash_amount: this.cashAmount,
                    bank_amount: this.bankAmount,
                    duration: parseInt(this.selectedDurationForRequests),
                    loan_no: this.loanNo,
                    start_date: this.startDate,
                    end_date: this.endDate,
                    salesRep_id: this.selectedSalesRepForNewLoan,
                    commission_rate: this.commission,
                    loan_interest:this.loanInterest,
                    payment_days: this.loanDays,
                    customer_id: this.customerId,
                    cheque_no: this.chequeNo
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
                            this.newLoanFailDialog = true;
                        } else {
                            this.$Progress.finish();
                            this.loanSuccessDialog = true;
                            this.loan_id = response.data.customer_loan_id;
                        }

                    }
                }).catch((error) => {
                    this.$Progress.fail();
                    this.$notify({
                        group: 'auth',
                        title: 'Error',
                        type: 'error',
                        text: error,
                        fontsize: '20px'
                    });
                });
            },
            sendEditLoanRequest() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/editCustomerLoan', {
                    loan_id: this.loan_id,
                    cash_amount: this.cashAmount,
                    bank_amount: this.bankAmount,
                    duration: parseInt(this.selectedDurationForRequests),
                    loan_no: this.loanNo,
                    start_date: this.startDate,
                    end_date: this.endDate,
                    salesRep_id: this.selectedSalesRepForNewLoan,
                    commission_rate: this.commission,
                    loan_interest:this.loanInterest,
                    payment_days: this.loanDays,
                    customer_id: this.customerId,
                    cheque_no: this.chequeNo
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
                            this.populateInExistingLoan(response.data.updated_loan);
                            this.populateEditedLoanInTable(response.data.updated_loan);
                            this.loanEditSuccessDialog = true;
                        }

                    }
                }).catch((error) => {
                    console.log(error);
                    this.$Progress.fail();
                    this.$notify({
                        group: 'auth',
                        title: 'Error',
                        type: 'error',
                        text: error,
                        fontsize: '20px'
                    });
                });
            },
            populateEditedLoanInTable(newLoan){
                let itemIndex=0;
                this.loans.forEach((loan)=>{
                    if(loan.no === newLoan.loan_no){
                        itemIndex = this.loans.indexOf(loan);
                    }
                });

                this.loans.splice(itemIndex,1);

                this.loans.push({
                    no: newLoan.loan_no,
                    amount: newLoan.loan_amount,
                    interest: newLoan.interest_rate,
                    daysCount: newLoan.no_of_installments,
                    installment: newLoan.installment_amount,
                    total: newLoan.total_loan_amount,
                    due: newLoan.due_amount
                })
            },
            loanEditSuccessConfirmed() {
                this.loanEditSuccessDialog = false;
                this.disableFields();
                this.showExistingLoanBtnS();
            },
            loanSuccessConfirmed() {
                this.loanSuccessDialog = false;
                this.disableFields();
                this.addNewLoanToTable();
                this.showExistingLoanBtnS();
            },
            addNewLoanToTable() {
                this.loans.push({
                    no: this.loanNo,
                    amount: this.loanAmount,
                    interest: this.loanInterest,
                    daysCount: this.loanDays,
                    installment: this.paymentInstallment,
                    total: this.totalLoan,
                    due: this.totalLoan
                });
                this.setDueAmount(this.totalLoan);
            },
            setDueAmount(amount) {
                this.dueAmount = amount;
            },
            newLoanCancelBtnClick() {
                this.flushExistingLoanData();
                this.disableFields();
                this.hideNewLoanBtnS();
            },
            hideNewLoanBtnS() {
                this.showSaveBtn = false;
                this.showCancelBtn = false;
            },
            showExistingLoanBtnS() {
                this.showEditBtn = true;
                this.showDeleteBtn = true;
                this.showClearBtn=true;
            },
            hideExistingBtnS() {
                this.showEditBtn = false;
                this.showDeleteBtn = false;
                this.showClearBtn=false;
            },
            setSelectedSalesRepForRequests(id) {
                this.selectedSalesRepForNewLoan = id;
            },
            newLoanFailConfirmed() {
                this.newLoanFailDialog = false;
                this.getData();
                this.disableFields();
                this.showExistingLoanBtnS();
            },
            deleteLoan() {
                this.loanDeleteConfirmDialog = true;
                this.hideExistingBtnS();
            },
            clearLoan() {
                this.loanClearConfirmDialog = true;
                this.hideExistingBtnS();
                this.loanClearConfirmMessage = "The selected loan has due amount of "+this.dueAmount+"\nDo you want to clear this loan?";
            },
            loanClearConfirm() {
                this.loanClearConfirmDialog = false;
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/clearCustomerLoan', {
                    loan_id: this.loan_id
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
                            this.showExistingLoanBtnS();
                        } else {
                            this.$Progress.finish();
                            this.loanClearSuccessDialog=true;
                        }

                    }
                }).catch((error) => {
                    this.$Progress.fail();
                    this.$notify({
                        group: 'auth',
                        title: 'Error',
                        type: 'error',
                        text: error,
                        fontsize: '20px'
                    });
                });
            },
            loanClearNotConfirm() {
                this.loanClearConfirmDialog = false;
                this.showExistingLoanBtnS();
            },
            loanDeleteConfirm() {
                this.loanDeleteConfirmDialog = false;
                this.sendDeleteLoanRequest();
            },
            sendDeleteLoanRequest() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/deleteCustomerLoan', {
                    loan_id: this.loan_id,
                    salesRep_id: this.selectedSalesRepForNewLoan,
                    customer_id: this.customerId,
                    isDeletePermanently:this.deletePermanentlyConfirmed
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
                            this.showExistingLoanBtnS();
                        } else {
                            this.$Progress.finish();
                            this.loanDeleteSuccessDialog=true;
                        }

                    }
                }).catch((error) => {
                    console.log(error);
                    this.$Progress.fail();
                    this.$notify({
                        group: 'auth',
                        title: 'Error',
                        type: 'error',
                        text: error,
                        fontsize: '20px'
                    });
                });
            },
            loanDeleteSuccessConfirm() {
                this.loanDeleteSuccessDialog=false;
                this.flushLoanData();
                this.flushExistingLoanData();
                this.getData();
            },
            loanClearSuccessConfirm() {
                this.loanClearSuccessDialog =false;
                this.flushLoanData();
                this.flushExistingLoanData();
                this.getData();
            },
            onClickLoan(item) {
                this.loanDetailsRequest(item.no);
                this.loanRepaymentDetailsDialog=true;
            },
            loanDetailsRequest(loan_no) {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/getLoanDetailsForNo', {
                    loan_no: loan_no
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
                            this.publishLoanDetails(response.data.loan_details);
                        }

                    }
                }).catch((error) => {
                    console.log(error);
                    this.$Progress.fail();
                    this.$notify({
                        group: 'auth',
                        title: 'Error',
                        type: 'error',
                        text: error,
                        fontsize: '20px'
                    });
                });
            },
            publishLoanDetails(repayments) {
                this.flushLoanDetails();
                repayments.forEach((repayment)=>{
                    this.loanRepayments.push({
                        paymentNo:repayment.id,
                        paymentDate:repayment.created_at,
                        paymentAmount:repayment.amount
                    });
                });
            },
            flushLoanDetails() {
                this.loanRepayments.splice(0,this.loanRepayments.length);
            },
            onclickOkForLoanRepayment() {
                this.loanRepaymentDetailsDialog=false;
            }
        },
        created() {
            this.disableFields();
        }
    }
</script>

<style scoped>

</style>
