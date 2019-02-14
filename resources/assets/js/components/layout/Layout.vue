<template>
    <v-app id="inspire">
        <v-toolbar color="teal" dark fixed app clipped-right>
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <v-toolbar-title>SPD</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-menu bottom>
                <v-btn slot="activator" dark icon>
                    <v-icon>settings</v-icon>
                </v-btn>
                <v-list>
                    <v-list-tile v-for="(item, i) in items" :key="i" @click="">
                        <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
        </v-toolbar>
        <v-navigation-drawer v-model="drawer" fixed app>
            <v-list dense>
                <v-list-group>
                    <v-list-tile slot="activator">
                        <v-list-tile-action>
                            <v-icon>account_box</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ labelCustomers }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile>
                        <v-list-tile-content>
                            <!--<v-list-tile-title>{{ labelCustomerDashboard }}</v-list-tile-title>-->
                            <router-link :to="{ name: 'CustomerDashboard' }">{{ labelCustomerDashboard }}</router-link>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile>
                        <v-list-tile-content>
                            <router-link :to="{ name: 'CustomerList' }">{{ labelCustomersList }}</router-link>
                            <!--<v-list-tile-title>{{ labelCustomersList }}</v-list-tile-title>-->
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list-group>
                <v-list-group>
                    <v-list-tile slot="activator">
                        <v-list-tile-action>
                            <v-icon>local_offer</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ labelLoans }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile>
                        <v-list-tile-content>
                            <router-link :to="{ name: 'LoanDashboard' }">{{ labelLoanDashboard }}</router-link>
                            <!--<v-list-tile-title>{{ labelLoanDashboard }}</v-list-tile-title>-->
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ labelLoanList }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list-group>
                <v-list-group>
                    <v-list-tile slot="activator">
                        <v-list-tile-action>
                            <v-icon>book</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ labelReport }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile>
                        <v-list-tile-content>
                            <router-link :to="{ name: 'DaySheet' }">Day Sheet</router-link>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile>
                        <v-list-tile-content>
                            <router-link :to="{ name: 'PaySheet' }">Pay Sheet</router-link>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list-group>
                <v-divider light></v-divider>
                <v-list-tile>
                    <v-list-tile-action>
                        <v-icon>book</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <!--<v-list-tile-title>{{ labelCashBook }}</v-list-tile-title>-->
                        <router-link :to="{ name: 'CashBook' }">Cash Book</router-link>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile>
                    <v-list-tile-action>
                        <v-icon>book</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <!--<v-list-tile-title>{{ labelBankBook }}</v-list-tile-title>-->
                        <router-link :to="{ name: 'BankBook' }">Bank Book</router-link>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile>
                    <v-list-tile-action>
                        <v-icon>trending_up</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>{{ labelIncome }}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile>
                    <v-list-tile-action>
                        <v-icon>trending_down</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>{{ labelExpense }}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile>
                    <v-list-tile-action>
                        <v-icon>today</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>
                            <router-link :to="{ name: 'Calendar' }">Calendar</router-link>
                        </v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>
        <v-navigation-drawer v-model="left" temporary fixed></v-navigation-drawer>
        <v-content>
            <router-view></router-view>
            <vue-progress-bar></vue-progress-bar>
        </v-content>
        <v-footer color="blue-grey" class="white--text" app>
            <span>SPD</span>
        </v-footer>
    </v-app>
</template>

<script>
    import VListTile from "vuetify/lib/components/VList/VListTile";

    export default {
        components: {VListTile},
        data: () => ({
            drawer: false,
            drawerRight: true,
            right: null,
            left: null,
            items: [
                {title: 'Profile', text: 'Notes'},
                {title: 'Update Details', text: 'Create new label'},
                {title: 'Getting Start', text: 'Archive'},
            ],
            labelMainDashboard: "Main Dashboard",
            labelCustomers: "Customers",
            labelCustomersList: "Customer List",
            labelCustomerDashboard:"Customer Dashboard",
            labelLoans: "Loans",
            labelLoanDashboard: "Loan Dashboard",
            labelLoanList: "Loan List",
            labelCashBook: "Cash Book",
            labelBankBook: "Bank Book",
            labelReport: "Reports",
            labelIncome: "Income",
            labelExpense: "Expense"
        }),
        props: {
            source: String
        }
    }
</script>
<style scoped>
    a {
        text-decoration: none;
        color: rgba(15, 15, 15, 0.64);
    }
</style>
