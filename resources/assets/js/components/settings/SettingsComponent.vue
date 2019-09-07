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
                            </v-list-tile>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout row wrap>
                <v-flex d-flex xs12 sm12 md12 mt-2>
                    <v-card color="dark" light>
                        <v-card-title class="blue--text text-lg-start">Account Settings</v-card-title>

                    </v-card>
                </v-flex>
                <v-flex d-flex xs12 sm12 md12 mt-2>
                    <v-card color="dark" light>
                        <v-card-title class="blue--text text-lg-start">Sales Rep Settings</v-card-title>
                        <v-toolbar flat color="white">
                            <v-btn light icon @click="onClickDefaultPassword">
                                <v-icon>lock</v-icon>
                            </v-btn>
                            <v-spacer></v-spacer>
                            <v-dialog v-model="dialog" max-width="500px" persistent>
                                <v-btn slot="activator" color="primary" dark class="mb-2" @click="onClickNewSalesRep">
                                    New Sales Rep
                                </v-btn>
                                <v-card>
                                    <v-card-title>
                                        <span class="headline">{{ formTitle }}</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-container grid-list-md>
                                            <v-layout wrap>
                                                <v-flex xs12 sm6 md6>
                                                    <v-text-field v-model="editedSalesRep.no" label="No"
                                                                  :disabled="isDisabled"></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm6 md6>
                                                    <v-text-field v-model="editedSalesRep.name"
                                                                  label="Name"></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm6 md4>
                                                    <v-text-field v-model="editedSalesRep.nic" label="NIC"
                                                                  ></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm6 md4>
                                                    <v-text-field v-model="editedSalesRep.email" label="Email"
                                                                  ></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm6 md4>
                                                    <v-select color="light" label="Route" single-line
                                                              :items="editedSalesRep.routes" item-text="name"
                                                              item-value="id"
                                                              @change="getRoute" v-model="selectedRoute"
                                                    ></v-select>
                                                </v-flex>
                                                <v-flex xs12 sm6 md4>
                                                    <v-text-field v-model="editedSalesRep.addLine1"
                                                                  label="Address Line 1"
                                                                  ></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm6 md4>
                                                    <v-text-field v-model="editedSalesRep.addLine2"
                                                                  label="Address Line 2"
                                                                  ></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm6 md4>
                                                    <v-text-field v-model="editedSalesRep.city" label="City"
                                                                  ></v-text-field>
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
                        <v-data-table :headers="headers" :items="salesReps" class="elevation-1">
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.no }}</td>
                                <td>{{ props.item.name }}</td>
                                <td>{{ props.item.nic }}</td>
                                <td>{{ props.item.email }}</td>
                                <td>{{ props.item.route }}</td>
                                <td>{{ props.item.addLine1 }}</td>
                                <td>{{ props.item.addLine2 }}</td>
                                <td>{{ props.item.city }}</td>
                                <td class="justify-center layout px-0 align-center">
                                    <v-icon
                                            small
                                            class="mr-2"
                                            @click="editSalesRep(props.item)"
                                    >
                                        edit
                                    </v-icon>
                                    <v-icon
                                            small
                                            @click="deleteSalesRep(props.item)"
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
<!--                route settings-->
                <v-flex d-flex xs12 sm12 md12 mt-2>
                    <v-card color="dark" light>
                        <v-card-title class="blue--text text-lg-start">Route Settings</v-card-title>
                        <v-toolbar flat color="white">
                            <v-spacer></v-spacer>
                            <v-dialog v-model="routeDialog" max-width="500px" persistent>
                                <v-btn slot="activator" color="primary" dark class="mb-2" @click="onClickNewRoute">
                                    New Route
                                </v-btn>
                                <v-card>
                                    <v-card-title>
                                        <span class="headline">{{ formTitleRoute }}</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-container grid-list-md>
                                            <v-layout wrap>
                                                <v-flex xs12 sm6 md6>
                                                    <v-text-field v-model="editedRoute.no" label="No"
                                                                  :disabled="isDisabled"></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm6 md6>
                                                    <v-text-field v-model="editedRoute.name"
                                                                  label="Name"></v-text-field>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="blue darken-1" flat @click="closeRoute">Cancel</v-btn>
                                        <v-btn color="blue darken-1" flat @click="saveRoute">Save</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </v-toolbar>
                        <v-data-table :headers="headersRoute" :items="routesMain" class="elevation-1">
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.no }}</td>
                                <td>{{ props.item.name }}</td>
                                <td class="justify-center layout px-0 align-center">
                                    <v-icon
                                            small
                                            class="mr-2"
                                            @click="editRoute(props.item)"
                                    >
                                        edit
                                    </v-icon>
                                    <v-icon
                                            small
                                            @click="deleteRoute(props.item)"
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
                <v-flex d-flex xs12 sm12 md12 mt-2>
                    <v-card color="dark" light>
                        <v-card-title class="blue--text text-lg-start">Cash Book Settings</v-card-title>
                        <v-flex d-flex xs12 sm6 md6 mt-2 ml-3>
                            <v-text-field
                                    v-model="initialCash"
                                    outline
                                    label="Cash Book Balance"
                                    type="text"
                                    :disabled="disabledCash"
                            ></v-text-field>
                            <v-btn light small icon @click="onclickEditCashAmount">
                                <v-icon>edit</v-icon>
                            </v-btn>
                        </v-flex>

                    </v-card>
                </v-flex>
                <v-flex d-flex xs12 sm12 md12 mt-2>
                    <v-card color="dark" light>
                        <v-card-title class="blue--text text-lg-start">Bank Book Settings</v-card-title>

                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout row justify-center>
                <v-dialog v-model="defaultPasswordDialog" max-width="400" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            {{defaultPasswordTitle}}
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            <v-text-field v-model="defaultPassword" label="Default Password"></v-text-field>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="red darken-1" @click="defaultPasswordDialog=false" flat="flat">Cancel</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-1" @click="defaultPasswordSave" flat="flat">Save</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
            <v-layout row justify-center>
                <v-dialog v-model="flashErrorDialog" max-width="400" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            {{flashErrorDialogTitle}}
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            <v-list>
                                <v-list-tile v-for="error in salesRepErrors" disabled dark :key="error.title">
                                    <v-list-tile-content v-text="error.title"></v-list-tile-content>
                                </v-list-tile>
                            </v-list>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="green darken-1" @click="flashErrorDialog=false" flat="flat">Ok</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
            <v-layout row justify-center>
                <v-dialog v-model="cashAmountUpdateDialog" max-width="400" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            {{cashAmountDialogTitle}}
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            <v-text-field
                                    label="Cash Amount"
                                    v-model="initialCash"
                            ></v-text-field>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="red darken-1" @click="cashAmountUpdateDialog=false" flat="flat">Cancel</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-1" @click="confirmCashBookAmount" flat="flat">Confirm</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
        </v-container>
    </v-app>
