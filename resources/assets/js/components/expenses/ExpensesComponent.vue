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
                                        <v-select color="light" label="Route" hide-details :items="routes"
                                                  item-text="name"
                                                  item-value="id" v-model="selectedId" @change="selectRoute">
                                        </v-select>
                                    </v-flex>
                                </v-layout>
                            </v-list-tile>
                        </v-card-actions>
                    </v-card>
                </v-flex>
                <v-flex xs12 sm12 md12 mt-4>
                    <v-card color="dark" light>
                        <v-toolbar flat color="white">
                            <v-layout align-center justify-start>
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
                                    <v-btn slot="activator" light icon @click="onClickSearch">
                                        <v-icon>search</v-icon>
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                            <v-spacer></v-spacer>
                            <v-dialog v-model="dialog" max-width="500px" persistent>
                                <v-btn slot="activator" color="primary" dark class="mb-2" @click="onClickNewExpense">
                                    New Expense
                                </v-btn>
                                <v-card>
                                    <v-card-title>
                                        <span class="headline">{{ formTitle }}</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-container grid-list-md>
                                            <v-layout wrap>
                                                <v-flex xs12 sm6 md6>
                                                    <v-text-field v-model="editedItem.no" label="No"
                                                                  :disabled="isDisabled"></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm6 md6>
                                                    <v-switch
                                                            v-model="switch1"
                                                            label="SalesRep Expenses"
                                                            @change="onChangeSwitch"
                                                            :disabled="disabledSwitch"
                                                    ></v-switch>
                                                </v-flex>
                                                <v-flex xs12 sm6 md4>
                                                    <v-menu
                                                            ref="menu3"
                                                            :close-on-content-click="false"
                                                            v-model="menu3"
                                                            :nudge-right="40"
                                                            :return-value.sync="editedItem.date"
                                                            lazy
                                                            transition="scale-transition"
                                                            offset-y
                                                            full-width
                                                            min-width="290px"
                                                    >
                                                        <v-text-field
                                                                slot="activator"
                                                                v-model="editedItem.date"
                                                                prepend-icon="event"
                                                                readonly
                                                                label="Date"
                                                                hide-details
                                                        ></v-text-field>
                                                        <v-date-picker v-model="editedItem.date" no-title scrollable>
                                                            <v-spacer></v-spacer>
                                                            <v-btn flat color="primary" @click="menu3 = false">Cancel
                                                            </v-btn>
                                                            <v-btn flat color="primary"
                                                                   @click="$refs.menu3.save(editedItem.date)">OK
                                                            </v-btn>
                                                        </v-date-picker>
                                                    </v-menu>
                                                </v-flex>
                                                <v-flex xs12 sm6 md4>
                                                    <v-select color="light" label="Extra Name" single-line
                                                              :items="editedItem.eName" item-text="name"
                                                              item-value="name"
                                                              @change="getEName" v-model="selectedEName"
                                                              :rules="[rules.required]"
                                                    ></v-select>
                                                </v-flex>
                                                <v-flex xs12 sm6 md4>
                                                    <v-select color="light" label="Sales Rep" single-line
                                                              :items="editedItem.salesReps" item-text="name"
                                                              item-value="id"
                                                              @change="getSalesRep" v-model="selectedSalesRep"
                                                              v-show="showSalesReps"
                                                    ></v-select>
                                                </v-flex>
                                                <v-flex xs12 sm6 md4>
                                                    <v-text-field v-model="editedItem.description"
                                                                  label="Description"></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm6 md4>
                                                    <v-select color="light" label="Type" single-line
                                                              :items="editedItem.types" item-text="name"
                                                              item-value="name"
                                                              @change="getPayType"
                                                    ></v-select>
                                                </v-flex>
                                                <v-flex xs12 sm6 md4>
                                                    <v-text-field v-model="editedItem.amount" :rules="[rules.required]"
                                                                  label="Amount" :disabled="isDisabled"></v-text-field>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="blue darken-1" flat @click="close">Cancel</v-btn>
                                        <v-btn color="blue darken-1" flat @click="save">Save</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </v-toolbar>
                        <v-data-table :headers="headers" :items="records" class="elevation-1">
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.no }}</td>
                                <td>{{ props.item.date }}</td>
                                <td>{{ props.item.routeName }}</td>
                                <td>{{ props.item.description }}
                                    <v-chip label color="green" text-color="white" v-show="props.item.isSalesRep" small>
                                        <v-icon left>label</v-icon>
                                        SR Expenses
                                    </v-chip>
                                </td>
                                <td>{{ props.item.amount }}</td>
                                <td class="justify-center layout px-0 align-center">
                                    <v-icon
                                            small
                                            class="mr-2"
                                            @click="editItem(props.item)"
                                    >
                                        edit
                                    </v-icon>
                                    <v-icon
                                            small
                                            @click="deleteItem(props.item)"
                                    >
                                        delete
                                    </v-icon>
                                </td>
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
            <v-layout row justify-center>
                <v-dialog v-model="expenseAmountBothDialog" max-width="400" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            Enter Expense Amounts
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            <v-text-field
                                    label="Cash Amount"
                                    v-model="cashAmount"
                                    v-show="showCash"
                            ></v-text-field>
                            <v-text-field
                                    label="Bank Amount"
                                    v-model="bankAmount"
                                    v-show="showBank"
                            ></v-text-field>
                            <v-text-field
                                    label="Cheque Number"
                                    v-model="chequeNo"
                                    v-show="showBank"
                            ></v-text-field>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red darken-1" @click="expenseAmountBothDialog=false" flat="flat">Cancel</v-btn>
                            <v-btn color="green darken-1" @click="setBankAndCashAmounts" flat="flat">Confirm</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
            <v-layout row justify-center>
                <v-dialog v-model="expenseDeleteConfirmDialog" max-width="400" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            {{expenseDeleteConfirmDialogTitle}}
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            {{ expenseDeleteConfirmDialogMessage }}
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="red darken-1" @click="expenseDeleteConfirmDialog=false" flat="flat">Cancel</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-1" @click="expenseDeleteConfirm" flat="flat">OK</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
            <v-layout row justify-center>
                <v-dialog v-model="deleteSuccessDialog" max-width="400" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            {{deleteSuccessDialogTitle}}
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            {{ deleteSuccessDialogMessage }}
                        </v-card-text>
                        <v-card-actions class="text-center">
                            <v-btn color="green darken-1" @click="deleteSuccess" flat="flat">OK</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
        </v-container>
    </v-app>
