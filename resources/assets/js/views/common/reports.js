/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../bootstrap');

import Vue from 'vue';

import DashboardPlugin from './../../plugins/dashboard-plugin';

import Global from './../../mixins/global';

import Form from './../../plugins/form';
import BulkAction from './../../plugins/bulk-action';

// plugin setup
Vue.use(DashboardPlugin);

const app = new Vue({
    el: '#app',

    mixins: [
        Global,
    ],

    data: function () {
        return {
            form: new Form('report'),
            bulk_action: new BulkAction('reports'),
            report_fields: '',
            showPreferences: false,
        }
    },

    methods: {
        onChangeClass(class_name) {
            axios.get(url + '/common/reports/fields', {
                params: {
                    class: class_name
                }
            })
            .then(response => {
                if (class_name) {
                    this.showPreferences = true;
                } else {
                    this.showPreferences = false;
                }

                let form = this.form;
                let html = response.data.html;

                this.report_fields = Vue.component('add-new-component', (resolve, reject) => {
                    resolve({
                        template : '<div id="report-fields" class="grid sm:grid-cols-6 sm:col-span-6  gap-x-8 gap-y-6 my-3.5">' + html + '</div>',
                        mixins: [
                            Global
                        ],

                        created: function() {
                            this.form = form;
                        },

                        data: function () {
                            return {
                                form: {},
                            }
                        },

                        watch: {
                            form: function (form) {
                                this.$emit("change", form);
                            }
                        },
                    })
                });
            })
            .catch(error => {
            });
        },

        onChangeReportFields(event) {
            this.form = event;
        },
    }
});
