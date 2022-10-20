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
            reconcile: false,
            difference: null,
            totals: {
                closing_balance: 0,
                cleared_amount: 0,
                difference: 0,
            },
            min_due_date: false,
        }
    },

    mounted() {
       if (document.getElementById('closing_balance') != null) {
           this.totals.closing_balance = parseFloat(document.getElementById('closing_balance').value);
       }

       if (this.form._method == 'PATCH') {
           this.onCalculate();
       }
    },

    methods: {
        setDueMinDate(date) {
            this.min_due_date = date;
        },

        onReconcilition() {
            let form = document.getElementById('form-create-reconciliation');

            let path = form.action + '?started_at=' + this.form.started_at + '&ended_at=' + this.form.ended_at + '&closing_balance=' + this.form.closing_balance + '&account_id=' + this.form.account_id;

            window.location.href = path;
        },

        onCalculate() {
            this.reconcile = false;
            this.difference = null;

            let transactions = this.form.transactions;

            let cleared_amount = 0;
            let closing_balance = parseFloat(this.form.closing_balance);
            let difference = 0;
            let income_total = 0;
            let expense_total = 0;

            this.totals.closing_balance = closing_balance;

            if (transactions) {
                // get all transactions.
                Object.keys(transactions).forEach(function(transaction) {
                    if (! transactions[transaction]) {
                        return;
                    }

                    let type = transaction.split('_');

                    if (type[0] == 'income') {
                        income_total += parseFloat(document.getElementById('transaction-' + type[1] + '-' + type[0]).value);
                    } else {
                        expense_total += parseFloat(document.getElementById('transaction-' + type[1] + '-' + type[0]).value);
                    }
                });

                let transaction_total = income_total - expense_total;

                cleared_amount = parseFloat(this.form.opening_balance) + transaction_total;
            }

            if (cleared_amount > 0) {
                difference = (parseFloat(this.form.closing_balance) - parseFloat(cleared_amount)).toFixed(this.currency.precision);
            } else {
                difference = (parseFloat(this.form.closing_balance) + parseFloat(cleared_amount)).toFixed(this.currency.precision);
            }

            if (difference != 0) {
                this.difference = 'bg-orange-300';
                this.reconcile = false;
            } else {
                this.difference = 'bg-green-100';
                this.reconcile = true;
            }

            this.totals.cleared_amount = parseFloat(cleared_amount);
            this.totals.difference = difference;
        },

        onReconcileSubmit() {
            this.form.reconcile = 1;

            this.form.submit();
        },
    }
});
