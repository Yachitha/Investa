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
                                    <v-flex sm3 offset-sm1>
                                        <v-select color="light" label="Route" single-line hide-details :items="routes">
                                        </v-select>
                                    </v-flex>
                                    <v-flex sm3 offset-sm1>
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
                                            min-width="290px"
                                        >
                                            <v-text-field
                                                slot="activator"
                                                v-model="date"
                                                prepend-icon="event"
                                                readonly
                                                hide-details
                                            ></v-text-field>
                                            <v-date-picker v-model="date" no-title scrollable>
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
                <v-flex d-flex xs12 sm6 md6 mt-2>
                    <v-card color="dark" light>
                        <v-layout column wrap pr-2 pl-2>
                            <v-flex d-flex mt-2>
                                <v-text-field
                                    label="BF AMOUNT"
                                    placeholder="xxxx.xx"
                                    hide-details
                                    disabled
                                    outline
                                    readonly
                                    box
                                ></v-text-field>
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
                                        <v-data-table :headers="headers" :items="payments" class="elevation-1">
                                            <template slot="items" slot-scope="props">
                                                <td>{{ props.item.routeName }}</td>
                                                <td class="text-xs-left">{{ props.item.payment }}</td>
                                            </template>
                                        </v-data-table>
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
                                        <v-subheader>{{labelLoanPayment}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="145896.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelExtraIncome}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="0.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelSuppCashLoan}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="0.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelSuppCheckLoan}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="0.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelEmpLoanPayment}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="0.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelAccess}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="0.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelIncome}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="5507040.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-card>
                </v-flex>
                <v-flex d-flex xs12 sm6 md6 mt-2 pl-2>
                    <v-card color="dark" light>
                        <v-layout column wrap pr-2>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelLoanCash}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="5507040.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelLoanCheque}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="5507040.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelSalaryPay}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="5507040.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelSupLoanPayCash}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="5507040.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelSupLoanPayChe}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="5507040.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelEmpLoan}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="5507040.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelExtraExp}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="5507040.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelExp}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="5507040.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2 pl-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-divider light></v-divider>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelTotalInc}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="5507040.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelBankDeposit}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="5507040.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelDayCash}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="5507040.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelCashOut}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="5507040.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelBalance}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="5507040.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelCashInLocker}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="5507040.00"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2 mb-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelDayProfit}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled label="5507040.00"
                                                      height=30 class="resize_field"></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
        <v-container fluid id="content" v-show="isPrint">
            <v-layout>
                <v-flex md6 align-start>
                    <h2>{{ companyName }}</h2>
                </v-flex>
                <v-flex md6>
                    <h2 class="text-lg-right">{{ reportName }}: {{ createDateFormat }}</h2>
                </v-flex>
            </v-layout>
            <v-layout>
                <v-flex md4>
                    <p class="text-lg-left">{{ routeLabel }}: {{ routeNameToUppercase }}</p>
                </v-flex>
                <v-flex md4>
                    <p class="text-lg-center">{{ salesRepLabel }}: {{ salesRepNameToUpperCase }}</p>
                </v-flex>
                <v-flex md4>
                    <p class="text-lg-right">{{ dueTotLabel }}: {{ dueTotValue }}</p>
                </v-flex>
            </v-layout>
            <v-layout>
                <table class="tbs" width="100%">
                    <thead>
                    <tr class="table_row">
                        <th class="table_header">BF Amount</th>
                        <th class="table_header">Loan Payment</th>
                        <th class="table_header">Loan Investment</th>
                        <th class="table_header">Extra Expenses</th>
                        <th class="table_header">Balance</th>
                        <th class="table_header">Access</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="table_data">0.00</td>
                        <td class="table_data">1500.00</td>
                        <td class="table_data">0.00</td>
                        <td class="table_data">0.00</td>
                        <td class="table_data">1500.00</td>
                        <td class="table_data">0.00</td>
                    </tr>
                    </tbody>
                </table>
            </v-layout>
            <v-container fluid grid-list-lg>
                <v-layout row wrap>
                    <!--suppress CommaExpressionJS -->
                    <v-flex lg6 v-for="position, index in items[0]" :data="position" :key="position.index"
                            id="data_wrapper">
                        <table class="mainData" width="100%">
                            <tr class="tb-tr" v-for="item in position">
                                <td class="tb-td">{{ item.id }}</td>
                                <td class="tb-td">{{ item.name }}</td>
                                <td class="tb-td">{{ item.amount }}</td>
                            </tr>
                        </table>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-container>
    </v-app>
</template>

