import Vue from 'vue';

import DashboardPlugin from './../plugins/dashboard-plugin';

import axios from 'axios';

import AkauntingSearch from './../components/AkauntingSearch';
import AkauntingModal from './../components/AkauntingModal';
import AkauntingRadioGroup from './../components/forms/AkauntingRadioGroup';
import AkauntingSelect from './../components/AkauntingSelect';
import AkauntingDate from './../components/AkauntingDate';
import AkauntingRecurring from './../components/AkauntingRecurring';

import NProgress from 'nprogress';
import 'nprogress/nprogress.css';
import NProgressAxios from './../plugins/nprogress-axios';

import {VMoney} from 'v-money';
import { Select, Option } from 'element-ui';

// plugin setup
Vue.use(DashboardPlugin);

export default {
    components: {
        AkauntingSearch,
        AkauntingRadioGroup,
        AkauntingSelect,
        AkauntingModal,
        AkauntingDate,
        AkauntingRecurring,
        [Select.name]: Select,
        [Option.name]: Option
    },

    data: function () {
        return {
            addNew: {
                modal: false,
                title: '',
                html: null
            },
            confirm: {
                url: '',
                title: '',
                message: '',
                button_cancel: '',
                button_delete: '',
                show: false
            },
            money: {
                decimal: '.',
                thousands: ',',
                prefix: '$ ',
                suffix: '',
                precision: 2,
                masked: false /* doesn't work with directive */
            }
        }
    },

    directives: {
        money: VMoney
    },

    mounted() {
        this.checkNotify();

        if (aka_currency) {
            this.money.decimal = aka_currency.decimal_mark;
            this.money.thousands = aka_currency.thousands_separator;
            this.money.prefix = (aka_currency.symbol_first) ? aka_currency.symbol : '';
            this.money.suffix = !(aka_currency.symbol_first) ? aka_currency.symbol : '';
            this.money.precision = aka_currency.precision;
        }
    },

    methods: {
        // Check Default set notify > store / update action
        checkNotify: function () {
            if (!flash_notification) {
                return false;
            }

            flash_notification.forEach(notify => {
                let type = notify.level;

                this.$notify({
                    message: notify.message,
                    timeout: 5000,
                    icon: 'fas fa-bell',
                    type
                });
            });
        },

        // Form Submit
        onSubmit() {
            this.form.submit();
        },

        onHandleFileUpload(key, event) {
            this.form[key] = '';
            this.form[key] = event.target.files[0];
        },

        // Bulk Action Select all
        onSelectAll() {
            this.bulk_action.selectAll();
        },

        // Bulk Action Select checked/ unchecked
        onSelect() {
            this.bulk_action.select();
        },

        // Bulk Action use selected Change
        onChange(event) {
            var result = this.bulk_action.change(event);
        },

        // Bulk Action use selected Action
        onAction() {
            this.bulk_action.action();

            window.location.reload(false);
        },

        // Bulk Action modal cancel
        onCancel() {
            this.bulk_action.modal = false;
        },

        // Bulk Action Clear selected items
        onClear() {
            this.bulk_action.modal = false;

            this.bulk_action.clear();
        },

        // List Enabled column status changes
        onStatus(item_id, event) {
            this.bulk_action.status(item_id, event, this.$notify);
        },

        // Actions > Delete
        confirmDelete(url, title, message, button_cancel, button_delete) {
            this.confirm.url = url;
            this.confirm.title = title;
            this.confirm.message = message;
            this.confirm.button_cancel = button_cancel;
            this.confirm.button_delete = button_delete;
            this.confirm.show = true;
        },

        // Delete action post
        onDelete() {
            axios({
                method: 'DELETE',
                url: this.confirm.url,
            })
            .then(response => {
                if (response.data.redirect) {
                    this.confirm.url = '';
                    this.confirm.title = '';
                    this.confirm.message = '';
                    this.confirm.show = false;

                    window.location.href = response.data.redirect;
                }
            })
            .catch(error => {
                this.success = false;
            });
        },

        // Close modal empty default value
        cancelDelete() {
            this.confirm.url = '';
            this.confirm.title = '';
            this.confirm.message = '';
            this.confirm.show = false;
        },

        onNewItem(event) {
            console.log(event);

            axios.get(event.path)
            .then(response => {
                this.addNew.modal = true;
                this.addNew.title = event.title;
                this.addNew.html = response.data.html;

                /*
                this.selectOptions[3] = value;

                let newOption = {
                    value: "3",
                    currentLabel: value,
                    label: value
                };

                this.$children[0].$children[0].handleOptionSelect(newOption);
                this.$children[0].$children[0].onInputChange('3');

                this.real_model = "3";

                this.$emit('change', this.real_model);
                */
            })
            .catch(e => {
                this.errors.push(e)
            })
            .finally(function () {
                // always executed
            });
        },

        // Change bank account get money and currency rate
        onChangeAccount(account_id) {
            axios.get(url + '/banking/accounts/currency', {
                params: {
                    account_id: account_id
                }
              })
            .then(response => {
                this.form.currency_code = response.data.currency_code;
                this.form.currency_rate = response.data.currency_rate;

                this.money.decimal = response.data.decimal_mark;
                this.money.thousands = response.data.thousands_separator;
                this.money.prefix = (response.data.symbol_first) ? response.data.symbol : '';
                this.money.suffix = !(response.data.symbol_first) ? response.data.symbol : '';
                this.money.precision = response.data.precision;
            })
            .catch(error => {
            });
        },

        onChangePaginationLimit(event) {
            let path = '';

            if (window.location.search.length) {
                if (window.location.search.includes('limit')) {
                    let queries = [];
                    let query = window.location.search;

                    query = query.replace('?', '');
                    queries = query.split('&');

                    path = window.location.origin + window.location.pathname;

                    queries.forEach(function (_query, index) {
                        let query_partials = _query.split('=');

                        if (index == 0) {
                            path += '?'
                        } else {
                            path += '&';
                        }

                        if (query_partials[0] == 'limit') {
                            path += 'limit=' + event.target.value;
                        } else {
                            path += query_partials[0] + '=' + query_partials[1];
                        }
                    });

                } else {
                    path = window.location.href + '&limit=' + event.target.value;
                }
            } else {
                path = window.location.href + '?limit=' + event.target.value;
            }

            window.location.href = path;
        }
    }
}
