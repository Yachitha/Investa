<template>
    <v-app>
        <v-container fluid grid-list-sm>
            <v-layout row wrap>
                <v-flex d-flex xs12 sm5 md5>
                    <v-layout column wrap>
                        <v-flex d-flex>
                            <v-card light color="dark">
                                <v-subheader>{{ labelSearchCustomer }}</v-subheader>
                                <v-form>
                                    <v-container class="pt-0">
                                        <v-layout row wrap>

                                            <v-flex d-flex>
                                                <v-text-field
                                                    label="Customer No"
                                                    :rules="[rules.customerNo]"
                                                ></v-text-field>
                                            </v-flex>

                                            <v-flex d-flex>
                                                <v-text-field
                                                    label="Customer Name"
                                                    :rules="[rules.customerName]"
                                                ></v-text-field>
                                            </v-flex>

                                        </v-layout>
                                    </v-container>
                                </v-form>
                            </v-card>
                        </v-flex>
                        <v-flex d-flex>
                            <v-layout row>
                                <v-flex d-flex>
                                    <v-card light color="dark">
                                        <v-subheader>Loan Form</v-subheader>
                                        <v-form>
                                            <v-container class="pt-0">
                                                <v-layout row wrap>
                                                    <v-flex d-flex>
                                                        <v-text-field
                                                            label="Loan Number"
                                                            disabled
                                                            v-model="loanNo"
                                                        ></v-text-field>
                                                    </v-flex>
                                                </v-layout>
                                                <v-layout row wrap>
                                                    <v-flex d-flex>
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
                                                        >
                                                            <v-text-field
                                                                slot="activator"
                                                                v-model="startDate"
                                                                prepend-icon="event"
                                                                readonly
                                                                label="Start Date"
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
                                                </v-layout>
                                                <v-layout row wrap>
                                                    <v-radio-group v-model="dayCount" :mandatory="false" row>
                                                        <v-radio label="30 days" value="30" color="primary"></v-radio>
                                                        <v-radio label="60 days" value="60" color="primary"></v-radio>
                                                        <v-radio label="90 days" value="90" color="primary"></v-radio>
                                                    </v-radio-group>
                                                </v-layout>
                                                <v-layout row wrap>
                                                    <v-flex d-flex>
                                                        <v-text-field
                                                            label="End Date"
                                                            disabled
                                                            v-model="endDate"
                                                        ></v-text-field>
                                                    </v-flex>
                                                </v-layout>
                                                <v-layout row wrap>
                                                    <v-flex d-flex>
                                                        <v-select
                                                            color="light"
                                                            label="sales Rep"
                                                            :items="salesRepNames">
                                                        </v-select>
                                                    </v-flex>
                                                </v-layout>
                                                <v-layout row wrap>
                                                    <v-flex d-flex>
                                                        <v-text-field
                                                            label="Commission(%)"
                                                            v-model="commission"
                                                        ></v-text-field>
                                                    </v-flex>
                                                    <v-flex d-flex>
                                                        <v-text-field
                                                            label="Commission Amount"
                                                            v-model="commAmount"
                                                        ></v-text-field>
                                                    </v-flex>
                                                </v-layout>
                                                <v-layout row wrap>
                                                    <v-flex d-flex>
                                                        <v-select
                                                            color="light"
                                                            label="Type"
                                                            :items="types">
                                                        </v-select>
                                                    </v-flex>
                                                </v-layout>
                                                <v-layout row wrap>
                                                    <v-flex d-flex>
                                                        <v-text-field
                                                            label="Loan Amount"
                                                            v-model="loanAmount"
                                                        ></v-text-field>
                                                    </v-flex>
                                                </v-layout>
                                                <v-layout row wrap>
                                                    <v-flex d-flex>
                                                        <v-text-field
                                                            label="Loan Interest %"
                                                            v-model="loanInterest"
                                                        ></v-text-field>
                                                    </v-flex>
                                                </v-layout>
                                                <v-layout row wrap>
                                                    <v-flex d-flex>
                                                        <v-text-field
                                                            label="Days"
                                                            v-model="loanDays"
                                                        ></v-text-field>
                                                    </v-flex>
                                                </v-layout>
                                                <v-layout row wrap>
                                                    <v-flex d-flex>
                                                        <v-text-field
                                                            label="Payment Installment"
                                                            v-model="paymentInstallment"
                                                        ></v-text-field>
                                                    </v-flex>
                                                </v-layout>
                                                <v-layout row wrap>
                                                    <v-flex d-flex>
                                                        <v-text-field
                                                            label="Total Loan"
                                                            v-model="totalLoan"
                                                        ></v-text-field>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-form>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                    </v-layout>
                </v-flex>
                <v-flex d-flex xs12 sm7 md7>
                    <v-layout column wrap>
                        <v-flex d-flex>
                            <v-card color="dark" light>
                                <v-subheader>Loans</v-subheader>
                                <v-data-table :headers="headers" :items="loans" class="elevation-1">
                                    <template slot="items" slot-scope="props">
                                        <td>{{ props.item.no }}</td>
                                        <td>{{ props.item.amount }}</td>
                                        <td>{{ props.item.interest }}</td>
                                        <td>{{ props.item.daysCount }}</td>
                                        <td>{{ props.item.installments }}</td>
                                        <td>{{ props.item.total }}</td>
                                        <td>{{ props.item.due }}</td>
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
                </v-flex>
            </v-layout>
        </v-container>
    </v-app>
</template>

<script>
    export default {
        name: "CustomerLoanComponent",
        data() {
            return {
                customerNo: 0,
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
                        text: 'Interest',
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
                        text: 'Installments',
                        align: 'left',
                        sortable: false,
                        value: 'installments'
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
                loans: [
                    {
                        value: false,
                        no: '100021',
                        amount: 1000,
                        interest: 2,
                        daysCount: 15,
                        installments: 6,
                        total: 50000,
                        due: 15000
                    },
                    {
                        value: false,
                        no: '100021',
                        amount: 1000,
                        interest: 2,
                        daysCount: 15,
                        installments: 6,
                        total: 50000,
                        due: 15000
                    },
                    {
                        value: false,
                        no: '100021',
                        amount: 1000,
                        interest: 2,
                        daysCount: 15,
                        installments: 6,
                        total: 50000,
                        due: 15000
                    },
                    {
                        value: false,
                        no: '100021',
                        amount: 1000,
                        interest: 2,
                        daysCount: 15,
                        installments: 6,
                        total: 50000,
                        due: 15000
                    },
                    {
                        value: false,
                        no: '100021',
                        amount: 1000,
                        interest: 2,
                        daysCount: 15,
                        installments: 6,
                        total: 50000,
                        due: 15000
                    },
                    {
                        value: false,
                        no: '100021',
                        amount: 1000,
                        interest: 2,
                        daysCount: 15,
                        installments: 6,
                        total: 50000,
                        due: 15000
                    }
                ],
                loanNo: 709,
                startDate: new Date().toISOString().substr(0, 10),
                menu2: false,
                dayCount: '30',
                endDate: new Date().toISOString().substr(0, 10),
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
                commission: 2,
                commAmount: 400,
                types: [
                    "cash",
                    "bank",
                    "both"
                ],
                loanAmount: 20000,
                loanInterest: 6,
                loanDays: 30,
                paymentInstallment: 400,
                totalLoan: 25000
            }
        },
        mounted() {
            console.log(this.dayCount)
        }
    }
</script>

<style scoped>

</style>
