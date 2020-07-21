<!--suppress JSUnresolvedVariable -->
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
                                        <v-select color="light" label="Route"
                                                  single-line hide-details
                                                  :items="routes"
                                                  v-model="routesDef"
                                                  item-text="name"
                                                  item-value="id"
                                                  @change="changeRoute">
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
                                    placeholder="0.00"
                                    hide-details
                                    disabled
                                    outline
                                    readonly
                                    box
                                    v-model="bfAmount"
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
                                                <td class="text-xs-left">{{ Number(props.item.payment).toLocaleString()
                                                    }}
                                                </td>
                                                <td class="text-xs-left">{{ Number(props.item.dueTotal).toLocaleString()
                                                    }}
                                                </td>
                                            </template>
                                            <template slot="footer">
                                                <tr :style="{backgroundColor:'orange'}">
                                                    <td>
                                                        {{ labelTotalCollection }}
                                                    </td>
                                                    <td>{{ Number(getTotalCollection).toLocaleString() }}</td>
                                                    <td>{{ Number(getTotalDueCollection).toLocaleString() }}</td>
                                                </tr>
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
                                        <v-text-field solo outline hide-details disabled
                                                      v-model="income_details.total_col"
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
                                        <v-text-field solo outline hide-details disabled
                                                      v-model="income_details.ex_income"
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
                                        <v-text-field solo outline hide-details disabled
                                                      v-model="sup_loan_details.sup_loan_cash"
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
                                        <v-text-field solo outline hide-details disabled
                                                      v-model="sup_loan_details.sup_loan_che"
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
                                        <v-text-field solo outline hide-details disabled
                                                      v-model="income_details.emp_loan_pay"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelAccess}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5 @click="accessEditDialog=true">
                                        <v-text-field solo outline hide-details disabled v-model="access"
                                                      height=30 background-color="primary"></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelIncome}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled v-model="income"
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
                                        <v-text-field solo outline hide-details disabled
                                                      v-model="expense_details.loan_total_cash"
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
                                        <v-text-field solo outline hide-details disabled
                                                      v-model="expense_details.loan_total_che"
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
                                        <v-text-field solo outline hide-details disabled
                                                      v-model="expense_details.salary_payment"
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
                                        <v-text-field solo outline hide-details disabled
                                                      v-model="expense_details.total_supplier_payment_cash"
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
                                        <v-text-field solo outline hide-details disabled
                                                      v-model="expense_details.total_supplier_payment_cheque"
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
                                        <v-text-field solo outline hide-details disabled
                                                      v-model="expense_details.total_emp_loan"
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
                                        <v-text-field solo outline hide-details disabled
                                                      v-model="expense_details.extra_expenses"
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
                                        <v-text-field solo outline hide-details disabled
                                                      v-model="expense_details.expenses"
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
                                        <v-text-field solo outline hide-details disabled v-model="getTotalIncome"
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
                                        <v-text-field solo outline hide-details disabled v-model="bank_deposit"
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
                                        <v-text-field solo outline hide-details disabled v-model="getDayCash"
                                                      height=30></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelCashOut}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5 @click="cashOutEditDialog = true">
                                        <v-text-field solo outline hide-details disabled v-model="cash_out"
                                                      height=30 background-color="primary"></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex d-flex mt-2>
                                <v-layout row>
                                    <v-flex d-flex>
                                        <v-subheader>{{labelBalance}}</v-subheader>
                                    </v-flex>
                                    <v-flex d-flex md5>
                                        <v-text-field solo outline hide-details disabled v-model="getBalance"
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
                                        <v-text-field solo outline hide-details disabled v-model="getCashInLocker"
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
                                        <v-text-field solo outline hide-details disabled
                                                      v-model="Number(day_profit).toLocaleString()"
                                                      height=30 class="resize_field"></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-card>
                </v-flex>
            </v-layout>
            <!--            <v-layout row mt-2>-->
            <!--                <v-flex wrap>-->
            <!--                    <v-card class="bg-gradient">-->
            <!--                        <v-layout row p-2>-->
            <!--                            <v-flex>-->
            <!--                                <v-card-title class="white&#45;&#45;text summary-text">Actions</v-card-title>-->
            <!--                            </v-flex>-->
            <!--                            <v-spacer></v-spacer>-->
            <!--                            <v-flex class="text-xs-right">-->
            <!--                                <v-menu left>-->
            <!--                                    <v-btn slot="activator" dark icon>-->
            <!--                                        <v-icon>settings</v-icon>-->
            <!--                                    </v-btn>-->
            <!--                                    <v-list>-->
            <!--                                        <v-list-tile v-for="(item, i) in footerMenu" :key="i" @click="enableFooter">-->
            <!--                                            <v-list-tile-title>{{ item.title }}</v-list-tile-title>-->
            <!--                                        </v-list-tile>-->
            <!--                                    </v-list>-->
            <!--                                </v-menu>-->
            <!--                            </v-flex>-->
            <!--                        </v-layout>-->
            <!--                        <v-layout row p-2>-->
            <!--                            <v-flex xs12 sm12 md4>-->
            <!--                                <v-card-text class="summary-sub-text">Access Balancing</v-card-text>-->
            <!--                                <v-divider></v-divider>-->
            <!--                                <v-layout row mt-2 ml-2>-->
            <!--                                    <v-flex wrap xs10 sm10 md10>-->
            <!--                                        <v-form-->
            <!--                                            ref="form"-->
            <!--                                            lazy-validation>-->
            <!--                                            <v-text-field-->
            <!--                                                v-model="access"-->
            <!--                                                required-->
            <!--                                                solo-->
            <!--                                                outline-->
            <!--                                                clearable-->
            <!--                                                color="light"-->
            <!--                                                dark-->
            <!--                                                :disabled="disableFooter"-->
            <!--                                            >-->
            <!--                                            </v-text-field>-->
            <!--                                        </v-form>-->
            <!--                                    </v-flex>-->
            <!--                                </v-layout>-->
            <!--                            </v-flex>-->
            <!--                            <v-divider class="mx-3" inset vertical></v-divider>-->
            <!--                            <v-flex xs12 sm12 md4>-->
            <!--                                <v-card-text class="summary-sub-text">Cash Out Balancing</v-card-text>-->
            <!--                                <v-divider></v-divider>-->
            <!--                                <v-layout row mt-2 ml-2>-->
            <!--                                    <v-flex wrap xs10 sm10 md10>-->
            <!--                                        <v-form-->
            <!--                                            ref="form"-->
            <!--                                            lazy-validation>-->
            <!--                                            <v-text-field-->
            <!--                                                v-model="cash_out"-->
            <!--                                                required-->
            <!--                                                solo-->
            <!--                                                outline-->
            <!--                                                clearable-->
            <!--                                                color="light"-->
            <!--                                                dark-->
            <!--                                                :disabled="disableFooter"-->
            <!--                                            >-->
            <!--                                            </v-text-field>-->
            <!--                                        </v-form>-->
            <!--                                    </v-flex>-->
            <!--                                </v-layout>-->
            <!--                            </v-flex>-->
            <!--                            <v-divider class="mx-3" inset vertical></v-divider>-->
            <!--                            <v-flex xs12 sm12 md4>-->
            <!--                                <v-card-text class="summary-sub-text">Cash In Locker</v-card-text>-->
            <!--                                <v-divider></v-divider>-->
            <!--                                <v-layout row mt-2 ml-2>-->
            <!--                                    <v-flex wrap xs10 sm10 md10>-->
            <!--                                        <v-form-->
            <!--                                            ref="form"-->
            <!--                                            lazy-validation>-->
            <!--                                            <v-text-field-->
            <!--                                                v-model="cash_in_locker"-->
            <!--                                                required-->
            <!--                                                solo-->
            <!--                                                outline-->
            <!--                                                clearable-->
            <!--                                                color="light"-->
            <!--                                                dark-->
            <!--                                                :disabled="disableFooter"-->
            <!--                                            >-->
            <!--                                            </v-text-field>-->
            <!--                                        </v-form>-->
            <!--                                    </v-flex>-->
            <!--                                </v-layout>-->
            <!--                            </v-flex>-->
            <!--                        </v-layout>-->
            <!--                        <v-layout p-2 row wrap justify-end>-->
            <!--                            <v-divider></v-divider>-->
            <!--                            <v-flex xs6 sm4 md4 shrink class="text-xs-right">-->
            <!--                                <v-btn color="#6109A3" dark @click="completeDaySheet">finalize day sheet</v-btn>-->
            <!--                            </v-flex>-->
            <!--                        </v-layout>-->
            <!--                    </v-card>-->
            <!--                </v-flex>-->
            <!--            </v-layout>-->
            <v-layout row mt-2>
                <v-flex wrap>
                    <v-card class="bg-gradient">
                        <v-layout row p-2>
                            <v-flex>
                                <v-card-title class="white--text summary-text">Actions</v-card-title>
                            </v-flex>
                            <v-spacer></v-spacer>
                            <v-flex xs6 sm4 md4 shrink class="text-xs-right">
                                <v-btn color="#6109A3" dark @click="completeDaySheet">finalize day sheet</v-btn>
                                <v-btn color="green" dark @click="printPDFReq">print</v-btn>
                            </v-flex>
                        </v-layout>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout row justify-center>
                <v-dialog v-model="daySheetSuccessDialog" max-width="400" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            {{daySheetSuccessTitle}}
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            {{ daySheetSuccessMessage }}
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-1" @click="daySheetSuccessDialog=false" flat="flat">OK</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
            <v-layout>
                <v-dialog v-model="accessEditDialog" max-width="400" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            {{accessEditTitle}}
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            <v-layout>
                                <v-flex>
                                    <v-text-field v-model="access" label="edit access"></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red darken-1" @click="accessEditCancel" flat="flat">Cancel</v-btn>
                            <v-btn color="green darken-1" @click="accessEditSuccess" flat="flat">OK</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
            <v-layout>
                <v-dialog v-model="cashOutEditDialog" max-width="400" persistent>
                    <v-card>
                        <v-card-title class="subheading">
                            {{cashOutEditTitle}}
                        </v-card-title>
                        <v-divider light></v-divider>
                        <v-card-text>
                            <v-layout>
                                <v-flex>
                                    <v-text-field v-model="cash_out" label="edit cash out"></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red darken-1" @click="cashOutEditCancel" flat="flat">Cancel</v-btn>
                            <v-btn color="green darken-1" @click="cashOutEditSuccess" flat="flat">OK</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
        </v-container>
        <v-container fluid id="content" v-show="isPrint">
            <v-layout>
                <v-flex md6>
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
                <table class="tbs">
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
<!--            <v-container fluid grid-list-lg>-->
<!--                <v-layout row wrap>-->
<!--                    &lt;!&ndash;suppress CommaExpressionJS &ndash;&gt;-->
<!--                    <v-flex lg6 v-for="position, index in items[0]" :data="position" :key="position.index"-->
<!--                            id="data_wrapper">-->
<!--                        <table class="mainData" width="100%">-->
<!--                            <tr class="tb-tr" v-for="item in position">-->
<!--                                <td class="tb-td">{{ item.id }}</td>-->
<!--                                <td class="tb-td">{{ item.name }}</td>-->
<!--                                <td class="tb-td">{{ item.amount }}</td>-->
<!--                            </tr>-->
<!--                        </table>-->
<!--                    </v-flex>-->
<!--                </v-layout>-->
<!--            </v-container>-->
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
                footerMenu: [
                    {title: 'Edit', text: 'Edit'},
                ],
                items: [],
                createDate: new Date(),
                isPrint: false,
                routes: [
                    {
                        id: -1,
                        name: "ALL"
                    }
                ],
                routesDef: {
                    id: -1,
                    name: "ALL"
                },
                date: new Date().toISOString().substr(0, 10),
                menu: false,
                modal: false,
                menu2: false,
                labelBfAmount: "BF AMOUNT",
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
                        align: 'left',
                        sortable: false,
                        value: 'payment'
                    },
                    {
                        text: 'Due Total Payments',
                        align: 'left',
                        sortable: false,
                        value: 'dueTotal'
                    }
                ],
                payments: [],
                bfAmount: 0.0,
                labelTotalCollection: "Total Collection",
                totalPayment: 0.0,
                income_details: {},
                expense_details: {},
                total_income: 0,
                bank_deposit: 0,
                day_cash: 0,
                cash_out: 0,
                balance: 0,
                cash_in_locker: 0,
                day_profit: 0,
                sup_loan_details: {},
                access: 0,
                income: 0,
                totalDuePayment: 0.0,
                disableFooter: false,
                route_id_req: -1,
                daySheetSuccessDialog: false,
                daySheetSuccessTitle: "Data Saved!",
                daySheetSuccessMessage: "Day sheet data saved successfully",
                accessEditDialog: false,
                accessEditTitle: "Edit access amount",
                cashOutEditDialog: false,
                cashOutEditTitle: "Edit cash out"
            }
        },
        created() {
            this.getData();
            this.getDataByDate(-1);
            this.disableFooterFunc();
        },
        methods: {
            disableFooterFunc() {
                this.disableFooter = true;
            },
            getData() {
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.get('/getInitialDataDaySheet').then((response) => {
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
                            this.populateInitialData(response.data.routes);
                        }

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
            populateInitialData(routes) {
                routes.forEach((route) => {
                    this.routes.push({id: route.id, name: route.name});
                });
            },
            getDataByDate(route_id) {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/daySheetDataByDate', {
                    date: this.date,
                    route_id: route_id
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
                            this.populateData(response.data);
                            console.log(response.data);
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
            populateData(data) {
                this.flushCollectionTable();
                this.setBfAmount(data.bf_amount);
                this.setRouteWiseCollection(data.route_col);
                this.setIncomeDetails(data.income_details);
                this.setExpenseDetails(data.expense_details);
                this.setTotalIncome(data.total_income);
                this.setBankDeposit(data.bank_deposit);
                this.setDayCash(data.day_cash);
                this.setDayProfit(data.day_profit);
                this.setSupLoanDetails();
            },
            flushCollectionTable() {
                this.payments.splice(0, this.payments.length);
            },
            setSupLoanDetails() {
                Object.assign(this.sup_loan_details, this.income_details.sup_loan_in);
            },
            setBfAmount(amount) {
                this.bfAmount = amount;
            },
            setRouteWiseCollection(cols) {
                cols.forEach((col) => {
                    this.payments.push({
                        value: false,
                        routeName: col.name,
                        payment: col.amount,
                        dueTotal: col.due_total
                    })
                })
            },
            setIncomeDetails(details) {
                Object.assign(this.income_details, details);
                console.log(this.income_details);
                this.setIncome(details);
            },
            setIncome(in_details) {
                this.income = in_details.income;
            },
            setExpenseDetails(details) {
                Object.assign(this.expense_details, details);
            },
            setTotalIncome(total_income) {
                this.total_income = total_income;
            },
            setBankDeposit(deposit) {
                this.bank_deposit = deposit;
            },
            setDayCash(cash) {
                this.day_cash = cash;
            },
            setCashOut(out) {
                this.cash_out = out;
            },
            setBalance(balance) {
                this.balance = balance;
            },
            setCashInLocker(cash) {
                this.cash_in_locker = cash;
            },
            setDayProfit(profit) {
                this.day_profit = profit;
            },
            changeRoute(id) {
                this.route_id_req = id;
                this.getDataByDate(id);
            },
            accessEditCancel() {
                this.accessEditDialog = false;
                this.access = 0;
            },
            accessEditSuccess() {
                this.accessEditDialog = false;
                this.changeIncomeValue();
            },
            changeIncomeValue() {
                this.income = parseFloat(this.bfAmount) +
                    parseFloat(this.income_details.total_col) +
                    parseFloat(this.income_details.ex_income) +
                    parseFloat(this.sup_loan_details.sup_loan_cash) +
                    parseFloat(this.sup_loan_details.sup_loan_che) +
                    parseFloat(this.income_details.emp_loan_pay) +
                    parseFloat(this.access);
            },
            cashOutEditCancel() {
                this.cashOutEditDialog = false;
                this.cash_out = 0;
            },
            cashOutEditSuccess() {
                this.cashOutEditDialog = false;
            },
            completeDaySheet() {
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/daySheetSaveReq', {
                    date: this.date,
                    route_id: this.route_id_req,
                    bfAmount: this.bfAmount,
                    totalCol: this.income_details.total_col,
                    exIncome: this.income_details.ex_income,
                    supLoanCash: this.sup_loan_details.sup_loan_cash,
                    supLoanChe: this.sup_loan_details.sup_loan_che,
                    empLoanPay: this.income_details.emp_loan_pay,
                    dayAccess: this.access,
                    dayIncome: this.income,
                    loanCash: this.expense_details.loan_total_cash,
                    loanChe: this.expense_details.loan_total_che,
                    daySalaryPay: this.expense_details.salary_payment,
                    daySupPayCash: this.expense_details.total_supplier_payment_cash,
                    daySupPayChe: this.expense_details.total_supplier_payment_cheque,
                    empLoan: this.expense_details.total_emp_loan,
                    extraEx: this.expense_details.extra_expenses,
                    expenses: this.expense_details.expenses,
                    dayTotIncome: this.total_income,
                    dayBDeposit: this.bank_deposit,
                    dayCash: this.day_cash,
                    dayCashOut: this.cash_out,
                    dayBalance: this.balance,
                    dayCashInLocker: this.cash_in_locker,
                    dayProfit: this.day_profit
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
                            this.daySheetSuccessDialog = true;
                            this.disableFooter = true;
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
            printPDFReq() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/daySheetPrintDetails', {
                    date: this.date,
                    route_id: this.route_id_req,
                    bfAmount: this.bfAmount,
                    totalCol: this.income_details.total_col,
                    dayAccess: this.access,
                    loanCash: this.expense_details.loan_total_cash,
                    loanChe: this.expense_details.loan_total_che,
                    extraEx: this.expense_details.extra_expenses,
                    dayBalance: this.balance,
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
                            // this.downloadFile(response.data)
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
            downloadFile(response) {
                var blob = new Blob([response], {type: 'application/pdf'})
                var url = URL.createObjectURL(blob);
                location.assign(url);
            },
            enableFooter() {
                this.disableFooter = false;
            },
            createPdf() {
                // noinspection JSUnresolvedFunction
                html2canvas($('#content')[0]).then(canvas => {
                    let img = canvas.toDataURL("image/png");
                    let doc = new JsPDF('p', 'mm', "a4");
                    doc.setProperties({
                        title: "DaySheet"+this.date
                    });
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
                    // doc.output('dataurlnewwindow');
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
            },
            getIncome: function () {
                if (Object.keys(this.income_details).length === 0) {
                    return 0;
                }

                this.income = this.bfAmount + this.income_details.total_col + this.income_details.ex_income + this.sup_loan_details.sup_loan_cash
                    + this.sup_loan_details.sup_loan_che + this.income_details.emp_loan_pay + this.access;

                return this.income;
            },
            getTotalIncome: function () {
                this.total_income = this.income - parseFloat(this.expense_details.expenses);

                return this.total_income;
            },
            getDayCash: function () {
                this.day_cash = this.total_income - this.bank_deposit;

                return this.day_cash;
            },
            getBalance: function () {
                this.balance = this.day_cash - this.cash_out;

                return this.balance;
            },
            getTotalCollection: function () {
                let t = 0;
                this.payments.forEach((item) => {
                    t += item.payment;
                });

                this.totalPayment = t;
                return t;
            },
            getTotalDueCollection: function () {
                let c = 0;
                this.payments.forEach((item) => {
                    c += item.dueTotal;
                });
                this.totalDuePayment = c;
                return c;
            },
            getCashInLocker: function () {
                this.cash_in_locker = this.balance;

                return this.cash_in_locker;
            }
        }
    }
</script>

<style scoped>
    #content
    {
        margin: 1px;
    }
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

    .resize_field label[for] {
        height: 20px;
        font-size: 10pt;
    }

    .text-simple {
        font-size: 24px;
        padding: 16px;
        font-weight: bold;
        color: #FFFFFF;
        text-align: center;
    }

    .summary-text {
        font-size: 18px !important;
        padding: 16px;
        font-weight: bold;
        color: #FFFFFF;
    }

    .summary-sub-text {
        font-size: 16px !important;
        padding: 16px;
        font-weight: bold;
        color: #ffffff;
    }

    .bg-special {
        background-repeat: inherit;
        background-position: inherit;
        background-image: inherit;
        background-color: inherit;
        background-attachment: inherit;
    }

    .bg-gradient {
        background: rgb(97, 9, 163);
        background: linear-gradient(90deg, rgba(97, 9, 163, 0.9836309523809523) 0%, rgba(123, 46, 200, 1) 21%, rgba(122, 125, 183, 1) 100%);
    }
</style>
