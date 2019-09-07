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
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
            <div id="calendar">
                <calendar-view
                        :events="events"
                        :show-date="showDate"
                        @click-date="setClickDate"
                        slot="day"
                        class="theme-default holiday-us-traditional holiday-us-official">
                    <calendar-view-header
                            slot="header"
                            slot-scope="t"
                            :header-props="t.headerProps"
                            @input="setShowDate"/>
                </calendar-view>
                <v-layout row justify-center>
                    <v-dialog v-model="dialog" max-width="290">
                        <v-card>
                            <v-card-title class="headline">
                                Confirm Remove
                            </v-card-title>
                            <v-card-text>
                                By confirming this date record will remove from the system
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="green darken-1" @click="setBackId" flat="flat">Not Confirm</v-btn>
                                <v-btn color="red" @click="deleteDate" flat="flat">Confirm</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-layout>
            </div>
        </v-container>
    </v-app>
</template>

<script>
    import CalendarView from "vue-simple-calendar/src/components/CalendarView";
    import CalendarViewHeader from "vue-simple-calendar/src/components/CalendarViewHeader";
    import moment from 'moment';

    require("vue-simple-calendar/static/css/default.css");
    require("vue-simple-calendar/static/css/holidays-us.css");
    export default {
        data() {
            return {
                compName:"Calendar",
                showDate: new Date(),
                clickDate: new Date(),
                newEventTitle: "",
                newEventStartDate: "",
                newEventEndDate: "",
                events: [],
                dates: [],
                dialog: false,
                deleteId: 0,
                deleteIndex: 0,
                pureDate: new Date()
            }
        }, components: {
            CalendarView,
            CalendarViewHeader,
        },
        methods: {
            setShowDate(d) {
                this.showDate = d;
            },
            setClickDate(d) {
                this.clickDate = moment(d).utcOffset("+05:30").format('YYYY-MM-DD').toString();
                this.pureDate = d;
                if (this.checkDateIsExists(this.clickDate)) {
                    this.sendDateToDB();
                } else {
                    this.dialog = true;
                    this.setDeleteId(this.clickDate);
                }

            },
            checkDateIsExists(date) {
                let exists = this.events.some((item) => {
                    return date === item.startDate.toString();
                });

                return !exists;
            },
            setDeleteId(d) {
                this.events.forEach((item) => {
                    if ( d === item.startDate.toString()) {
                        this.deleteId = item.id;
                    }
                });
            },
            setBackId() {
                this.deleteId = 0;
                this.dialog =false;
            },
            setDeleteIndex(id) {
                const item = this.events.find((item) => {
                    return (id === item.id);
                });
                this.deleteIndex = this.events.indexOf(item);
            },
            deleteDate() {
                this.dialog = false;
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/deleteDate', {
                    id: this.deleteId
                }).then((response) => {
                    if (response.status === 200) {
                        this.$Progress.finish();
                        if (response.data.error) {
                            this.$notify({
                                group: 'auth',
                                title: 'Error',
                                type: 'error',
                                text: response.data.message,
                                fontsize: '20px'
                            });
                            this.$Progress.fail();
                        } else {
                            this.$notify({
                                group: 'auth',
                                title: 'Success',
                                type: 'success',
                                text: response.data.message,
                                fontsize: '20px'
                            });
                            this.setDeleteIndex(this.deleteId);
                            console.log(this.deleteIndex);
                            this.removeEventForDate(this.deleteIndex);
                            this.setBackId();
                        }
                    }
                }).catch((error) => {
                    this.$notify({
                        group: 'auth',
                        title: 'Error',
                        type: 'error',
                        text: "error occurred!",
                        fontsize: '20px'
                    });
                    this.$Progress.fail();
                });
            },
            removeEventForDate(index) {
                this.events.splice(index,1);
                this.dates.splice(index,1);
            },
            sendDateToDB() {
                this.$Progress.start();
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.post('/createCalendar', {
                    date: this.clickDate,
                    holiday: true
                }).then((response) => {
                    if (response.status === 200) {
                        this.$Progress.finish();
                        if (response.data.error) {
                            this.$notify({
                                group: 'auth',
                                title: 'Error',
                                type: 'error',
                                text: response.data.message,
                                fontsize: '20px'
                            });
                            this.$Progress.fail();
                        } else {
                            this.$notify({
                                group: 'auth',
                                title: 'Success',
                                type: 'success',
                                text: "Date recorded successfully: " + response.data.calendar.date,
                                fontsize: '20px'
                            });
                            this.addEventForDate(response.data.calendar.date, response.data.calendar.id);
                        }
                    }
                }).catch((error) => {
                    this.$notify({
                        group: 'auth',
                        title: 'Error',
                        type: 'error',
                        text: "error occurred!",
                        fontsize: '20px'
                    });
                    this.$Progress.fail();
                });
            },
            addEventForDate(date, id) {
                this.events.push({
                    id: id,
                    startDate: date,
                    title: "Holiday"
                })
            },
            getData() {
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                axios.get('/getCalendarDates').then((response) => {
                    if (response.status === 200) {
                        if (!response.data.error) {
                            this.dates = response.data.dates;
                            response.data.dates.forEach((item) => {
                                this.addEventForDate(item.date, item.id);
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
                    this.$notify({
                        group: 'auth',
                        title: 'Error',
                        type: 'error',
                        text: "error occurred!",
                        fontsize: '20px',
                        position: 'top center'
                    });
                });
            }
        },
        beforeMount() {
            this.getData();
        }
    }
</script>

<style scoped>
    #calendar {
        font-family: 'Avenir', Helvetica, Arial, sans-serif;
        color: #2c3e50;
        height: 67vh;
        width: 90vw;
        margin-left: auto;
        margin-right: auto;
        margin-top: 20px;
    }
</style>
