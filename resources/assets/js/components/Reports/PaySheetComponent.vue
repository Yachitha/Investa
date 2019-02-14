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
                                                  :items="salesRepNames">
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
                                        <v-text-field solo outline hide-details disabled label="0.00"
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
                                        <v-text-field solo outline hide-details disabled label="0.00"
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
                                        <v-text-field solo outline hide-details disabled label="0.00"
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
                                        <v-text-field solo outline hide-details disabled label="0.00"
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
                                        <v-text-field solo outline hide-details disabled label="0.00"
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
                                    <v-flex d-flex xs12 sm6 md6>
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
                                    <v-flex d-flex ml-2>
                                        <v-text-field solo outline label="Payment" height="12"></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row justify-left>
                                    <v-flex d-flex sm4 md4>
                                        <v-btn color="success">Save</v-btn>
                                    </v-flex>
                                    <v-flex d-flex sm4 md4>
                                        <v-btn color="warn">Cancel</v-btn>
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
                                        <td :colspan="2">
                                            <strong>{{ labelTotalLoanAmount }}</strong>
                                        </td>
                                        <td>
                                            <v-text-field solo outline hide-details disabled label="0.00" height="12"></v-text-field>
                                        </td>
                                        <td>
                                            <strong>{{ labelTotalLoanCommission }}</strong>
                                        </td>
                                        <td>
                                            <v-text-field solo outline hide-details disabled label="0.00" height="12"></v-text-field>
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
                    </v-card>
                </v-flex>
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
                salesRepNames: [
                    "Raveendra",
                    "Madushanka",
                    "sampath",
                    "Asanka",
                    "sahan",
                    "nuwan",
                    "gamege",
                    "sameera"
                ],
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
                payments: [
                    {
                        value: false,
                        date: '',
                        payment: 51400.00
                    },
                    {
                        value: false,
                        date: '',
                        payment: 41400.00
                    },
                    {
                        value: false,
                        date: '',
                        payment: 31400.00
                    }
                ],
                employeeCollections: [
                    {
                        value: false,
                        code: '1000',
                        loanNo: '2000',
                        customerName: "asdasfafnakjaskjsah asjdkjsadh",
                        loanAmount: 500000,
                        percentage: 2,
                        commission: 10000
                    }
                ]
            }
        }
    }
</script>

<style scoped>

</style>
