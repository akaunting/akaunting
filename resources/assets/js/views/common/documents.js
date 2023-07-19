/**
* First we will load all of this project's JavaScript dependencies which
* includes Vue and other libraries. It is a great starting point when
* building robust, powerful web applications using Vue and Laravel.
*/
require('./../../bootstrap');
import Vue from 'vue';

import DashboardPlugin from './../../plugins/dashboard-plugin';
import { addDays, format } from 'date-fns';
import { setPromiseTimeout, getQueryVariable } from './../../plugins/functions';

import Global from './../../mixins/global';

import Form from './../../plugins/form';
import BulkAction from './../../plugins/bulk-action';
import draggable from 'vuedraggable';

// plugin setup
Vue.use(DashboardPlugin);

const app = new Vue({
    el: '#main-body',

    mixins: [
        Global
    ],

    components: {
        draggable
    },

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
            selected_items:[],
            taxes: [],
            page_loaded: false,
            currencies: [],
            min_due_date: false,
            currency_symbol: {
               "name":"US Dollar",
               "code":"USD",
               "rate":1,
               "precision":2,
               "symbol":"$",
               "symbol_first":1,
               "decimal_mark":".",
               "thousands_separator":","
            },
            dropdown_visible: true,
            dynamic_taxes: [],
            show_discount: false,
            show_discount_text: true,
            delete_discount: false,
            regex_condition: [
                '..',
                '.,',
                ',.',
                ',,'
            ],
            email_template: false,
            send_to: false,
        }
    },

    mounted() {
        this.form.discount_type = 'percentage';

        if ((document.getElementById('items') != null) && (document.getElementById('items').rows)) {
            this.colspan = document.getElementById("items").rows[0].cells.length - 1;
        }

        if (! this.edit.status) {
           this.dropdown_visible = false;
        }

        this.currency_symbol.rate = this.form.currency_rate;

        if (company_currency_code) {
           let default_currency_symbol = null;

           for (let symbol of this.currencies) {
               if (symbol.code == company_currency_code) {
                   default_currency_symbol = symbol.symbol;
               }
           }

           this.currency_symbol.symbol = default_currency_symbol;
        };

        if (document_app_env == 'production') {
           this.onFormCapture();
        }
    },

    methods: {
        onItemSortUpdate(event) {
            let item_index = this.form.items.indexOf(this.form.items[event.oldIndex]);
            let item = this.form.items.splice(item_index, 1)[0];

            this.form.items.splice(event.newIndex, 0, item);
        },

        onRefFocus(ref) {
            let index = this.form.items.length - 1;

            if (typeof (this.$refs['items-' + index + '-' + ref]) !== 'undefined') {
                let first_ref = this.$refs['items-' + index + '-'  + ref];

                if (first_ref != undefined) {
                    first_ref[0].focus();
                } else if (this.$refs[Object.keys(this.$refs)[0]] != undefined) {
                    this.$refs[Object.keys(this.$refs)[0]][0].focus();
                }
            }
        },

        onCalculateTotal() {
            let global_discount = parseFloat(this.form.discount);
            let total_discount = 0;
            let line_item_discount_total = 0;
            let sub_total = 0;
            let totals_taxes = [];
            let grand_total = 0;
            let items_amount = this.calculateTotalBeforeDiscountAndTax();

            // items calculate
            this.items.forEach(function(item, index) {
                item.total = item.grand_total = item.price * item.quantity;

                let item_discounted_total = items_amount[index];

                let line_discount_amount = item.total - item_discounted_total;

                // Apply line & total discount to item
                if (global_discount) {
                    if (this.form.discount_type === 'percentage') {
                        if (global_discount > 100) {
                            global_discount = 100;
                        }

                        total_discount += (item_discounted_total / 100) * global_discount;
                        item_discounted_total -= (item_discounted_total / 100) * global_discount;
                    } else {
                        total_discount += (items_amount[index] / (items_amount['total'] / 100)) * (global_discount / 100);
                        item_discounted_total -= (items_amount[index] / (items_amount['total'] / 100)) * (global_discount / 100);
                    }
                }

                // set item total
                if (item.discount || global_discount) {
                    item.grand_total = item_discounted_total;
                }

                this.calculateItemTax(item, totals_taxes, total_discount + line_discount_amount);

                item.total = item.price * item.quantity;

                // calculate sub, tax, discount all items.
                line_item_discount_total += line_discount_amount;
                sub_total += item.total;
                grand_total += item.grand_total;

                let item_tax_ids = [];

                item.tax_ids.forEach(function(item_tax, item_tax_index) {
                    item_tax_ids.push(item_tax.id);
                });

                this.form.items[index].name = item.name;
                this.form.items[index].description = item.description;
                this.form.items[index].quantity = item.quantity;
                this.form.items[index].price = item.price;
                this.form.items[index].tax_ids = item_tax_ids;
                this.form.items[index].discount = item.discount;
                this.form.items[index].discount_type = item.discount_type;
                this.form.items[index].total = item.total;
            }, this);

            this.totals.item_discount = line_item_discount_total;
            this.totals.discount = total_discount;
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

            this.currencyConversion();
        },

        calculateItemTax(item, totals_taxes, total_discount_amount) {
            let taxes = this.dynamic_taxes;

            if (item.tax_ids) {
                let inclusive_tax_total = 0;
                let price_for_tax = 0;
                let total_tax_amount = 0;
                let inclusives = [];
                let compounds = [];
                let fixed = [];
                let withholding = [];
                let normal = [];

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
                }, this);

                if (inclusives.length) {
                    inclusives.forEach(function(inclusive) {
                        item.tax_ids[inclusive.tax_index].name = inclusive.tax_name;
                        item.tax_ids[inclusive.tax_index].price = item.grand_total - (item.grand_total / (1 + inclusive.tax_rate / 100));

                        inclusive_tax_total += item.tax_ids[inclusive.tax_index].price;

                        totals_taxes = this.calculateTotalsTax(totals_taxes, inclusive.tax_id, inclusive.tax_name, item.tax_ids[inclusive.tax_index].price);
                    }, this);

                    item.total = parseFloat(item.grand_total - inclusive_tax_total);
                }

                if (fixed.length) {
                    fixed.forEach(function(fixed) {
                        item.tax_ids[fixed.tax_index].name = fixed.tax_name;
                        item.tax_ids[fixed.tax_index].price = fixed.tax_rate * item.quantity;

                        total_tax_amount += item.tax_ids[fixed.tax_index].price;

                        totals_taxes = this.calculateTotalsTax(totals_taxes, fixed.tax_id, fixed.tax_name, item.tax_ids[fixed.tax_index].price);
                    }, this);
                }

                if (inclusives.length) {
                    price_for_tax = item.total;
                } else {
                    price_for_tax = item.grand_total;
                }

                if (normal.length) {
                    normal.forEach(function(normal) {
                        item.tax_ids[normal.tax_index].name = normal.tax_name;
                        item.tax_ids[normal.tax_index].price = price_for_tax * (normal.tax_rate / 100);

                        total_tax_amount += item.tax_ids[normal.tax_index].price;

                        totals_taxes = this.calculateTotalsTax(totals_taxes, normal.tax_id, normal.tax_name, item.tax_ids[normal.tax_index].price);
                    }, this);
                }

                if (withholding.length) {
                    withholding.forEach(function(withholding) {
                        item.tax_ids[withholding.tax_index].name = withholding.tax_name;
                        item.tax_ids[withholding.tax_index].price = -(price_for_tax * (withholding.tax_rate / 100));

                        total_tax_amount += item.tax_ids[withholding.tax_index].price;

                        totals_taxes = this.calculateTotalsTax(totals_taxes, withholding.tax_id, withholding.tax_name, item.tax_ids[withholding.tax_index].price);
                    }, this);
                }

                item.grand_total += total_tax_amount;

                if (compounds.length) {
                    compounds.forEach(function(compound) {
                        item.tax_ids[compound.tax_index].name = compound.tax_name;
                        item.tax_ids[compound.tax_index].price = (item.grand_total / 100) * compound.tax_rate;

                        totals_taxes = this.calculateTotalsTax(totals_taxes, compound.tax_id, compound.tax_name, item.tax_ids[compound.tax_index].price);

                        item.grand_total += item.tax_ids[compound.tax_index].price;
                    }, this);
                }

                if (inclusives.length) {
                    item.total += total_discount_amount;
                }
            }
        },

        calculateTotalBeforeDiscountAndTax() {
            let amount_before_discount_and_tax = [];
            let total = 0;

            this.items.forEach(function(item, index) {
                let item_total = 0;

                item_total = item.price * item.quantity;

                // item discount calculate.
                if (item.discount) {
                    if (item.discount_type === 'percentage') {
                        if (item.discount > 100) {
                            item.discount = 100;
                        }

                        item.discount_amount = item_total * (item.discount / 100);
                    } else {
                        if (parseInt(item.discount) > item_total) {
                            item.discount_amount = item_total;
                        } else {
                            item.discount_amount = parseFloat(item.discount);
                        }
                    }
                } else {
                    item.discount_amount = 0;
                }

                total += item_total - item.discount_amount;
                amount_before_discount_and_tax[index] = item_total - item.discount_amount;
            });

            amount_before_discount_and_tax['total'] = total;

            return amount_before_discount_and_tax;
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

        onSelectedItem(item){
            this.onAddItem(item);
        },

        // addItem to list
        onAddItem(payload) {
            let { item, itemType } = payload;
            let inputRef = `${itemType === 'newItem' ? 'name' : 'description'}`; // indication for which input to focus first
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
                add_tax: false,
                tax_ids: item_taxes,
                add_discount: false,
                discount: 0,
                discount_amount: 0,
                total: total,
                // @todo
                // invoice_item_checkbox_sample: [],
            });

            setTimeout(function() {
                this.onRefFocus(inputRef);
            }.bind(this), 100);

            setTimeout(function() {
                this.onCalculateTotal();
            }.bind(this), 800);
        },

        onSelectedTax(item_index) {
            if (! this.tax_id || this.tax_id == '') {
                return;
            }

            let selected_tax;

            this.dynamic_taxes.forEach(function(tax) {
                if (tax.id == this.tax_id) {
                    selected_tax = tax;
                }
            }, this);

            if (selected_tax) {
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
            }

            this.tax_id = '';
            this.items[item_index].add_tax = false;

            this.onCalculateTotal();
        },

        // remove document item row => row_id = index
        onDeleteItem(index) {
            this.items.splice(index, 1);
            this.form.items.splice(index, 1);

            this.onCalculateTotal();
        },

        onAddLineDiscount(item_index) {
            this.items[item_index].discount_type = 'percentage';
            this.items[item_index].add_discount = true;
        },

        onChangeDiscountType(type) {
            this.form.discount_type = type;

            this.onAddTotalDiscount();
            this.onCalculateTotal();
        },

        onChangeLineDiscountType(item_index, type) {
            this.items[item_index].discount_type = type;

            this.onCalculateTotal();
        },

        onAddTotalDiscount() {
            let discount = document.getElementById('pre-discount').value;

            if (this.form.discount_type === 'percentage') {
                if (discount < 0) {
                    discount = 0;
                } else if (discount > 100) {
                    discount = 100;
                }
            } else {
                if (discount < 0) {
                    discount = 0;
                } else if (discount > this.totals.sub) {
                    discount = this.totals.sub;
                }
            }

            document.getElementById('pre-discount').value = discount;

            this.form.discount = discount;
            this.discount = false;

            this.onCalculateTotal();
        },

        onDeleteDiscount(item_index) {
            this.items[item_index].add_discount = false;
            this.items[item_index].discount = 0;

            this.onCalculateTotal();
        },

        onAddTax(item_index) {
            this.items[item_index].add_tax = true;
        },

        onAddDiscount() {
            this.show_discount = !this.show_discount;

            if (this.show_discount) {
                this.show_discount_text = false;
                this.delete_discount = true;
            }
        },

        onRemoveDiscountArea() {
            this.show_discount = false;
            this.show_discount_text = true;
            this.discount = false;
            this.delete_discount = false;
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

        onEmailViaTemplate(route, template) {
            this.email_template = template;

            this.onSendEmail(route);
        },

        // Change currency get money
        onChangeCurrency(currency_code) {
            if (this.edit.status && this.edit.currency <= 2) {
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

                    this.currencyConversion();
                }

                if (company_currency_code == currency.code) {
                   this.currency_symbol = currency;
                }
            }, this);
        },

        setDueMinDate(date) {
            this.min_due_date = date;
        },

        currencyConversion() {
            setTimeout(() => {
                if (document.querySelectorAll('.js-conversion-input')) {
                    let currency_input = document.querySelectorAll('.js-conversion-input');

                    for(let input of currency_input) {
                        input.setAttribute('size', input.value.length);
                    }
                }
            }, 250);
        },

        onBeforeUnload() {
            window.onbeforeunload = function() {
                return 'Are you sure you want to leave this page';
            };
        },

        onFormCapture() {
           let form_html = document.querySelector('form');

           if (form_html && form_html.getAttribute('id') == 'document') {
               form_html.querySelectorAll('input, textarea, select, ul, li, a').forEach((element) => {
                  element.addEventListener('click', () => {
                      this.onBeforeUnload();
                  });
               });

               form_html.querySelectorAll('[type="submit"]').forEach((submit) => {
                   submit.addEventListener('click', () => {
                        window.onbeforeunload = null;
                   });
               });

               form_html.querySelectorAll('[type="button"]').forEach((button) => {
                button.addEventListener('click', () => {
                     window.onbeforeunload = null;
                });
            });
           }
        },

        onChangeRecurringDate() {
            let started_at = new Date(this.form.recurring_started_at);
            let due_at = format(addDays(started_at, this.form.payment_terms), 'YYYY-MM-DD');

            this.form.due_at = due_at;
        },

        onSubmitViaSendEmail() {
            this.form['senddocument'] = true;
            this.send_to = true;

            this.onSubmit();
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
                    discount_type: item.discount_type,
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
                    add_tax: false,
                    tax_ids: item_taxes,
                    add_discount: (item.discount_rate) ? true : false,
                    discount: item.discount_rate,
                    discount_type: item.discount_type,
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

        if (typeof document_currencies !== 'undefined' && document_currencies) {
            this.currencies = document_currencies;

            this.currencies.forEach(function (currency, index) {
                if (document_default_currency == currency.code) {
                    this.currency = currency;

                    this.form.currency_code = currency.code;
                }
            }, this);
        }

        if (typeof document_taxes !== 'undefined' && document_taxes) {
            this.dynamic_taxes = document_taxes;
        }

        if (getQueryVariable('senddocument')) {
            // clear query string
            let uri = window.location.toString();

            if (uri.indexOf("?") > 0) {
                let clean_uri = uri.substring(0, uri.indexOf("?"));

                window.history.replaceState({}, document.title, clean_uri);
            }

            let email_route = document.getElementById('senddocument_route').value;

            this.onSendEmail(email_route);
        }

        this.page_loaded = true;
    },

    watch: {
        'form.discount': function (newVal, oldVal) {
            if (typeof newVal !== 'string') {
                return;
            }

            if (newVal != '' && newVal.search('^(?=.*?[0-9])[0-9.,]+$') !== 0) {
                this.form.discount = oldVal;

                if (Number(newVal) == null) {
                    this.form.discount.replace(',', '.');
                }

                return;
            }

            for (let item of this.regex_condition) {
                if (this.form.discount && this.form.discount.includes(item)) {
                    const removeLastChar  = newVal.length - 1;
                    const inputShown = newVal.slice(0, removeLastChar);

                    this.form.discount = inputShown;
                }
            }

           this.form.discount.replace(',', '.');
        },

        'form.loading': function (newVal, oldVal) {
            if (! newVal) {
                this.send_to = false;
            }
        },
    },
});