</template>

<script>
    export default {
        name: "SettingsComponent",
        data() {
            return {
                compName: "Settings",
                headers: [
                    {
                        text: "No",
                        sortable: false,
                        align: 'left',
                        value: 'no'
                    },
                    {
                        text: "Name",
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
                        text: "Email",
                        sortable: false,
                        align: 'left',
                        value: 'email'
                    },
                    {
                        text: "Route",
                        sortable: false,
                        align: 'left',
                        value: 'route'
                    },
                    {
                        text: "Address Line 1",
                        sortable: false,
                        align: 'left',
                        value: 'addLine1'
                    },
                    {
                        text: "Address Line 2",
                        sortable: false,
                        align: 'left',
                        value: 'addLine2'
                    },
                    {
                        text: "City",
                        sortable: false,
                        align: 'left',
                        value: 'city'
                    }
                ],
                headersRoute: [
                    {
                        text: "No",
                        sortable: false,
                        align:'left',
                        value:'no'
                    },
                    {
                        text: "Name",
                        sortable: false,
                        align:'left',
                        value:'name'
                    }
                ],
                salesReps: [],
                dialog: false,
                editedSalesRep: {
                    no: '',
                    name: '',
                    nic: '',
                    email: '',
                    routeId: 0,
                    routes: [],
                    addLine1: '',
                    addLine2: '',
                    city: ''
                },
                defaultSalesRep: {
                    no: '',
                    name: '',
                    nic: '',
                    email: '',
                    routeId: 0,
                    routes: [],
                    addLine1: '',
                    addLine2: '',
                    city: ''
                },
                selectedRoute: {
                    id: '',
                    name: ''
                },
                isDisabled: false,
                editedIndex: -1,
                deletedIndex: -1,
                routes: [],
                routeId: 0,
                defaultPassword: "123456",
                defaultPasswordDialog: false,
                defaultPasswordTitle: "Enter Default Password",
                salesRepErrors: [],
                flashErrorDialog: false,
                flashErrorDialogTitle: "",
                routeDialog: false,
                editedIndexRoute: -1,
                editedRoute: {
                    no:'',
                    name: ''
                },
                defaultRoute: {
                    no:'',
                    name: ''
                },
                routesMain: [],
                deletedIndexRoute: -1,
                initialCash:0,
                disabledCash: true,
                cashAmountUpdateDialog:false,
                cashAmountDialogTitle: "Update Cash Book Amount",
            }
        },
        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'New Sales Rep' : 'Edit Sales Rep'
            },
            formTitleRoute() {
                return this.editedIndexRoute === -1 ? 'New Route' : 'Edit Route'
            }
        },
        watch: {
            dialog(val) {
                val || this.close()
            }
        },
        created() {
            this.getBasicData();
            this.isDisableField(true);
        },
        methods: {
            editSalesRep(item) {
                this.editedIndex = this.salesReps.indexOf(item);
                this.setSelectedRoute(item.routeId);
                this.editedSalesRep = Object.assign({}, item);
                this.dialog = true;
            },
            deleteSalesRep(item) {
                this.deleteSalesRepRequest(item);
                this.deletedIndex = this.salesReps.indexOf(item);
            },
            save() {
                if (this.editedIndex > -1) {
                    if (this.validateEditSalesRepRequest()) {
                        this.editSalesRepRequest(this.editedSalesRep);
                        this.close();
                    } else {
                        this.showValidationFailMessage();
                    }
                } else {
                    if (this.validateNewSalesRepRequest()) {
                        this.addNewSalesRepRequest(this.editedSalesRep);
                        this.close();
                    } else {
                        this.showValidationFailMessage();
                    }
                }
            },
            validateNewSalesRepRequest () {
                return this.editedSalesRep.no !== '' && this.editedSalesRep.name!== '' && this.editedSalesRep.addLine1!== '' && this.editedSalesRep.addLine2 !=='' && this.editedSalesRep.email !== '' && this.editedSalesRep.nic !== '' && this.editedSalesRep.city !== '';
            },
            validateEditSalesRepRequest() {
                return this.editedSalesRep.no !== '' && this.editedSalesRep.name!== '' && this.editedSalesRep.addLine1!== '' && this.editedSalesRep.addLine2 !=='' && this.editedSalesRep.email !== '' && this.editedSalesRep.nic !== '' && this.editedSalesRep.city !== '';
            },
            showValidationFailMessage() {
                this.$notify({
                    group: 'auth',
                    title: 'Error',
                    type: 'error',
                    text: "Please fill all required fields properly!",
                    fontsize: '20px',
                    position: 'top center'
                });
            },
            close() {
                this.dialog = false;
                setTimeout(() => {
                    this.editedSalesRep = Object.assign({}, this.defaultSalesRep);
                    this.editedIndex = -1;
                    this.selectedRoute = Object.assign({}, {id: '', name: ''});
                }, 300);
            },
            getRoute(id) {
                this.routeId = id;
            },
            getBasicData() {
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.get('/getSettingsData').then((response) => {
                    if (response.status === 200) {
                        if (!response.data.error) {
                            this.populateUserData(response.data.user);
                            this.populateRoutes(response.data.routes);
                            this.populateSalesRepData(response.data.salesReps);
                            this.populateInitialCashBookAmount(response.data.cash_balance);
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
            populateUserData(user) {

            },
            populateSalesRepData(salesReps) {
                salesReps.forEach((salesRep) => {
                    this.salesReps.push({
                        no: salesRep.id,
                        name: salesRep.name,
                        email: salesRep.email,
                        route: salesRep.route,
                        routes: this.routes,
                        routeId: salesRep.route_id,
                        nic: salesRep.NIC,
                        addLine1: salesRep.addLine1,
                        addLine2: salesRep.addLine2,
                        city: salesRep.city
                    });
                });
            },
            populateRoutes(routes) {
                routes.forEach((route) => {
                    this.editedSalesRep.routes.push({
                        id: route.id,
                        name: route.name
                    })
                });

                routes.forEach((route) => {
                    this.defaultSalesRep.routes.push({
                        id: route.id,
                        name: route.name
                    })
                });

                routes.forEach((route) => {
                    this.routes.push({
                        id: route.id,
                        name: route.name
                    })
                });

                routes.forEach((route) => {
                    this.routesMain.push({
                        no: route.id,
                        name: route.name
                    })
                });
            },
            setSelectedRoute(id) {
                this.routeId = id;
                this.routes.forEach((route) => {
                    if (route.id === id) {
                        this.selectedRoute = Object.assign({}, {id: route.id, name: route.name});
                    }
                });
            },
            onClickNewSalesRep() {
                this.getSalesRepNum();
            },
            getSalesRepNum () {
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.get('/getSalesRepNumber').then((response) => {
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
                            this.populateSalesRepNum(response.data.num);
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
            addNewSalesRepRequest (item) {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/addNewSalesRep', {
                    id: item.no,
                    name: item.name,
                    NIC: item.nic,
                    email: item.email,
                    addLine1: item.addLine1,
                    addLine2: item.addLine2,
                    city: item.city,
                    route_id: this.routeId,
                    password: this.defaultPassword
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
                            this.addNewSalesRepToTable(response.data.salesRep);
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
            populateSalesRepNum (no) {
                this.editedSalesRep.no = no;
            },
            addNewSalesRepToTable(salesRep) {
                this.salesReps.push({
                    no: salesRep.id,
                    name: salesRep.name,
                    email: salesRep.email,
                    route: salesRep.route,
                    routes: this.routes,
                    routeId: salesRep.route_id,
                    nic: salesRep.NIC,
                    addLine1: salesRep.addLine1,
                    addLine2: salesRep.addLine2,
                    city: salesRep.city
                });
            },
            editSalesRepRequest (item) {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/editSalesRep', {
                    id: item.no,
                    name: item.name,
                    NIC: item.nic,
                    email: item.email,
                    addLine1: item.addLine1,
                    addLine2: item.addLine2,
                    city: item.city,
                    route_id: this.routeId
                }).then((response) => {
                    console.log(response);
                    if (response.status === 200) {
                        if (response.data.error) {
                            this.$Progress.fail();
                            if (!response.data.emailPresent) {
                                this.salesRepErrors.push({
                                    title: "The email has already being taken!"
                                });
                            }
                            if (!response.data.nicPresent) {
                                this.salesRepErrors.push({
                                    title: "The NIC has already being taken!"
                                });
                            }
                            if (response.data.message) {
                                this.$notify({
                                    group: 'auth',
                                    title: 'Error',
                                    type: 'error',
                                    text: response.data.message,
                                    fontsize: '20px'
                                });
                            }
                            if (!response.data.emailPresent || !response.data.nicPresent) {
                                this.flashErrorDialogTitle = "Errors in edit request";
                                this.flashErrorDialog = true;
                            }
                        } else {
                            this.$Progress.finish();
                            this.$notify({
                                group: 'auth',
                                title: 'Success',
                                type: 'success',
                                text: response.data.message,
                                fontsize: '20px'
                            });
                            this.addEditSalesRepToTable();
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
            deleteSalesRepRequest(item) {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/deleteSalesRep', {
                    id: item.no
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
                            this.deleteSalesRepFromTable();
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
            deleteSalesRepFromTable() {
                this.salesReps.splice(this.deleteIndex, 1);
            },
            addEditSalesRepToTable() {
                Object.assign(this.salesReps[this.editedIndex], this.editedSalesRep);
            },
            defaultPasswordSave() {
                this.defaultPasswordDialog = false;
            },
            onClickDefaultPassword() {
                this.defaultPasswordDialog = true;
            },
            isDisableField(val) {
                this.isDisabled = !!val;
            },
            onClickNewRoute() {
                this.getRouteNoRequest();
            },
            closeRoute() {
                this.routeDialog = false;
                setTimeout(() => {
                    this.editedRoute = Object.assign({}, this.defaultRoute);
                    this.editedIndexRoute = -1;
                }, 300);
            },
            saveRoute() {
                if (this.editedIndexRoute > -1) {
                    if (this.validateEditRouteRequest()) {
                        this.editRouteRequest(this.editedRoute);
                        this.closeRoute();
                    } else {
                        this.showValidationFailMessage();
                    }
                } else {
                    if (this.validateNewRouteRequest()) {
                        this.addNewRouteRequest(this.editedRoute);
                        this.closeRoute();
                    } else {
                        this.showValidationFailMessage();
                    }
                }
            },
            validateEditRouteRequest() {
                return this.editedRoute.no !== '' && this.editedRoute.name !== '';
            },
            validateNewRouteRequest() {
                return this.editedRoute.no !== '' && this.editedRoute.name !== '';
            },
            deleteRoute(item) {
                this.deleteRouteRequest(item);
                this.deletedIndexRoute = this.routesMain.indexOf(item);
            },
            editRoute (item) {
                this.editedIndexRoute = this.routesMain.indexOf(item);
                this.editedRoute = Object.assign({}, item);
                this.routeDialog = true;
            },
            addNewRouteRequest(item) {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/addNewRoute', {
                    name: item.name,
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
                            this.addNewRouteToTable(response.data.route);
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
            editRouteRequest(item) {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/editRoute', {
                    id: item.no,
                    name: item.name
                }).then((response) => {
                    console.log(response);
                    if (response.status === 200) {
                        if (response.data.error) {
                            this.$Progress.fail();
                            if (response.data.message) {
                                this.$notify({
                                    group: 'auth',
                                    title: 'Error',
                                    type: 'error',
                                    text: response.data.message,
                                    fontsize: '20px'
                                });
                            }
                        } else {
                            this.$Progress.finish();
                            this.$notify({
                                group: 'auth',
                                title: 'Success',
                                type: 'success',
                                text: response.data.message,
                                fontsize: '20px'
                            });
                            this.addEditRouteToTable();
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
            deleteRouteRequest(item) {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/deleteRoute', {
                    id: item.no
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
                            this.deleteRouteFromTable();
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
            getRouteNoRequest() {
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.get('/getRouteNumber').then((response) => {
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
                            this.populateRouteNum(response.data.num);
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
            populateRouteNum(no) {
                this.editedRoute.no = no;
            },
            deleteRouteFromTable() {
                this.routesMain.splice(this.deletedIndexRoute, 1);
            },
            addNewRouteToTable(route) {
                this.routesMain.push({
                    no: route.id,
                    name: route.name
                });
            },
            addEditRouteToTable() {
                Object.assign(this.routesMain[this.editedIndexRoute], this.editedRoute);
            },
            populateInitialCashBookAmount(cash_amount) {
                this.initialCash = cash_amount;
            },
            onclickEditCashAmount() {
                this.cashAmountUpdateDialog=true;
            },
            confirmCashBookAmount() {
                this.cashAmountUpdateDialog=false;
                this.cashBookUpdateRequest();
            },
            cashBookUpdateRequest() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/cashBookAmountUpdate', {
                    amount: this.initialCash
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
                    console.log(error);
                    this.$notify({
                        group: 'auth',
                        title: 'Error',
                        type: 'error',
                        text: error,
                        fontsize: '20px'
                    });
                });
            }
        }
    }
</script>

<style scoped>

</style>