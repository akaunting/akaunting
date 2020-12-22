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
        Global
    ],

    data: function () {
        return {
            form: new Form('report'),
            bulk_action: new BulkAction('reports'),
            report_fields: '',
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
                let form = this.form;
                let html = response.data.html;

                this.report_fields = Vue.component('add-new-component', (resolve, reject) => {
                    resolve({
                        template : '<div id="report-fields" class="row col-md-12">' + html + '</div>',

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
        }
    }
});
