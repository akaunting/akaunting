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
            form: new Form('reconciliation'),
            bulk_action: new BulkAction('reconciliations'),
            reconcile: true,
            difference: null,
            totals: {
                closing_balance: 0,
                cleared_amount: 0,
                difference: 0,
            },
        }
    },

    mounted() {
        this.totals.closing_balance = parseFloat(document.getElementById('closing_balance').value);
    },

    methods:{
        onReconcilition() {
            let form = document.getElementById('form-create-reconciliation');

            let path = form.action +'?started_at=' + this.form.started_at + '&ended_at=' + this.form.ended_at + '&closing_balance=' + this.form.closing_balance + '&account_id=' + this.form.account_id;

            window.location.href = path;
        },

        onCalculate() {
            this.reconcile = true;
            this.difference = null;

            let transactions = this.form.transactions;

            let cleared_amount = 0;
            let difference = 0;
            let income_total = 0;
            let expense_total = 0;

            if (transactions) {
                // get all transactions.
                Object.keys(transactions).forEach(function(transaction) {
                    if (!transactions[transaction]) {
                        return;
                    }

                    let type = transaction.split('_');

                    if (type[0] == 'income') {
                        income_total += parseFloat(document.getElementById('transaction-' + type[1] + '-' + type[0]).value);
                    } else {
                        expense_total += parseFloat(document.getElementById('transaction-' + type[1] + '-' + type[0]).value);
                    }
                });

                cleared_amount = parseFloat(this.form.opening_balance) + parseFloat(income_total - expense_total);
            }

            difference = parseFloat(this.form.closing_balance) - cleared_amount;

            if (difference != 0) {
                this.difference = 'table-danger';
                this.reconcile = true;
            } else {
                this.difference = 'table-success';
                this.reconcile = false;
            }

            this.totals.cleared_amount = cleared_amount;
            this.totals.difference = difference;
        },

        onReconcileSubmit() {
            this.form.reconcile = 1;

            this.form.submit();
        }
    },
});
