/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../bootstrap');

import Vue from 'vue';

import Global from './../../mixins/global';

import AkauntingDashboard from './../../components/AkauntingDashboard';
import AkauntingWidget from './../../components/AkauntingWidget';

import {DatePicker} from 'element-ui';

Vue.use(DatePicker);

const dashboard = new Vue({
    el: '#main-body',

    components: {
        [DatePicker.name]: DatePicker,
        AkauntingDashboard,
        AkauntingWidget
    },

    mixins: [
        Global
    ],

    data: function () {
        return {
            dashboard_modal: false,
            dashboard: {
                name: '',
                enabled: 1,
                type: 'create',
                dashboard_id: 0
            },
            widget_modal: false,
            widgets: {},
            widget: {
                name: '',
                type: '',
                width: '',
                action: 'create',
                sort: 0,
                widget_id: 0
            },
            pickerOptions: {
                shortcuts: [{
                text: 'Last week',
                onClick(picker) {
                    const end = new Date();
                    const start = new Date();
                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                    picker.$emit('pick', [start, end]);
                }
                }, {
                text: 'Last month',
                onClick(picker) {
                    const end = new Date();
                    const start = new Date();
                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                    picker.$emit('pick', [start, end]);
                }
                }, {
                text: 'Last 3 months',
                onClick(picker) {
                    const end = new Date();
                    const start = new Date();
                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
                    picker.$emit('pick', [start, end]);
                }
                }]
            },
            value2: ''
        };
    },

    mounted() {
        this.getWidgets();
    },

    methods:{
        // Create Dashboard form open
        onCreateDashboard() {
            this.dashboard_modal = true;
            this.dashboard.name = '';
            this.dashboard.enabled = 1;
            this.dashboard.type = 'create';
            this.dashboard.dashboard_id = 0;
        },

        // Edit Dashboard information
        onEditDashboard(dashboard_id) {
            var self = this;

            axios.get(url + '/common/dashboards/' + dashboard_id + '/edit')
                .then(function (response) {
                    self.dashboard.name = response.data.name;
                    self.dashboard.enabled = response.data.enabled;
                    self.dashboard.type = 'edit';
                    self.dashboard.dashboard_id = dashboard_id;

                    self.dashboard_modal = true;
                })
                .catch(function (error) {
                    self.dashboard_modal = false;
                });
        },

        // Get All Widgets
        getWidgets() {
            var self = this;

            axios.get(url + '/common/widgets')
            .then(function (response) {
                self.widgets = response.data;
            })
            .catch(function (error) {
            });
        },

        // Add new widget on dashboard
        onCreateWidget() {
            this.widget_modal = true;
        },

        // Edit Dashboard selected widget setting.
        onEditWidget(widget_id) {
            var self = this;

            axios.get(url + '/common/widgets/' + widget_id + '/edit')
            .then(function (response) {
                self.widget.name = response.data.name;
                self.widget.type = response.data.widget_id;
                self.widget.width = response.data.settings.width;
                self.widget.action = 'edit';
                self.widget.sort = response.data.sort;
                self.widget.widget_id = widget_id;

                self.widget_modal = true;
            })
            .catch(function (error) {
                self.widget_modal = false;
            });
        },

        onCancel() {
            this.dashboard_modal = false;

            this.dashboard.name = '';
            this.dashboard.enabled = 1;
            this.dashboard.type = 'create';
            this.dashboard.dashboard_id = 0;

            this.widget_modal = false;

            this.widget.name = '';
            this.widget.type = '';
            this.widget.width = '';
            this.widget.action = 'create';
            this.widget.sort = 0;
            this.widget.widget_id = 0;
        },
    }
});
