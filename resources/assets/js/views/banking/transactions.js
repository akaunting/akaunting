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
    el: '#main-body',

    mixins: [
        Global
    ],

    data: function () {
        return {
            form: new Form('transaction'),
            bulk_action: new BulkAction('transactions'),
            dynamic_taxes: [],
            tax_summary: false,
            tax_summary_total: 0,
            tax_summary_html: '',
        }
    },

    created() {
        if (typeof transaction_taxes !== 'undefined' && transaction_taxes) {
            this.dynamic_taxes = transaction_taxes;
        }
    },

    methods: {
        //
        onChangeTax(tax_id) {
            if (tax_id == undefined || ! tax_id.length) {
                this.tax_summary = false;

                return;
            }

            let tax_amount = 0;
            let tax_total = 0;
            let taxes = this.dynamic_taxes;

            let inclusives = [];
            let fixed = [];
            let normal = [];
            let withholding = [];
            let compounds = [];

            tax_id.forEach(function(item_tax, item_tax_index) {
                for (var index_taxes = 0; index_taxes < taxes.length; index_taxes++) {
                    let tax = taxes[index_taxes];

                    if (item_tax != tax.id) {
                        continue;
                    }

                    switch (tax.type) {
                        case 'inclusive':
                            inclusives.push({
                                tax_index: item_tax_index,
                                tax_id: tax.id,
                                tax_name: tax.title,
                                tax_rate: tax.rate
                            });
                            break;
                        case 'compound':
                            compounds.push({
                                tax_index: item_tax_index,
                                tax_id: tax.id,
                                tax_name: tax.title,
                                tax_rate: tax.rate
                            });
                            break;
                        case 'fixed':
                            fixed.push({
                                tax_index: item_tax_index,
                                tax_id: tax.id,
                                tax_name: tax.title,
                                tax_rate: tax.rate
                            });
                            break;
                        case 'withholding':
                            withholding.push({
                                tax_index: item_tax_index,
                                tax_id: tax.id,
                                tax_name: tax.title,
                                tax_rate: tax.rate
                            });
                            break;
                        default:
                            normal.push({
                                tax_index: item_tax_index,
                                tax_id: tax.id,
                                tax_name: tax.title,
                                tax_rate: tax.rate
                            });
                            break;
                    }
                }
            });

            if (inclusives.length) {
                inclusives.forEach(function(inclusive) {
                    tax_amount = this.form.amount - (this.form.amount / (1 + inclusive.tax_rate / 100));

                    tax_total += tax_amount;
                }, this);
            }

            if (fixed.length) {
                fixed.forEach(function(fix) {
                    tax_amount = fix.tax_rate * parseFloat(1);

                    tax_total += tax_amount;
                }, this);
            }

            if (normal.length) {
                normal.forEach(function(norm) {
                    tax_amount = this.form.amount * (norm.tax_rate / 100);

                    tax_total += tax_amount;
                }, this);
            }

            if (withholding.length) {
                withholding.forEach(function(withhold) {
                    tax_amount = -(this.form.amount * (withhold.tax_rate / 100));

                    tax_total += tax_amount;
                }, this);
            }

            if (compounds.length) {
                compounds.forEach(function(compound) {
                    tax_amount = (this.form.amount / 100) * compound.tax_rate;

                    tax_total += tax_amount;
                }, this);
            }

            if (tax_total <= 0) {
                return;
            }

            this.tax_summary = true;
            this.tax_summary_total = tax_total.toFixed(this.currency.precision ?? 2);
        },
    },
});