<script>
    import moment from 'moment';
    import $ from 'jquery';
    import JsPDF from 'jspdf';
    import * as html2canvas from "html2canvas";

    export default {
        name: "DaySheetComponent",
        beforeMount() {
            // this.getData();
        },
        mounted() {

        },
        data() {
            return {
                companyName: "SOUTHERN INVESTMENT",
                reportName: "Day Sheet",
                routeLabel: "Route",
                routeName: "ambalangoda",
                salesRepLabel: "Sales Rep",
                salesRepName: "Amal",
                dueTotLabel: "Due Total Collection",
                dueTotValue: 85000.00,
                items: [],
                createDate: new Date(),
                isPrint: false,
                routes: ["Galle", "karapitiya", "Ahangama"],
                date: new Date().toISOString().substr(0, 10),
                menu: false,
                modal: false,
                menu2: false,
                bfAmount: "BF AMOUNT",
                labelLoanPayment: "Loan Payments",
                labelExtraIncome: "Extra Income",
                labelSuppCashLoan: "Supplier Cash Loan",
                labelSuppCheckLoan: "Supplier Cheque Loan",
                labelEmpLoanPayment: "Emp Loan Payment",
                labelAccess: "Access",
                labelIncome: "Income",
                labelLoanCash: "Loan(cash)",
                labelLoanCheque: "Loan(cheque)",
                labelSalaryPay: "Salary Payment",
                labelSupLoanPayCash: "S/ Loan Pay (Cash)",
                labelSupLoanPayChe: "S/ Loan Pay (Cheque)",
                labelEmpLoan: "Employee Loan",
                labelExtraExp: "Extra expenses",
                labelExp: "expenses",
                labelTotalInc: "Total Income",
                labelBankDeposit: "Bank Deposit",
                labelDayCash: "Day Cash",
                labelCashOut: "Cash Out",
                labelBalance: "Balance",
                labelCashInLocker: "Cash in Locker",
                labelDayProfit: "Day Profit",
                headers: [
                    {
                        text: 'Route',
                        align: 'left',
                        sortable: false,
                        value: 'routeName'
                    },
                    {
                        text: 'Total Payments',
                        value: 'payment'
                    }
                ],
                payments: [
                    {
                        value: false,
                        routeName: 'Galle',
                        payment: 51400.00
                    },
                    {
                        value: false,
                        routeName: 'Karapitiya',
                        payment: 41400.00
                    },
                    {
                        value: false,
                        routeName: 'Ahangama',
                        payment: 31400.00
                    }
                ]
            }
        },
        created() {
            this.getData()
        },
        methods: {
            getData() {
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.get('/getInitialDataDaySheet').then((response) => {
                    console.log(response);
                    if (response.status === 200) {
                        if (!response.data.error) {
                            // noinspection JSUnresolvedVariable

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
            populateData(data) {

            },
            createPdf() {
                // noinspection JSUnresolvedFunction
                html2canvas($('#content')[0]).then(canvas => {
                    let img = canvas.toDataURL("image/png", 1);
                    let doc = new JsPDF('p', 'mm', "a4");
                    // noinspection JSUnresolvedVariable
                    let imgWidth = doc.internal.pageSize.getWidth();
                    // noinspection JSUnresolvedVariable
                    let pageHeight = doc.internal.pageSize.getHeight();
                    let imgHeight = canvas.height * imgWidth / canvas.width;
                    let heightLeft = imgHeight;
                    let position = 0;
                    let fileName = "day_sheet" + this.createDateFormat;
                    // noinspection JSUnresolvedFunction
                    doc.addImage(img, 'png', 0, position, imgWidth, imgHeight);

                    heightLeft -= pageHeight;

                    while (heightLeft >= 0) {
                        position = heightLeft - imgHeight;
                        // noinspection JSUnresolvedFunction
                        doc.addPage();
                        // noinspection JSUnresolvedFunction
                        doc.addImage(img, 'png', 0, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight;
                    }
                    // noinspection JSUnresolvedFunction
                    doc.save(fileName);
                }).catch(err => {
                    console.log(err);
                });
            }
        },
        computed: {
            createDateFormat: function () {
                return moment(this.createDate).format("YYYY/MM/DD");
            },
            routeNameToUppercase: function () {
                return this.routeName.toUpperCase();
            },
            salesRepNameToUpperCase: function () {
                return this.salesRepName.toUpperCase();
            }
        }
    }
</script>

<style scoped>
    .table_row, .table_header, .table_data {
        font-size: small;
        border: 1px solid black;
        text-align: center;
        line-height: 50px;
    }

    .mainData .tb-tr {
        font-size: medium;
        text-align: left;
        border: none;
        line-height: 30px;
    }

    #data_wrapper {
        margin-bottom: 5mm;
    }
    .resize_field label[for]{
        height: 20px;
        font-size: 10pt;
    }
</style>
