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
import Error from './../../plugins/error';
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
            form: new Form('document'),
            bulk_action: new BulkAction('documents'),
            totals: {
                sub: 0,
                item_discount: '',
                discount: '',
                discount_text: false,
                taxes: [],
                total: 0
            },
            transaction: [],
            edit: {
                status: false,
                currency: false,
                items: 0,
            },
            colspan: 6,
            discount: false,
            tax: false,
            discounts: [],
            tax_id: [],

            items: [],
            taxes: [],
            page_loaded: false,
            currencies: [],
        }
    },

    mounted() {
        if ((document.getElementById('items') != null) && (document.getElementById('items').rows)) {
            this.colspan = document.getElementById("items").rows[0].cells.length - 1;
        }
    },

    methods: {
        onCalculateTotal() {
            let global_discount = parseFloat(this.form.discount);
            let discount_total = 0;
            let line_item_discount_total = 0;
            let taxes = document_taxes;
            let sub_total = 0;
            let totals_taxes = [];
            let grand_total = 0;
            let inclusive_tax_total = 0;

            // items calculate
            this.items.forEach(function(item, index) {
                let item_discount = 0;

                item.total = item.price * item.quantity;
                item.grand_total = item.price * item.quantity;

                // item discount calculate.
                let line_discount_amount = 0;

                if (item.discount) {
                    line_discount_amount = item.total * (item.discount / 100);
                    item.discount_amount = line_discount_amount

                    item_discounted_total = item.total -= line_discount_amount;
                    item_discount = item.discount;
                }

                let item_discounted_total = item.total;

                if (global_discount) {
                    item_discounted_total = item.total - (item.total * (global_discount / 100));

                    item_discount = global_discount;
                }

                // item tax calculate.
                if (item.tax_ids) {
                    let inclusives = [];
                    let compounds = [];

                    item.tax_ids.forEach(function(item_tax, item_tax_index) {
                        for (var index_taxes = 0; index_taxes < taxes.length; index_taxes++) {
                            let tax = taxes[index_taxes];

                            if (item_tax.id != tax.id) {
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
                                    item_tax.price = tax.rate * item.quantity;

                                    totals_taxes = this.calculateTotalsTax(totals_taxes, tax.id, tax.title, item_tax.price);

                                    item.grand_total += item_tax.price;
                                    break;
                                case 'withholding':
                                    item_tax.price = 0 - item.total * (tax.rate / 100);

                                    totals_taxes = this.calculateTotalsTax(totals_taxes, tax.id, tax.title, item_tax.price);

                                    item.grand_total += item_tax.price;
                                    break;
                                default:
                                    item_tax.price = item.total * (tax.rate / 100);

                                    totals_taxes = this.calculateTotalsTax(totals_taxes, tax.id, tax.title, item_tax.price);

                                    item.grand_total += item_tax.price;
                                    break;
                            }
                        }
                    }, this);

                    if (inclusives.length) {
                        let inclusive_total = 0;

                        inclusives.forEach(function(inclusive) {
                            inclusive_total += inclusive.tax_rate;

                            // tax price
                            item.tax_ids[inclusive.tax_index].price = item.grand_total - (item.grand_total / (1 + inclusive.tax_rate / 100));

                            totals_taxes = this.calculateTotalsTax(totals_taxes, inclusive.tax_id, inclusive.tax_name, item.tax_ids[inclusive.tax_index].price);
                        }, this);

                        let item_base_rate = parseFloat(item.grand_total / (1 + inclusive_total / 100));
                        //item.grand_total = item.grand_total + item_base_rate;

                        item.total = item_base_rate;

                        inclusive_tax_total += parseFloat(item.grand_total - item.total);
                    }

                    if (compounds.length) {
                        let price = 0;

                        compounds.forEach(function(compound) {
                            price = (item.grand_total / 100) * compound.tax_rate;

                            item.tax_ids[compound.tax_index].price = price;

                            totals_taxes = this.calculateTotalsTax(totals_taxes, compound.tax_id, compound.tax_name, price);
                        }, this);

                        item.grand_total += price;
                    }
                }

                // set item total
                if (item.discount) {
                    item.grand_total = item_discounted_total;
                }

                // calculate sub, tax, discount all items.
                line_item_discount_total += line_discount_amount;
                sub_total += item.total;
                grand_total += item.grand_total;

                this.form.items[index].name = item.name;
                this.form.items[index].description = item.description;
                this.form.items[index].quantity = item.quantity;
                this.form.items[index].price = item.price;
                this.form.items[index].discount = item.discount;
                this.form.items[index].total = item.total;
            }, this);

            // Apply discount to total
            if (global_discount) {
                discount_total = parseFloat(sub_total + inclusive_tax_total) * (global_discount / 100);

                this.totals.discount = discount_total;

                grand_total -= discount_total;
            }

            this.totals.item_discount = line_item_discount_total;
            this.totals.sub = sub_total;
            this.totals.taxes = totals_taxes;
            this.totals.total = grand_total;

            this.form.items.forEach(function(form_item, form_index) {
                let item = this.items[form_index];
                
                for (const [key, value] of Object.entries(item)) {
                    if (key == 'add_tax' || key == 'tax_ids' || key == 'add_discount') {
                        continue
                    }

                    if (form_item[key] === undefined) {
                        form_item[key] = value
                    }
                }
            }, this);
        },

        calculateTotalsTax(totals_taxes, id, name, price) {
            let total_tax_index = totals_taxes.findIndex(total_tax => {
                if (total_tax.id === id) {
                  return true;
                }
            }, this);

            if (total_tax_index === -1) {
                totals_taxes.push({
                    id: id,
                    name: name,
                    total: price,
                });
            } else {
                totals_taxes[total_tax_index].total = parseFloat(totals_taxes[total_tax_index].total) + parseFloat(price);
            }

            return totals_taxes;
        },

        // Select Item added form
        onSelectedItem(item) {
            let total = 1 * item.price;
            let item_taxes = [];

            if (item.tax_ids) {
                item.tax_ids.forEach(function (tax_id, index) {
                    if (this.taxes.includes(tax_id)) {
                        this.taxes[tax_id].push(item.id);
                    } else {
                        this.taxes.push(tax_id);
                        this.taxes[tax_id] = [];
                        this.taxes[tax_id].push(item.id);
                    }

                    item_taxes.push({
                        id: tax_id,
                        price: 10,
                    });
                }, this);
            }

            this.form.items.push({
                item_id: item.id,
                name: item.name,
                description: item.description,
                quantity: 1,
                price: item.price,
                tax_ids: item.tax_ids,
                discount: 0,
                total: total,
            });

            this.items.push({
                item_id: item.id,
                name: item.name,
                description: item.description,
                quantity: 1,
                price: item.price,
                add_tax: (document.getElementById('invoice-item-discount-rows') != null) ? false : true,
                tax_ids: item_taxes,
                add_discount: false,
                discount: 0,
                discount_amount: 0,
                total: total,
                // @todo
                // invoice_item_checkbox_sample: [],
            });

            setTimeout(function() {
                this.onCalculateTotal();
            }.bind(this), 800);
        },

        onSelectedTax(item_index) {
            if (!this.tax_id) {
                return;
            }

            let selected_tax;

            document_taxes.forEach(function(tax) {
                if (tax.id == this.tax_id) {
                    selected_tax = tax;
                }
            }, this);

            this.items[item_index].tax_ids.push({
                id: selected_tax.id,
                name: selected_tax.title,
                price: 0
            });

            this.form.items[item_index].tax_ids.push(this.tax_id);

            if (this.taxes.includes(this.tax_id)) {
                this.taxes[this.tax_id].push(this.items[item_index].item_id);
            } else {
                this.taxes[this.tax_id] = [];
                this.taxes[this.tax_id].push(this.items[item_index].item_id);
            }

            this.tax_id = '';

            this.onCalculateTotal();
        },

        // remove document item row => row_id = index
        onDeleteItem(index) {
            this.items.splice(index, 1);
            this.form.items.splice(index, 1);

            this.onCalculateTotal();
        },

        onAddLineDiscount(item_index) {
            this.items[item_index].add_discount = true;
        },

        onAddTotalDiscount() {
            let discount = document.getElementById('pre-discount').value;

            if (discount < 0) {
                discount = 0;
            } else if (discount > 100) {
                discount = 100;
            }

            document.getElementById('pre-discount').value = discount;

            this.form.discount = discount;
            this.discount = false;

            this.onCalculateTotal();
        },

        onDeleteDiscount(item_index) {
            this.items[item_index].add_discount = false;
        },

        onAddTax(item_index) {
            this.items[item_index].add_tax = true;
        },

        onDeleteTax(item_index, tax_index) {
            if (tax_index == '999') {
                this.items[item_index].add_tax = false;

                return;
            }

            this.items[item_index].tax_ids.splice(tax_index, 1);
            this.form.items[item_index].tax_ids.splice(tax_index, 1);

            this.onCalculateTotal();
        },

        onBindingItemField(item_index, field_name) {
            this.form.items[item_index][field_name] = this.items[item_index][field_name];
        },

        async onPayment() {
            let document_id = document.getElementById('document_id').value;

            let payment = {
                modal: false,
                url: url + '/modals/documents/' + document_id + '/transactions/create',
                title: '',
                html: '',
                buttons:{}
            };

            let payment_promise = Promise.resolve(window.axios.get(payment.url));

            payment_promise.then(response => {
                payment.modal = true;
                payment.title = response.data.data.title;
                payment.html = response.data.html;
                payment.buttons = response.data.data.buttons;

                this.component = Vue.component('add-new-component', (resolve, reject) => {
                    resolve({
                        template: '<div id="dynamic-payment-component"><akaunting-modal-add-new modal-dialog-class="modal-md" :show="payment.modal" @submit="onSubmit" @cancel="onCancel" :buttons="payment.buttons" :title="payment.title" :is_component=true :message="payment.html"></akaunting-modal-add-new></div>',

                        mixins: [
                            Global
                        ],

                        data: function () {
                            return {
                                form:{},
                                payment: payment,
                            }
                        },

                        methods: {
                            onSubmit(event) {
                                this.form = event;
                                this.form.response = {};

                                this.loading = true;

                                let data = this.form.data();

                                FormData.prototype.appendRecursive = function(data, wrapper = null) {
                                    for(var name in data) {
                                        if (wrapper) {
                                            if ((typeof data[name] == 'object' || data[name].constructor === Array) && ((data[name] instanceof File != true ) && (data[name] instanceof Blob != true))) {
                                                this.appendRecursive(data[name], wrapper + '[' + name + ']');
                                            } else {
                                                this.append(wrapper + '[' + name + ']', data[name]);
                                            }
                                        } else {
                                            if ((typeof data[name] == 'object' || data[name].constructor === Array) && ((data[name] instanceof File != true ) && (data[name] instanceof Blob != true))) {
                                                this.appendRecursive(data[name], name);
                                            } else {
                                                this.append(name, data[name]);
                                            }
                                        }
                                    }
                                };

                                let form_data = new FormData();
                                form_data.appendRecursive(data);

                                window.axios({
                                    method: this.form.method,
                                    url: this.form.action,
                                    data: form_data,
                                    headers: {
                                        'X-CSRF-TOKEN': window.Laravel.csrfToken,
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Content-Type': 'multipart/form-data'
                                    }
                                })
                                .then(response => {
                                    if (response.data.success) {
                                        if (response.data.redirect) {
                                            this.form.loading = true;

                                            window.location.href = response.data.redirect;
                                        }
                                    }

                                    if (response.data.error) {
                                        this.form.loading = false;

                                        this.form.response = response.data;
                                    }
                                })
                                .catch(error => {
                                    this.form.loading = false;

                                    this.form.onFail(error);

                                    this.method_show_html = error.message;
                                });
                            },

                            onCancel() {
                                this.payment.modal = false;
                                this.payment.html = null;

                                let documentClasses = document.body.classList;

                                documentClasses.remove("modal-open");
                            },
                        }
                    })
                });
            })
            .catch(error => {
            })
            .finally(function () {
                // always executed
            });
        },

        // Change currency get money
        onChangeCurrency(currency_code) {
            if (this.edit.status && this.edit.currency <= 3) {
                this.edit.currency++;
                return;
            }

            if (!this.currencies.length) {
                let currency_promise = Promise.resolve(window.axios.get((url + '/settings/currencies')));

                currency_promise.then(response => {
                    if ( response.data.success) {
                        this.currencies = response.data.data;
                    }
                })
                .catch(error => {
                    this.onChangeCurrency(currency_code);
                });
            }

            this.currencies.forEach(function (currency, index) {
                if (currency_code == currency.code) {
                    this.currency = currency;

                    this.form.currency_code = currency.code;
                    this.form.currency_rate = currency.rate;
                }
            }, this);
        },
    },

    created() {
        this.form.items = [];

        if (typeof document_items !== 'undefined' && document_items) {
            this.edit.status = true;
            this.edit.currency = 1;

            document_items.forEach(function(item) {
                // form set item
                this.form.items.push({
                    item_id: item.item_id,
                    name: item.name,
                    description: item.description === null ? "" : item.description,
                    quantity: item.quantity,
                    price: (item.price).toFixed(2),
                    tax_ids: item.tax_ids,
                    discount: item.discount_rate,
                    total: (item.total).toFixed(2)
                });

                if (item.tax_ids) {
                    item.tax_ids.forEach(function (tax_id, index) {
                        if (this.taxes.includes(tax_id)) {
                            this.taxes[tax_id].push(item.id);
                        } else {
                            this.taxes.push(tax_id);

                            this.taxes[tax_id] = [];

                            this.taxes[tax_id].push(item.id);
                        }
                    }, this);
                }

                let item_taxes = [];

                item.taxes.forEach(function(item_tax) {
                    item_taxes.push({
                        id: item_tax.tax_id,
                        name: item_tax.name,
                        price: (item_tax.amount).toFixed(2),
                    });
                });

                this.items.push({
                    item_id: item.item_id,
                    name: item.name,
                    description: item.description === null ? "" : item.description,
                    quantity: item.quantity,
                    price: (item.price).toFixed(2),
                    add_tax: (!item_taxes.length && document.getElementById('invoice-item-discount-rows') != null) ? false : true,
                    tax_ids: item_taxes,
                    add_discount: (item.discount_rate) ? true : false,
                    discount: item.discount_rate,
                    total: (item.total).toFixed(2),
                    // @todo
                    // invoice_item_checkbox_sample: [],
                });
            }, this);

            this.items.forEach(function(item) {
                item.tax_ids.forEach(function(tax) {
                    let total_tax_index = this.totals.taxes.findIndex(total_tax => {
                        if (total_tax.id === tax.id) {
                          return true;
                        }
                    }, this);

                    if (total_tax_index === -1) {
                        this.totals.taxes.push({
                            id: tax.id,
                            name: tax.name,
                            total: tax.price,
                        });
                    } else {
                        this.totals.taxes[total_tax_index].total = parseFloat(this.totals.taxes[total_tax_index].total) + parseFloat(tax.price);
                    }
                }, this);
            }, this);
        }

        this.page_loaded = true;

        if (document_currencies) {
            this.currencies = document_currencies;

            this.currencies.forEach(function (currency, index) {
                if (document_default_currency == currency.code) {
                    this.currency = currency;

                    this.form.currency_code = currency.code;
                }
            }, this);
        }
    }
});
