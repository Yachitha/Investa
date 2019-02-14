
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


window.Vue = require('vue');
import Notification from 'vue-notification';
import VueProgressBar from 'vue-progressbar';
import VueRouter from 'vue-router';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import 'material-design-icons-iconfont/dist/material-design-icons.css';
import DaySheet from './components/Reports/DaySheetComponent';
import Calendar from './components/calendar/CalendarComponent';
import PaySheet from './components/Reports/PaySheetComponent';
import CustomerDashboard from './components/Customer/CustomerDashboard';
import CustomerList from './components/Customer/CustomerList';
import LoanDashboard from './components/Loans/CustomerLoanComponent';
import CashBook from './components/cashBook/CashBookComponent';
import BankBook from './components/bankBook/BankBookComponent';

Vue.use(Notification);
Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/daysheet',
            name: 'DaySheet',
            component: DaySheet
        },
        {
            path: '/calendar',
            name: 'Calendar',
            component : Calendar
        },
        {
            path: '/paysheet',
            name: 'PaySheet',
            component: PaySheet
        },
        {
            path: '/customerdashboard',
            name: 'CustomerDashboard',
            component: CustomerDashboard
        },
        {
            path: '/customerlist',
            name: 'CustomerList',
            component: CustomerList
        },
        {
            path: '/loandashboard',
            name: 'LoanDashboard',
            component: LoanDashboard
        },
        {
            path: '/cashbook',
            name: 'CashBook',
            component: CashBook
        },
        {
            path: '/bankBook',
            name: 'BankBook',
            component: BankBook
        }
    ]
});
const options = {
    color: '#16e9ff',
    failedColor: '#ff6761',
    thickness: '4px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
};

Vue.use(VueProgressBar, options);
Vue.use(Vuetify, {
    theme: {
        "primary": "#00bcd4",
        "secondary": "#424242",
        "accent": "#82B1FF",
        "error": "#FF5252",
        "info": "#2196F3",
        "success": "#4CAF50",
        "warning": "#FFC107"
    }
});
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('home-component', require('./components/HomeComponent.vue'));
Vue.component('layout',require('./components/layout/Layout.vue'));
Vue.component('calendar',require('./components/calendar/CalendarComponent.vue'));
Vue.component('pdf-generator', require('./components/PDF/PDFGenerator'));
Vue.component('day-sheet-component', require('./components/Reports/DaySheetComponent'));
Vue.component('bar-chart',require('./components/Charts/CustomerLineChartComponent'));
const app = new Vue({
    el: '#app',
    router,
});
