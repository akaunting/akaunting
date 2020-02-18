import Vue from 'vue';

import axios from 'axios';

import AkauntingSearch from './../components/AkauntingSearch';
import AkauntingModal from './../components/AkauntingModal';
import AkauntingMoney from './../components/AkauntingMoney';
import AkauntingModalAddNew from './../components/AkauntingModalAddNew';
import AkauntingRadioGroup from './../components/forms/AkauntingRadioGroup';
import AkauntingSelect from './../components/AkauntingSelect';
import AkauntingSelectRemote from './../components/AkauntingSelectRemote';
import AkauntingDate from './../components/AkauntingDate';
import AkauntingRecurring from './../components/AkauntingRecurring';

import NProgress from 'nprogress';
import 'nprogress/nprogress.css';
import NProgressAxios from './../plugins/nprogress-axios';

import { Select, Option, Steps, Step, Button, Link, Tooltip } from 'element-ui';

import Form from './../plugins/form';

export default {
    components: {
        AkauntingSearch,
        AkauntingRadioGroup,
        AkauntingSelect,
        AkauntingSelectRemote,
        AkauntingMoney,
        AkauntingModal,
        AkauntingModalAddNew,
        AkauntingDate,
        AkauntingRecurring,
        [Select.name]: Select,
        [Option.name]: Option,
        [Steps.name]: Steps,
        [Step.name]: Step,
        [Button.name]: Button,
        [Link.name]: Link,
        [Tooltip.name]: Tooltip,
    },

    data: function () {
        return {
            component: '',
        }
    },

    directives: {
        //money: VMoney
    },

    mounted() {
        this.checkNotify();
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
            let confirm = {
                url: url,
                title: title,
                message: message,
                button_cancel: button_cancel,
                button_delete: button_delete,
                show: true
            };

            this.component = Vue.component('add-new-component', (resolve, reject) => {
                resolve({
                    template : '<div id="dynamic-component"><akaunting-modal v-if="confirm.show" :show="confirm.show" :title="confirm.title" :message="confirm.message" :button_cancel="confirm.button_cancel" :button_delete="confirm.button_delete" @confirm="onDelete" @cancel="cancelDelete"></akaunting-modal></div>',

                    components: {
                        AkauntingModal,
                    },

                    data: function () {
                        return {
                            confirm: confirm,
                        }
                    },

                    methods: {
                        // Delete action post
                       async onDelete() {
                            let promise = Promise.resolve(axios({
                                method: 'DELETE',
                                url: this.confirm.url,
                            }));

                            promise.then(response => {
                                if (response.data.redirect) {
                                    window.location.href = response.data.redirect;
                                }
                            })
                            .catch(error => {
                                this.success = false;
                            });
                        },

                        // Close modal empty default value
                        cancelDelete() {
                            this.confirm.show = false;
                        },
                    }
                })
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
        },

        onDynamicComponent(path)
        {
            axios.get(path)
            .then(response => {
                let html = response.data.html;

                this.component = Vue.component('add-new-component', (resolve, reject) => {
                    resolve({
                        template : '<div id="dynamic-component">' + html + '</div>',

                        components: {
                            AkauntingSearch,
                            AkauntingRadioGroup,
                            AkauntingSelect,
                            AkauntingSelectRemote,
                            AkauntingModal,
                            AkauntingModalAddNew,
                            AkauntingDate,
                            AkauntingRecurring,
                            [Select.name]: Select,
                            [Option.name]: Option,
                            [Steps.name]: Steps,
                            [Step.name]: Step,
                            [Button.name]: Button,
                        },

                        created: function() {
                            this.form = new Form('form-create');
                        },

                        mounted() {
                            let form_id = document.getElementById('dynamic-component').querySelectorAll('form')[1].id;

                            this.form = new Form(form_id);
                        },

                        data: function () {
                            return {
                                form: {},
                                dynamic: {
                                    data: dynamic_data
                                }
                            }
                        },

                        methods: {
                        }
                    })
                });
            })
            .catch(e => {
                this.errors.push(e);
            })
            .finally(function () {
                // always executed
            });
        },
    }
}
