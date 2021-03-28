<template>
    <date-range-picker
        :startDate="startDate"
        :endDate="endDate"
        @update="console.log(value)"
        :locale-data="locale"
        :opens="opens"
    >
    <!--Optional scope for the input displaying the dates -->
    <div slot="input" slot-scope="picker">...</div>
</date-range-picker>
</template>

<script>
export default {
    components: { DateRangePicker },
    data() {
        return {
            startDate: '2017-09-05',
            endDate: '2017-09-15',
            opens: "center",//which way the picker opens, default "center", can be "left"/"right"
            locale: {
                direction: 'ltr', //direction of text
                format: 'DD-MM-YYYY', //fomart of the dates displayed
                separator: ' - ', //separator between the two ranges
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
                weekLabel: 'W',
                customRangeLabel: 'Custom Range',
                daysOfWeek: moment.weekdaysMin(), //array of days - see moment documenations for details
                monthNames: moment.monthsShort(), //array of month names - see moment documenations for details
                firstDay: 1 //ISO first day of week - see moment documenations for details
                showWeekNumbers: true //show week numbers on each row of the calendar
            },
            ranges: { //default value for ranges object (if you set this to false ranges will no be rendered)
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'This month': [moment().startOf('month'), moment().endOf('month')],
                'This year': [moment().startOf('year'), moment().endOf('year')],
                'Last week': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
                'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            }
        }
    }
}
</script>


//you need to import the CSS manually (in case you want to override it)
import 'vue2-daterange-picker/dist/lib/vue-daterange-picker.min.css'