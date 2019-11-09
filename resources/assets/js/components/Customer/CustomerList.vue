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
                        <v-data-table :headers="headers" :items="customers" class="elevation-1" :search="search">
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.no }}</td>
                                <td>{{ props.item.name }}</td>
                                <td>{{ props.item.nic }}</td>
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
        name: "CustomerList",
        data () {
            return{
                listName: "Customer List",
                headers: [
                    {
                        text: 'Customer No',
                        align: 'left',
                        sortable: false,
                        value: 'no'
                    },
                    {
                        text: 'Customer Name',
                        align: 'left',
                        sortable: false,
                        value: 'name'
                    },
                    {
                        text: 'NIC',
                        align: 'left',
                        sortable: false,
                        value: 'nic'
                    }
                ],
                customers: [],
                sortCustomer: [],
                search: [],
                routes: [
                    {
                        name:"ALL",
                        id: -1
                    }
                ],
                selectedId: 0
            }
        },

        methods: {
            getData() {
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.get('/getCustomerList').then((response) => {
                    if (response.status === 200) {
                        if (!response.data.error) {
                            response.data.routes.forEach((route) => {
                                this.pushDefaultRoute(route.name, route.id)
                            });
                            response.data.customers.forEach((customerSet) => {
                                customerSet.customers.forEach((item) => {
                                    this.init(item.name, item.customer_no, item.NIC, item.route_id)
                                })
                            });
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

            init(name, no, nic, route_id) {
                this.customers.push({
                    name: name,
                    no: no,
                    nic: nic,
                    route_id: route_id
                });
                this.sortCustomer.push({
                    name: name,
                    no: no,
                    nic: nic,
                    route_id: route_id
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
                    this.customers = this.sortCustomer;
                } else {
                    this.customers = this.sortCustomer.filter((customer) => {
                        return customer.route_id === id;
                    });
                }
            }
        },

        created() {
            this.getData()
        }
    }
</script>

<style scoped>

</style>
