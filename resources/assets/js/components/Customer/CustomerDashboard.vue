<template>
    <v-app>
        <v-container fluid>
            <v-layout row wrap>
                <v-flex xs12 sm12 md12 lg12>
                    <v-card color="dark" light>

                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout row wrap justify-center>
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

                        <v-card-text class="headline font-weight-bold text-lg-center">
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

                        <v-card-text class="headline font-weight-bold text-lg-center">
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
                                              item-text="name" item-value="id" v-model="selectedId" @change="selectRoute"></v-select>
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
                            <v-btn slot="activator" color="primary" dark class="mb-2" @click="resetSelectedRoute">New Customer</v-btn>
                            <v-card>
                                <v-card-title class="pb-0">
                                    <span class="headline">{{ formTitle }}</span>
                                </v-card-title>

                                <v-card-text class="pa-0">
                                    <v-container grid-list-md class="pt-0">
                                        <v-layout wrap>
                                            <v-flex xs12 sm4 md4>
                                                <v-text-field v-model="editedItem.customerNo"
                                                              label="Customer No"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4>
                                                <v-text-field v-model="editedItem.nicNo" label="NIC"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4>
                                                <!--<v-text-field v-model="editedItem.route" label="Route"></v-text-field>-->
                                                <v-select color="light" label="Route" single-line
                                                          :items="editedItem.route" item-text="name" item-value="id"
                                                          @change="getRouteId" v-model="selected"></v-select>
                                            </v-flex>
                                            <v-flex xs12 sm6 md6>
                                                <v-text-field v-model="editedItem.customerName"
                                                              label="Customer Name"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm6 md6>
                                                <v-text-field v-model="editedItem.contactNo"
                                                              label="Contact No"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4>
                                                <v-text-field v-model="editedItem.addLine1"
                                                              label="Address Line1"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4>
                                                <v-text-field v-model="editedItem.addLine2"
                                                              label="Address Line2"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4>
                                                <v-text-field v-model="editedItem.city" label="City"></v-text-field>
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
                            <v-btn color="primary" @click="initialize">Reset</v-btn>
                        </template>
                    </v-data-table>
                </v-flex>
            </v-layout>
        </v-container>
    </v-app>
</template>

<script>
    export default {
        name: 'customerDashboard',
        data() {
            return {
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
                routeId: '',
                selectedId: 0,
                selectedRouteId: 0,
                selected: {
                    name: "",
                    id: 0
                }
            }
        },
        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'New Customer' : 'Edit Customer'
            }
        },
        watch: {
            dialog(val) {
                val || this.close()
            }
        },
        created() {
            this.getSalesRepRoutes()
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
                this.pushRoutes();
                this.changeSelectedRoute(this.customers[this.editedIndex].route_id);
                this.editedItem = Object.assign({}, item);
                console.log(this.editedItem);
                this.dialog = true
            },

            deleteItem(item) {
                const index = this.customers.indexOf(item);
                confirm('Are you sure you want to delete this item?') && this.customers.splice(index, 1)
            },

            close() {
                this.dialog = false;
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem);
                    this.editedIndex = -1;
                    this.pushRoutes()
                }, 300)
            },

            save() {
                if (this.editedIndex > -1) {
                    Object.assign(this.customers[this.editedIndex], this.editedItem)
                } else {
                    this.addCustomerToDb(this.editedItem)
                }
                this.close()
            },

            getSalesRepRoutes() {

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
                            this.pushRoutes();
                            response.data.customers.forEach((item) => {
                                this.initialize(item);
                            });
                            console.log(response.data.customers);
                            console.log(response.data.salesReps);
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
                        this.customers.push(this.editedItem);
                        this.$Progress.finish();
                        this.$notify({
                            group: 'auth',
                            title: 'Success',
                            type: 'success',
                            text: response.data.message,
                            fontsize: '20px'
                        });
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
                if(this.selectedId === -1) {
                    if(this.deactivate) {
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

            pushRoutes () {
                this.editedItem.route = this.routesCopy;
                this.defaultItem.route = this.routesCopy
            },

            changeSelectedRoute(id) {
                this.routes.forEach(( route ) => {
                    if( route.id === id ){
                        this.selected.id = route.id;
                        this.selected.name = route.name;
                    }
                });
            },

            resetSelectedRoute() {
                this.selectedRouteId = 0
            }
        }
    }
</script>

<style scoped>

</style>
