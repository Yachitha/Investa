<template>
    <v-app>
        <v-container fluid>
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
                            </v-list-tile>
                            <v-spacer></v-spacer>
                            <v-list-tile>
                                <v-list-tile-content>
                                    <v-btn class="btn-outline" @click="disableCustomers">
                                        Disable Customers
                                    </v-btn>
                                </v-list-tile-content>
                            </v-list-tile>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout row wrap justify-center class="mt-2">
                <v-flex xs12 sm6 md6>
                    <v-card
                        class="mx-auto"
                        color="#26c6da"
                        dark
                        max-width="500"
                    >
                        <v-card-title>
                            <v-icon
                                left
                            >
                                account_box
                            </v-icon>
                            <span class="title font-weight-light">{{ labelCardCustomerGrowth }}</span>
                        </v-card-title>

                        <v-card-text class="cell-text">
                            {{ labelMonthlyCount }}
                        </v-card-text>
                        <v-card-text class="text-sm-center">
                            <strong>{{ labelNewCustomers }}</strong>
                        </v-card-text>
                    </v-card>
                </v-flex>
                <v-flex xs12 sm6 md6>
                    <v-card
                        class="mx-auto"
                        color="#26c6da"
                        dark
                        max-width="500"
                    >
                        <v-card-title>
                            <v-icon
                                left
                            >
                                account_box
                            </v-icon>
                            <span class="title font-weight-light">{{ labelTotalCustomerGrowth }}</span>
                        </v-card-title>

                        <v-card-text class="cell-text">
                            {{ labelCustomerGrowthPer }}
                        </v-card-text>
                        <v-card-text class="text-sm-center">
                            <strong>{{ labelCustomerGrowthText }}</strong>
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout justify-left ml-2 mt-4>
                <v-flex xs12 sm3 md3 ml-2>
                    <v-card color="dark" light>
                        <v-container>
                            <v-layout row>
                                <v-flex>
                                    <v-subheader class="pl-0">{{ labelConfigDetails }}</v-subheader>
                                </v-flex>
                            </v-layout>
                            <v-layout row>
                                <v-flex>
                                    <v-select color="light" label="Route" single-line hide-details :items="routes"
                                              item-text="name" item-value="id" v-model="selectedId"
                                              @change="selectRoute"></v-select>
                                </v-flex>
                            </v-layout>
                            <v-layout row mt-3 justify-left align-center>
                                <v-flex xs12 sm8 md8>
                                    <v-subheader class="pl-0">{{ labelShowDeactivate }}</v-subheader>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-checkbox input-value="true" value class="shrink mr-2" color="primary"
                                                v-model="deactivate" @change="showDeactivate"></v-checkbox>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card>
                </v-flex>
                <v-flex xs12 sm9 md9 ml-2>
                    <v-toolbar flat color="white">
                        <v-text-field
                            v-model="search"
                            append-icon="search"
                            label="Search"
                            single-line
                            hide-details
                        ></v-text-field>
                        <v-spacer></v-spacer>
                        <v-dialog v-model="dialog" persistent max-width="900px">
                            <v-btn slot="activator" color="primary" dark class="mb-2" @click="onClickNewLoan">New
                                Customer
                            </v-btn>
                            <v-card ref="form">
                                <v-card-title class="pb-0">
                                    <span class="headline">{{ formTitle }}</span>
                                </v-card-title>

                                <v-card-text class="pa-0">
                                    <v-container grid-list-md class="pt-0">
                                        <v-layout wrap>
                                            <v-flex xs12 sm4 md4>
                                                <v-text-field v-model="editedItem.customerNo"
                                                              label="Customer No"
                                                              :disabled="isDisable"
                                                >
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4>
                                                <v-text-field v-model="editedItem.nicNo" label="NIC"
                                                              :rules="[rules.required,rules.min,rules.mustIncludeVorX]"
                                                              ref="nicNo"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4>
                                                <v-select color="light" label="Route" single-line
                                                          :items="editedItem.route" item-text="name" item-value="id"
                                                          @change="getRouteId" v-model="selected"
                                                          :rules="[rules.required]" ref="route_id"></v-select>
                                            </v-flex>
                                            <v-flex xs12 sm6 md6>
                                                <v-text-field v-model="editedItem.customerName"
                                                              label="Customer Name"
                                                              :rules="[rules.required]"
                                                              ref="customerName"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm6 md6>
                                                <v-text-field v-model="editedItem.contactNo"
                                                              label="Contact No"
                                                              :rules="[rules.required]" ref="contactNo"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4>
                                                <v-text-field v-model="editedItem.addLine1"
                                                              label="Address Line1"
                                                              :rules="[rules.required]" ref="addLine1"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4>
                                                <v-text-field v-model="editedItem.addLine2"
                                                              label="Address Line2"
                                                              :rules="[rules.required]" ref="addLine2"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4>
                                                <v-text-field v-model="editedItem.city" label="City"
                                                              :rules="[rules.required]" ref="city"></v-text-field>
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
                    <v-data-table
                        :headers="headers"
                        :items="customers"
                        class="elevation-1"
                        :search="search"
                    >
                        <template slot="items" slot-scope="props">
                            <td>{{ props.item.customerNo }}</td>
                            <td>{{ props.item.nicNo }}</td>
                            <td class="text-xs-left">{{ props.item.customerName }}</td>
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
                </v-flex>
            </v-layout>
            <v-layout row justify-center>
                <v-dialog v-model="customerDeleteConfirmDialog" max-width="400" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            {{customerDeleteConfirmDialogTitle}}
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            {{ customerDeleteConfirmDialogMessage }}
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="red darken-1" @click="customerDeleteConfirmDialog=false" flat="flat">Cancel
                            </v-btn>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-1" @click="customerDeleteConfirm" flat="flat">OK</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
            <v-layout row justify-center>
                <v-dialog v-model="disableCustomersDialog" max-width="800" scrollable persistent>
                    <v-card>
                        <v-card-title>
                            {{ disableCustomersDialogTitle }}
                            <v-spacer></v-spacer>
                            <v-btn class="btn-rounded" dark color="#ff6761">disable</v-btn>
                        </v-card-title>
                        <v-data-table
                            v-model="selected_arr"
                            :headers="headers_d"
                            :items="d_cus"
                            item-key="customerNo"
                            show-select
                            class="elevation-1"
                        >
                        </v-data-table>
                        <v-card-actions>
                            <v-btn color="red darken-1" @click="disableCustomersDialog=false" flat="flat">Cancel
                            </v-btn>
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
        </v-container>
    </v-app>