</template>

<script>
    export default {
        name: "ExpensesComponent",
        data() {
            return {
                compName: "Expenses",
                startDate: new Date().toISOString().substr(0, 10),
                endDate: new Date().toISOString().substr(0, 10),
                menu1: false,
                menu2: false,
                menu3: false,
                headers: [
                    {
                        text: "No",
                        sortable: false,
                        align: 'left',
                        value: 'no'
                    },
                    {
                        text: "Date",
                        sortable: false,
                        align: 'left',
                        value: 'date'
                    },
                    {
                        text: "Route",
                        sortable: false,
                        align: 'left',
                        value: 'route'
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
                    }
                ],
                records: [],
                routes: [
                    {
                        id: -1,
                        name: "ALL"
                    }
                ],
                selectedId: {
                    id: -1,
                    name: "ALL"
                },
                editedItem: {
                    no: '',
                    date: new Date().toISOString().substr(0, 10),
                    route: 0,
                    eName: [
                        {
                            name: "TEA"
                        },
                        {
                            name: "PRINT ROLL"
                        }
                    ],
                    description: '',
                    amount: '',
                    isSalesRep: false,
                    salesReps: [],
                    types:[
                        {
                            name: "cash"
                        },
                        {
                            name: "bank"
                        },
                        {
                            name: "both"
                        }
                    ],
                    exName:'',
                    user_name: ''
                },
                defaultItem: {
                    no: '',
                    date: new Date().toISOString().substr(0, 10),
                    route: 0,
                    eName: [
                        {
                            name: "TEA"
                        },
                        {
                            name: "PRINT ROLL"
                        }
                    ],
                    description: '',
                    amount: '',
                    isSalesRep: false,
                    salesReps: [],
                    types:[
                        {
                            name: "cash"
                        },
                        {
                            name: "bank"
                        },
                        {
                            name: "both"
                        }
                    ],
                    exName:'',
                    user_name: ''
                },
                search: [],
                editedIndex: -1,
                dialog: false,
                isDisabled: false,
                switch1: false,
                selectedEName: {
                    name: "TEA"
                },
                rules: {
                    required: value => !!value || 'Required.'
                },
                ENames: [],
                selectedRouteIdForRequest: 0,
                salesReps: [],
                showSalesReps: false,
                selectedSalesRep: {
                    id: '',
                    name: ''
                },
                isError: false,
                eNameForRequest: '',
                routeIdForRequest: 0,
                salesRepIdForRequest: 0,
                expenseAmountBothDialog: false,
                cashAmount: 0,
                bankAmount: 0,
                chequeNo: 0,
                showCash: true,
                showBank: false,
                disabledSwitch: false,
                recordDeleteIndex: null,
                deleteRecordId: null,
                expenseDeleteConfirmDialog: false,
                expenseDeleteConfirmDialogTitle: "Confirm delete?",
                expenseDeleteConfirmDialogMessage: "Do you really want to delete",
                deleteItemSalesRep: false,
                deleteSuccessDialog: false,
                deleteSuccessDialogTitle: "Successfully deleted!",
                deleteSuccessDialogMessage: "Selected expense was deleted successfully"
            }
        },
        created() {
            this.getData();
            this.isDisabled = true;
        },
        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'New Expense' : 'Edit Expense'
            }
        },
        methods: {
            getData() {
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/getExpenseData', {
                    startDate: this.startDate,
                    endDate: this.endDate
                }).then((response) => {
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
                            console.log(response);
                            this.flushDataTable();
                            this.ENames = response.data.eNames;
                            this.salesReps = response.data.salesReps;
                            this.populateENames(response.data.eNames);
                            this.populateSalesReps(response.data.salesReps);
                            this.populateData(response.data.records);
                            this.populateRoutes(response.data.routes);
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
            populateData(expense_records) {
                expense_records.forEach((record) => {
                    this.records.push({
                        no: record.id,
                        date: record.transaction_date,
                        routeName: record.route,
                        route: record.route_id,
                        eName: this.ENames,
                        salesReps: this.salesReps,
                        description: record.description,
                        amount: record.amount,
                        isSalesRep: record.isSalesRep,
                        exName: record.exName,
                        user_name: record.user_name,
                        sales_rep_id: record.sales_rep_id >0? record.sales_rep_id: 0,
                        user_id: record.user_id>0? record.user_id: 0,
                        types: [{name:'cash'},{name:'bank'},{name:'both'}],
                        cashAmount: record.cash_amount,
                        bankAmount: record.bank_amount,
                        cheque_no: record.cheque_no
                    });
                });
            },
            flushENames() {
                this.editedItem.eName.splice(0, this.editedItem.eName.length);
            },
            populateENames(eNames) {
                eNames.forEach((eName) => {
                    this.editedItem.eName.push({
                        name: eName
                    });
                    this.defaultItem.eName.push({
                        name: eName
                    });
                });
            },
            populateSalesReps(salesReps) {
                salesReps.forEach((salesRep) => {
                    this.editedItem.salesReps.push({
                        id: salesRep.id,
                        name: salesRep.name
                    });
                    this.defaultItem.salesReps.push({
                        id: salesRep.id,
                        name: salesRep.name
                    });
                });
            },
            flushDataTable() {
                this.records.splice(0, this.records.length);
            },
            populateRoutes(routes) {
                routes.forEach((route) => {
                    this.routes.push({
                        id: route.id,
                        name: route.name
                    });
                });
            },
            selectRoute(route_id) {
                this.selectedRouteIdForRequest = route_id;
                this.getDataByRoute();
            },
            close() {
                this.dialog = false;
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem);
                    this.editedIndex = -1;
                }, 300);
            },
            save() {
                if (this.editedIndex > -1) {
                    if (this.validateEditInputs()) {
                        this.editRequestFilter(this.editedItem);
                        this.close();
                    } else {
                        this.showValidationMessage();
                    }
                } else {
                    if (this.validateInputs()) {
                        this.addNewExpenseFilter(this.editedItem);
                        this.close();
                    } else {
                        this.showValidationMessage();
                    }
                }
            },
            validateEditInputs() {
                if (this.switch1) {
                    return this.editedItem.amount !== '' && this.salesRepIdForRequest !== 0 && this.eNameForRequest !== '';
                } else {
                    return this.editedItem.amount !== '' && this.eNameForRequest !== '';
                }
            },
            validateInputs() {
                if (this.switch1) {
                    return this.editedItem.amount !== '' && this.salesRepIdForRequest !== 0 && this.eNameForRequest !== '';
                } else {
                    return this.editedItem.amount !== '' && this.eNameForRequest !== '';
                }
            },
            editRequestFilter() {
                if (this.switch1) {
                    this.editSalesRepExpenseRequest();
                } else {
                    this.editOtherExpenseRequest();
                }
            },
            addNewExpenseFilter() {
                if (this.switch1) {
                    this.addSalesRepExpenseToDb();
                } else {
                    this.addOtherExpenseToDb();
                }
            },
            editItem(item) {
                this.editedIndex = this.records.indexOf(item);
                this.setENameForRecord(item.exName);
                if (item.isSalesRep) {
                    this.setSwitchEnable(true, true);
                    this.showHideSalesRepSelect(true);
                    this.populateCashAndBankAmounts(item.cashAmount, item.bankAmount, item.cheque_no);
                    this.setSalesRep(item.sales_rep_id, item.user_name);
                }
                this.editedItem = Object.assign({}, item);
                this.dialog = true
            },
            deleteItem(item) {
                this.recordDeleteIndex = this.records.indexOf(item);
                this.deleteRecordId = item.no;
                this.deleteItemSalesRep = !!item.isSalesRep;
                this.expenseDeleteConfirmDialog = true;
            },
            expenseDeleteConfirm() {
                if (this.deleteItemSalesRep) {
                    this.deleteSalesRepExpenseRequest();
                } else {
                    this.deleteOtherExpenseRequest();
                }
                this.expenseDeleteConfirmDialog = false;
            },
            setSwitchEnable(disable, on) {
                this.switch1 = on;
                this.disabledSwitch = disable;
            },
            setENameForRecord(eName) {
                this.selectedEName = Object.assign({}, {name:eName});
                this.eNameForRequest = eName;
            },
            setSalesRep(id, name) {
                this.selectedSalesRep = Object.assign({}, {id:id, name: name});
                this.salesRepIdForRequest = id;
            },
            populateCashAndBankAmounts(cash_amount,bank_amount,cheque_no) {
                this.cashAmount = cash_amount;
                this.bankAmount = bank_amount;
                this.chequeNo = cheque_no;
            },
            getEName(name) {
                this.eNameForRequest = name;
            },
            editOtherExpenseRequest() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/editOtherExpense', {
                    id: this.editedItem.no,
                    tDate: this.editedItem.date,
                    eName: this.eNameForRequest,
                    description: this.editedItem.description,
                    cash_amount: this.cashAmount,
                    bank_amount: this.bankAmount,
                    cheque_no: this.chequeNo
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
                            this.addEditedExpenseToTable(response.data.expense);
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
            addOtherExpenseToDb() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/addOtherExpense', {
                    id: this.editedItem.no,
                    tDate: this.editedItem.date,
                    eName: this.eNameForRequest,
                    description: this.editedItem.description,
                    cash_amount: this.cashAmount,
                    bank_amount: this.bankAmount,
                    cheque_no: this.chequeNo,
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
                            this.addNewItemToDataTable(response.data.record);
                        }
                    }
                }).catch((error) => {
                    console.log(error.message);
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
            editSalesRepExpenseRequest() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/editSalesRepExpense', {
                    id: this.editedItem.no,
                    tDate: this.editedItem.date,
                    eName: this.eNameForRequest,
                    description: this.editedItem.description,
                    amount: this.editedItem.amount,
                    routeId: this.selectedRouteIdForRequest
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
            addSalesRepExpenseToDb() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/addSalesRepExpense', {
                    tDate: this.editedItem.date,
                    eName: this.eNameForRequest,
                    description: this.editedItem.description,
                    cash_amount: this.cashAmount,
                    bank_amount: this.bankAmount,
                    cheque_no: this.chequeNo,
                    salesRep: this.salesRepIdForRequest
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
                            this.addNewItemToDataTable(response.data.record);
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
            deleteOtherExpenseRequest() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/deleteOtherExpense', {
                    id: this.deleteRecordId,
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
                            this.deleteRecordFromTable();
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
            deleteSalesRepExpenseRequest() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/deleteSalesRepExpense', {
                    id: this.deleteRecordId,
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
                            this.deleteRecordFromTable();
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
            deleteRecordFromTable(){
                this.deleteSuccessDialog = true;
            },
            deleteSuccess() {
                this.records.splice(this.recordDeleteIndex, 1);
                this.deleteSuccessDialog = false;
            },
            addEditedExpenseToTable(item){
                Object.assign(this.records[this.editedIndex], item);
            },
            addNewItemToDataTable(item) {
                this.records.push({
                    no: item.id,
                    date: item.transaction_date,
                    routeName: item.route,
                    route: item.route_id,
                    eName: this.ENames,
                    description: item.description,
                    amount: item.amount,
                    isSalesRep: item.isSalesRep,
                    exName: item.exName,
                    user_name: item.user_name,
                    sales_rep_id: item.sales_rep_id >0? item.sales_rep_id: 0,
                    user_id: item.user_id>0? item.user_id: 0,
                    types: [{name:'cash'},{name:'bank'},{name:'both'}],
                    cashAmount: item.cash_amount,
                    bankAmount: item.bank_amount,
                    cheque_no: item.cheque_no
                });
            },
            showValidationMessage() {
                this.$notify({
                    group: 'auth',
                    title: 'Error',
                    type: 'error',
                    text: "Please fill the form correctly!",
                    fontsize: '20px',
                    position: 'top center'
                });
            },
            onClickNewExpense() {
                this.getOtherExpenseNo();
                this.setEName();
                this.flushData();
            },
            setEName() {
                this.eNameForRequest = "TEA";
            },
            flushData() {
                this.cashAmount = 0;
                this.bankAmount = 0;
                this.chequeNo = 0;
            },
            getDataByRoute() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/getExpenseDataByRoute', {
                    startDate: this.startDate,
                    endDate: this.endDate,
                    route_id: this.selectedRouteIdForRequest
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
                            this.flushDataTable();
                            this.populateData(response.data.records);
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
            onClickSearch() {
                if (this.selectedRouteIdForRequest === 0) {
                    this.selectedRouteIdForRequest = -1;
                }
                this.getDataByRoute();
            },
            getOtherExpenseNo() {
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.get('/getOtherExpenseNumber').then((response) => {
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
                            this.populateOtherExpenseNumber(response.data.other_expense_no);
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
            populateOtherExpenseNumber(other_expense_no) {
                this.editedItem.no = other_expense_no;
            },
            getSalesRepExpenseNo() {
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.get('/getSalesRepExpenseNumber').then((response) => {
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
                            this.populateSalesRepExpenseNumber(response.data.sales_rep_expense_no);
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
            populateSalesRepExpenseNumber(sales_rep_expense_no) {
                this.editedItem.no = sales_rep_expense_no;
            },
            onChangeSwitch(val) {
                if (val) {
                    this.getSalesRepExpenseNo();
                } else {
                    this.getOtherExpenseNo();
                }
                this.showHideSalesRepSelect(val);
            },
            showHideSalesRepSelect(val) {
                this.showSalesReps = val;
            },
            getSalesRep(id) {
                this.salesRepIdForRequest = id;
            },
            setBankAndCashAmounts() {
                this.editedItem.amount = parseInt(this.cashAmount) + parseInt(this.bankAmount);
                this.expenseAmountBothDialog = false;
            },
            getPayType(type) {
                this.expenseAmountBothDialog = true;
                if (type === "cash") {
                    this.showCash = true;
                    this.showBank = false;
                }
                else if (type === "bank") {
                    this.showCash = false;
                    this.showBank = true;
                } else if (type === "both") {
                    this.showCash = true;
                    this.showBank = true;
                }
            }
        }
    }
</script>

<style scoped>

</style>