</template>

<script>
    export default {
        name: 'customerDashboard',
        data: function () {
            return {
                compName: "Customer Dashboard",
                labelCardCustomerGrowth: "Monthly Customers",
                labelMonthlyCount: 0,
                labelNewCustomers: "% of growth in Monthly Customer count",
                labelTotalCustomerGrowth: "Total Customers",
                labelCustomerGrowthPer: 0,
                labelCustomerGrowthText: "Total Customer count at all time",
                dialog: false,
                headers: [
                    {
                        text: 'Customer No',
                        align: 'left',
                        sortable: false,
                        value: 'customerNo'
                    },
                    {
                        text: 'Customer NIC',
                        align: 'left',
                        sortable: false,
                        value: 'nicNo'
                    },
                    {
                        text: 'Customer Name',
                        align: 'left',
                        sortable: false,
                        value: 'customerName'
                    }
                ],
                customers: [],
                sortCustomers: [],
                routes: [
                    {
                        name: "ALL",
                        id: -1
                    }
                ],
                routesCopy: [],
                salesReps: [],
                editedIndex: -1,
                editedItem: {
                    customerNo: '',
                    customerName: '',
                    route_id: 0,
                    route: [],
                    nicNo: '',
                    contactNo: '',
                    status: '',
                    addLine1: '',
                    addLine2: '',
                    city: ''
                },
                defaultItem: {
                    customerNo: '',
                    customerName: '',
                    route_id: 0,
                    route: [],
                    nicNo: '',
                    contactNo: '',
                    status: '',
                    addLine1: '',
                    addLine2: '',
                    city: ''
                },
                search: [],
                labelConfigDetails: "Configuration Details",
                labelShowDeactivate: "Show Deactivate Customers only",
                deactivate: false,
                routeId: 0,
                selectedId: 0,
                selectedRouteId: 0,
                selected: {
                    name: "",
                    id: 0
                },
                defaultSelected: {
                    name: '',
                    id: 0
                },
                isDisable: false,
                rules: {
                    required: value => !!value || 'Required.',
                    min: v => v.length >= 10 || 'Min 10 characters',
                    mustIncludeVorX: v => /v|V|x|X$/.test(v) || 'Nic must include v or x'
                },
                formHasErrors: false,
                customerDeleteConfirmDialog: false,
                customerDeleteConfirmDialogTitle: "Delete selected customer?",
                customerDeleteConfirmDialogMessage: "By confirming this customer marked as deleted",
                deleteIndex: null,
                deleteCustomerNo: null,
                disableCustomersDialog: false,
                disableCustomersDialogTitle: "Select and Disable",
                headers_d: [
                    {
                        text: 'Customer No',
                        align: 'left',
                        sortable: false,
                        value: 'customerNo'
                    },
                    {
                        text: 'Customer NIC',
                        align: 'left',
                        sortable: false,
                        value: 'nicNo'
                    },
                    {
                        text: 'Customer Name',
                        align: 'left',
                        sortable: false,
                        value: 'customerName'
                    },
                    {
                        text: 'Customer Status',
                        align: 'left',
                        sortable: false,
                        value: 'status'
                    }
                ],
                selected_arr:[],
                d_cus:[],
            }
        },
        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'New Customer' : 'Edit Customer'
            },
            form() {
                return {
                    nicNo: this.editedItem.nicNo,
                    route_id: this.routeId,
                    customerName: this.editedItem.customerName,
                    contactNo: this.editedItem.contactNo,
                    addLine1: this.editedItem.addLine1,
                    addLine2: this.editedItem.addLine2,
                    city: this.editedItem.city
                }
            }
        },
        watch: {
            dialog(val) {
                val || this.close()
            }
        },
        created() {
            this.getCustomerData();
            this.disableField();
        },
        methods: {
            initialize(item) {
                let customers = item.customers;
                customers.forEach((customer) => {
                    this.customers.push({
                        route_id: item.route_id,
                        route: this.routesCopy,
                        customerNo: customer.customer_no,
                        customerName: customer.name,
                        nicNo: customer.NIC,
                        contactNo: customer.contact_no,
                        status: customer.status,
                        addLine1: customer.addLine1,
                        addLine2: customer.addLine2,
                        city: customer.city
                    });

                    this.sortCustomers.push({
                        route_id: item.route_id,
                        route: this.routesCopy,
                        customerNo: customer.customer_no,
                        customerName: customer.name,
                        nicNo: customer.NIC,
                        contactNo: customer.contact_no,
                        status: customer.status,
                        addLine1: customer.addLine1,
                        addLine2: customer.addLine2,
                        city: customer.city
                    })
                });
            },
            editItem(item) {
                this.editedIndex = this.customers.indexOf(item);
                this.setSelected(item.route, item.route_id);
                this.editedItem = Object.assign({}, item);
                this.dialog = true
            },
            deleteItem(item) {
                this.deleteIndex = this.customers.indexOf(item);
                this.deleteCustomerNo = item.customerNo;
                this.customerDeleteConfirmDialog = true;
            },
            customerDeleteConfirm() {
                this.customerDeleteConfirmDialog = false;
                console.log(this.deleteCustomerNo);
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/deleteCustomer', {
                    customer_no: this.deleteCustomerNo,
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
                            this.$notify({
                                group: 'auth',
                                title: 'Success',
                                type: 'success',
                                text: response.data.message,
                                fontsize: '20px'
                            });
                            this.deleteFromDBSuccess();
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
            deleteFromDBSuccess() {
                this.customers.splice(this.deleteIndex, 1);
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
                        this.editCustomerRequest(this.editedItem);
                        this.close();
                    } else {
                        this.showValidationMessage();
                    }
                } else {
                    if (this.validateInputs()) {
                        this.addCustomerToDb(this.editedItem);
                        this.close();
                    } else {
                        this.showValidationMessage();
                    }
                }
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
            validateEditInputs() {
                return this.editedItem.customerNo !== '' && this.editedItem.customerName !== '' && this.editedItem.route_id !== 0 && this.editedItem.contactNo !== '' && this.editedItem.nicNo !== '' && this.editedItem.addLine1 !== '' && this.editedItem.addLine2 !== '' && this.editedItem.city !== '';
            },
            validateInputs() {
                this.formHasErrors = false;
                Object.keys(this.form).forEach(f => {
                    if (!(this.form[f])) this.formHasErrors = true;

                    this.$refs[f].validate();
                });
                return this.formHasErrors !== true;
            },
            clearForm() {
                this.formHasErrors = false;
                Object.keys(this.form).forEach(f => {
                    this.$refs[f].reset()
                });
            },
            getCustomerData() {
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.get('/getCustomerData').then((response) => {
                    if (response.status === 200) {
                        if (!response.data.error) {
                            response.data.routes.forEach((item) => {
                                this.routes.push({
                                    name: item.name,
                                    id: item.id
                                })
                            });
                            this.routesCopy = response.data.routes;
                            response.data.customers.forEach((item) => {
                                this.initialize(item);
                            });
                            response.data.salesReps.forEach((item) => {
                                this.addSalesReps(item.route_id, item.id);
                            });
                            this.labelMonthlyCount = response.data.monthlyGrowth;
                            this.labelCustomerGrowthPer = response.data.count;
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
            addSalesReps(route_id, id) {
                this.salesReps.push({
                    id: id,
                    route_id: route_id
                })
            },
            addCustomerToDb(item) {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/addCustomerToDb', {
                    customer_no: item.customerNo,
                    name: item.customerName,
                    nic: item.nicNo,
                    addLine1: item.addLine1,
                    addLine2: item.addLine2,
                    city: item.city,
                    contact_no: item.contactNo,
                    route_id: this.routeId
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
                            this.$notify({
                                group: 'auth',
                                title: 'Success',
                                type: 'success',
                                text: response.data.message,
                                fontsize: '20px'
                            });
                            this.addNewCustomerToTable(response.data.customer);
                            this.labelMonthlyCount = response.data.monthlyGrowth;
                            this.labelCustomerGrowthPer = response.data.count;
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
            editCustomerRequest(item) {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/editCustomer', {
                    customer_no: item.customerNo,
                    name: item.customerName,
                    nic: item.nicNo,
                    addLine1: item.addLine1,
                    addLine2: item.addLine2,
                    city: item.city,
                    contact_no: item.contactNo,
                    route_id: this.routeId !== 0 ? this.routeId : item.route_id
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
                            this.addEditedCustomerToTable();
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
            addEditedCustomerToTable() {
                Object.assign(this.customers[this.editedIndex], this.editedItem);
            },
            addNewCustomerToTable(customer) {
                this.customers.push({
                    route_id: customer.route_id,
                    route: this.routesCopy,
                    customerNo: customer.customer_no,
                    customerName: customer.name,
                    nicNo: customer.NIC,
                    contactNo: customer.contact_no,
                    status: customer.status,
                    addLine1: customer.addLine1,
                    addLine2: customer.addLine2,
                    city: customer.city
                });

                this.sortCustomers.push({
                    route_id: customer.route_id,
                    route: this.routesCopy,
                    customerNo: customer.customer_no,
                    customerName: customer.name,
                    nicNo: customer.NIC,
                    contactNo: customer.contact_no,
                    status: customer.status,
                    addLine1: customer.addLine1,
                    addLine2: customer.addLine2,
                    city: customer.city
                });
            },
            getRouteId(id) {
                this.routeId = id;
            },
            selectRoute() {
                this.permutations()
            },
            showDeactivate() {
                this.permutations()
            },
            permutations() {
                if (this.selectedId === -1) {
                    if (this.deactivate) {
                        this.customers = this.customers.filter((customer) => {
                            return customer.status === 'deactive'
                        })
                    } else {
                        this.customers = this.sortCustomers
                    }
                } else {
                    if (this.deactivate) {
                        this.customers = this.sortCustomers.filter((customer) => {
                            return customer.route_id === this.selectedId;
                        });
                        this.customers = this.customers.filter((customer) => {
                            return customer.status === 'deactive'
                        })
                    } else {
                        this.customers = this.sortCustomers.filter((customer) => {
                            return customer.route_id === this.selectedId;
                        });
                    }
                }
            },
            setSelected(routes, id) {
                routes.forEach((route) => {
                    if (route.id === id) {
                        this.selected = Object.assign({}, {id: route.id, name: route.name});
                    }
                });
            },
            onClickNewLoan() {
                this.getCustomerNumber();
                this.setRoutes();
                this.resetSelectedRoutes();
            },
            disableField() {
                this.isDisable = true;
            },
            enableField() {
                this.isDisable = false;
            },
            getCustomerNumber() {
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.get('/getCustomerNumber').then((response) => {
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
                            this.populateCustomerNumber(response.data.customer_no);
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
            populateCustomerNumber(customer_no) {
                this.editedItem.customerNo = customer_no;
            },
            setRoutes() {
                this.editedItem.route = this.routesCopy;
            },
            resetSelectedRoutes() {
                this.selected = Object.assign({}, {id: '', name: ''});
            },
            disableCustomers() {
                this.disableCustomersDialog = true;
                this.getDisableCustomers();
            },
            getDisableCustomers() {
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.get('/getCustomersToDisable').then((response) => {
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
                            this.pushDisabling(response.data.d_cus);
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
            disableCustomerRequest() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/editCustomer', {
                    customer_no: item.customerNo,
                    name: item.customerName,
                    nic: item.nicNo,
                    addLine1: item.addLine1,
                    addLine2: item.addLine2,
                    city: item.city,
                    contact_no: item.contactNo,
                    route_id: this.routeId !== 0 ? this.routeId : item.route_id
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
                            this.pushDisabling();
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
            pushDisabling (customers){
                customers.forEach((cus)=>{
                    this.d_cus.push({
                        customerNo: cus.customer_no,
                        customerName: cus.name,
                        nicNo: cus.NIC,
                        status: cus.status,
                    })
                });
            }
        }
    }
</script>

<style scoped>
    .cell-text {
        font-size: 72px;
        padding: 16px;
        font-weight: bold;
        color: #FFFFFF;
        text-align: center;
    }
</style>
