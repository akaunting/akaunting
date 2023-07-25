import Vue from 'vue';

import axios from 'axios';

import AkauntingDropzoneFileUpload from './../components/AkauntingDropzoneFileUpload';
import AkauntingContactCard from './../components/AkauntingContactCard';
import AkauntingCompanyEdit from './../components/AkauntingCompanyEdit';
import AkauntingEditItemColumns from './../components/AkauntingEditItemColumns';
import AkauntingItemButton from './../components/AkauntingItemButton';
import AkauntingDocumentButton from './../components/AkauntingDocumentButton';
import AkauntingSearch from './../components/AkauntingSearch';
import AkauntingModal from './../components/AkauntingModal';
import AkauntingMoney from './../components/AkauntingMoney';
import AkauntingModalAddNew from './../components/AkauntingModalAddNew';
import AkauntingRadioGroup from './../components/AkauntingRadioGroup';
import AkauntingSelect from './../components/AkauntingSelect';
import AkauntingSelectRemote from './../components/AkauntingSelectRemote';
import AkauntingDate from './../components/AkauntingDate';
import AkauntingRecurring from './../components/AkauntingRecurring';
import AkauntingHtmlEditor from './../components/AkauntingHtmlEditor';
import AkauntingCountdown from './../components/AkauntingCountdown';
import AkauntingCurrencyConversion from './../components/AkauntingCurrencyConversion';
import AkauntingConnectTransactions from './../components/AkauntingConnectTransactions';
import AkauntingSwitch from './../components/AkauntingSwitch';
import AkauntingSlider from './../components/AkauntingSlider';
import AkauntingColor from './../components/AkauntingColor';
import AkauntingImport from './../components/AkauntingImport';
import CardForm from './../components/CreditCard/CardForm';

import NProgress from 'nprogress';
import 'nprogress/nprogress.css';
import NProgressAxios from './../plugins/nprogress-axios';

import { Select, Option, Steps, Step, Button, Link, Tooltip, ColorPicker } from 'element-ui';

import Form from './../plugins/form';
import Swiper, { Navigation, Pagination } from 'swiper';
import GLightbox from 'glightbox';

Swiper.use([Navigation, Pagination]);

import Bugsnag from './../exceptions/trackers/bugsnag';
import Sentry from './../exceptions/trackers/sentry';

// Exception Tracket start here!!s
if (typeof exception_tracker != 'undefined') {
    switch (exception_tracker.channel) {
        case 'bugsnag':
            Vue.use(Bugsnag);
            break;
        case 'sentry':
            Vue.use(Sentry);
            break;
    }
}

var BreakException = {};

export default {
    components: {
        AkauntingDropzoneFileUpload,
        AkauntingContactCard,
        AkauntingCompanyEdit,
        AkauntingEditItemColumns,
        AkauntingItemButton,
        AkauntingDocumentButton,
        AkauntingSearch,
        AkauntingRadioGroup,
        AkauntingSelect,
        AkauntingSelectRemote,
        AkauntingMoney,
        AkauntingModal,
        AkauntingModalAddNew,
        AkauntingDate,
        AkauntingRecurring,
        AkauntingHtmlEditor,
        AkauntingCountdown,
        AkauntingCurrencyConversion,
        AkauntingConnectTransactions,
        AkauntingSwitch,
        AkauntingSlider,
        AkauntingColor,
        AkauntingImport,
        CardForm,
        [Select.name]: Select,
        [Option.name]: Option,
        [Steps.name]: Steps,
        [Step.name]: Step,
        [Button.name]: Button,
        [Link.name]: Link,
        [Tooltip.name]: Tooltip,
        [ColorPicker.name]: ColorPicker,
    },

    data: function () {
        return {
            component: '',
            currency: {
                "name":"US Dollar",
                "code":"USD",
                "rate":1,
                "precision":2,
                "symbol":"$",
                "symbol_first":1,
                "decimal_mark":".",
                "thousands_separator":",",
            },
            all_currencies: [],
            connect: {
                show: false,
                currency: {},
                documents: [],
            },

            cardData: {
                cardName: '',
                cardNumber: '',
                cardMonth: '',
                cardYear: '',
                cardCvv: '',
                storeCard: false,
                card_id: 0,
            },

            min_date: false,
            item_name_input: false,
            price_name_input: false,
            quantity_name_input: false,
        }
    },

    directives: {
        //money: VMoney
    },

    mounted() {
        this.checkNotify();

        GLightbox({
            touchNavigation: true,
            loop: false,
            autoplayVideos: false,
            selector: ".glightbox-video",
            plyr: {
                config: {
                    ratio: '16:9', // or '4:3'
                    muted: false,
                    hideControls: true,
                    youtube: {
                        noCookie: true,
                        rel: 0,
                        showinfo: 0,
                        iv_load_policy: 3
                    },
                },
            },
        })

        if (aka_currency) {
            this.currency = aka_currency;
        }

        if (typeof all_currencies !== 'undefined' && all_currencies) {
            this.all_currencies = all_currencies;
        }

        GLightbox({
            touchNavigation: true,
            loop: false,
            autoplayVideos: false,
            selector: ".glightbox"
        });

        new Swiper(".swiper-container", {
            loop: false,
            slidesPerView: 2,
            pagination: {
                el: ".swiper-pagination",
                clickable: true
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

        //swiper slider for long tabs items
        for (let [index, item] of document.querySelectorAll('[data-swiper]').entries()) {
            if (item.clientWidth < item.querySelector('[data-tabs-swiper-wrapper]').clientWidth && ! item.querySelector('[data-tabs-swiper-wrapper]').getAttribute('data-disable-slider', true)) {
                let initial_slide = 0;
                let hash_split = window.location.hash.split('#')[1];
                let loop = 0;
                let slides_view;

                try {
                    item.querySelectorAll('[data-tabs-slide]').forEach((slide, index, arr) => {
                        loop += slide.clientWidth;
    
                        slide.classList.add('swiper-slide');
        
                        if (slide.getAttribute('data-tabs') == hash_split) {
                            initial_slide = index;
                        }
    
                        if (loop > item.clientWidth) {
                            slides_view = index;
                            throw BreakException;
                        }
                    });
                } catch (e) {
                    if (e !== BreakException) throw e;
                }
                
                item.querySelector('[data-tabs-swiper]').classList.add('swiper', 'swiper-links');
                item.querySelector('[data-tabs-swiper-wrapper]').classList.add('swiper-wrapper');
    
                let html = `
                    <div class="swiper-tabs-container">
                        ${item.querySelector('[data-tabs-swiper]').innerHTML}
                    </div>
    
                    <div class="swiper-button-next bg-body text-white flex items-center justify-center right-0">
                        <span class="material-icons text-purple text-4xl">chevron_right</span>
                    </div>
                    <div class="swiper-button-prev bg-body text-white flex items-center justify-center left-0">
                        <span class="material-icons text-purple text-4xl">chevron_left</span>
                    </div>
                    `; 
    
                item.querySelector('[data-tabs-swiper]').innerHTML = html;
                slides_view = Number(item.getAttribute('data-swiper')) != 0 ? Number(item.getAttribute('data-swiper'))  : slides_view;
                item.setAttribute('data-swiper', slides_view);
                                
                new Swiper(item.querySelector('.swiper-tabs-container'), {
                    loop: true,
                    slidesPerView: slides_view,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    initialSlide: initial_slide,
                });
            } else {
                item.removeAttribute('data-swiper');
                item.querySelector('[data-tabs-swiper]').removeAttribute('data-tabs-swiper');
                item.querySelector('[data-tabs-swiper-wrapper]').removeAttribute('data-tabs-swiper-wrapper');
    
                item.querySelectorAll('[data-tabs-slide]').forEach((slide) => {
                    slide.removeAttribute('data-tabs-slide');
                });
            }
        }
        //swiper slider for long tabs items
    },

    methods: {
        // Check Default set notify > store / update action
        checkNotify: function () {
            if (! flash_notification) {
                return false;
            }

            flash_notification.forEach(notify => {
                let type = notify.level;
                let timeout = 5000;

                if (notify.important) {
                    timeout = 0;
                }

                this.$notify({
                    verticalAlign: 'bottom',
                    horizontalAlign: 'left',
                    message: notify.message,
                    timeout: timeout,
                    icon: 'error_outline',
                    type
                });
            });
        },

        // Form Submit
        onSubmit() {
            this.form.submit();
        },

        // Form Async Submit
        async onAsyncSubmit() {
            await this.form.asyncSubmit();
        },

        onHandleFileUpload(key, event) {
            this.form[key] = '';
            this.form[key] = event.target.files[0];
        },

        // Bulk Action Select all
        onSelectAllBulkAction() {
            this.bulk_action.selectAll();
        },

        // Bulk Action Checkbox checked/ unchecked
        onSelectBulkAction() {
            this.bulk_action.select();
        },

        // Bulk Action use selected Change
        onChangeBulkAction(type) {
            this.bulk_action.change(type);

            if (this.bulk_action.message.length) {
                this.bulk_action.modal=true;
            } else {
                this.onActionBulkAction();
            }
        },

        // Bulk Action use selected Action
        onActionBulkAction() {
            this.bulk_action.action();
        },

        // Bulk Action modal cancel
        onCancelBulkAction() {
            this.bulk_action.modal = false;

            let documentClasses = document.body.classList;
            documentClasses.remove('overflow-y-hidden', 'overflow-overlay');
        },

        // Bulk Action Clear selected items
        onClearBulkAction() {
            this.bulk_action.modal = false;

            this.bulk_action.clear();
        },

        // List Enabled column status changes
        onStatusBulkAction(item_id, event) {
            this.bulk_action.status(item_id, event, this.$notify);
        },

        onDeleteViaConfirmation(delete_id) {
            let action = document.getElementById(delete_id).getAttribute('data-action');
            let title = document.getElementById(delete_id).getAttribute('data-title');
            let message = document.getElementById(delete_id).getAttribute('data-message');
            let button_cancel = document.getElementById(delete_id).getAttribute('data-cancel');
            let button_delete = document.getElementById(delete_id).getAttribute('data-delete');

            this.confirmDelete(action, title, message, button_cancel, button_delete);
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
                    template : '<div id="dynamic-delete-component"><akaunting-modal v-if="confirm.show" :show="confirm.show" :title="confirm.title" :message="confirm.message" :button_cancel="confirm.button_cancel" :button_delete="confirm.button_delete" @confirm="onDelete" @cancel="cancelDelete"></akaunting-modal></div>',

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

                                this.$emit('deleted', response.data);
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
            if (! account_id) {
                return;
            }

            axios.get(url + '/banking/accounts/currency', {
                params: {
                    account_id: account_id
                }
            })
            .then(response => {
                this.currency = response.data;

                this.form.currency_code = response.data.currency_code;
                this.form.currency_rate = response.data.currency_rate;
            })
            .catch(error => {
            });
        },

        // Change currency get money
        onChangeCurrency(currency_code) {
            if (! currency_code) {
                return;
            }

            if (! this.all_currencies.length) {
                let currency_promise = Promise.resolve(window.axios.get((url + '/settings/currencies')));

                currency_promise.then(response => {
                    if (response.data.success) {
                        this.all_currencies = response.data.data;
                    }

                    this.all_currencies.forEach(function (currency, index) {
                        if (currency_code == currency.code) {
                            this.currency = currency;

                            this.form.currency_code = currency.code;
                            this.form.currency_rate = currency.rate;
                        }
                    }, this);
                })
                .catch(error => {
                    this.onChangeCurrency(currency_code);
                });
            } else {
                this.all_currencies.forEach(function (currency, index) {
                    if (currency_code == currency.code) {
                        this.currency = currency;

                        this.form.currency_code = currency.code;
                        this.form.currency_rate = currency.rate;
                    }
                }, this);
            }
        },

        // Pages limit change
        onChangePaginationLimit(event) {
            let path = '';

            let split_href = window.location.href.split('#');
            let href = split_href[0];

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
                            path += 'limit=' + event.target.getAttribute("value");
                        } else {
                            path += query_partials[0] + '=' + query_partials[1];
                        }
                    });

                } else {
                    path = href + '&limit=' + event.target.getAttribute("value");
                }
            } else {
                path = href + '?limit=' + event.target.getAttribute("value");
            }

            if (split_href[1]) {
                path +=  '#' + split_href[1];
            }

            window.location.href = path;
        },

        // Dynamic component get path view and show it.
        onDynamicComponent(path) {
            if (! path) {
                return;
            }

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
                            this.form = new Form('form-dynamic-component');
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

        onDynamicFormParams(path, params) {
            if (! path) {
                return;
            }

            let data = {};

            for (const [key, value] of Object.entries(params)) {
                data[key] = eval(value);
            }

            axios.get(path, {
                params: data
            }).then(response => {
                if (response.data.data) {
                    let rows = response.data.data;

                    if (!Array.isArray(rows)) {
                        for (const [key, value] of Object.entries(rows)) {
                            this.form[key] = value;
                        }
                    } else {
                        rows.forEach(function (key, index) {
                            this.form[index] = key;
                        }, this);
                    }
                }
            }).catch(error => {
            });
        },

        // Delete attachment file
        onDeleteFile(file_id, url, title, message, button_cancel, button_delete) {
            let file_data = {
                page: null,
                key: null,
                value: null,
                ajax: true,
                redirect: window.location.href
            };

            if (this.form['page' +  file_id]) {
                file_data.page = this.form['page' +  file_id];
            }

            if (this.form['key' +  file_id]) {
                file_data.key = this.form['key' +  file_id];
            }

            if (this.form['value' +  file_id]) {
                file_data.value = this.form['value' +  file_id];
            }

            let confirm = {
                url: url,
                title: title,
                message: message,
                button_cancel: button_cancel,
                button_delete: button_delete,
                file_data: file_data,
                show: true
            };

            this.component = Vue.component('add-new-component', (resolve, reject) => {
                resolve({
                    template : '<div id="dynamic-delete-file-component"><akaunting-modal v-if="confirm.show" :show="confirm.show" :title="confirm.title" :message="confirm.message" :button_cancel="confirm.button_cancel" :button_delete="confirm.button_delete" @confirm="onDelete" @cancel="cancelDelete"></akaunting-modal></div>',

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
                                data: file_data
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

        // Change Contact Card set form fields..
        onChangeContactCard(contact) {
            this.form.contact_id = contact.id;
            this.form.contact_name = (contact.title) ? contact.title : (contact.display_name) ? contact.display_name : contact.name;
            this.form.contact_email = (contact.email) ? contact.email : '';
            this.form.contact_tax_number = (contact.tax_number) ? contact.tax_number : '';
            this.form.contact_phone = (contact.phone) ? contact.phone : '';
            this.form.contact_address = (contact.address) ? contact.address : '';
            this.form.contact_country = (contact.country) ? contact.country : '';
            this.form.contact_state = (contact.state) ? contact.state : '';
            this.form.contact_zip_code = (contact.zip_code) ? contact.zip_code : '';
            this.form.contact_city = (contact.city) ? contact.city : '';

            let currency_code = (contact.currency_code) ? contact.currency_code : this.form.currency_code;

            this.onChangeCurrency(currency_code);
        },

        async onAddPayment(url) {
            let payment = {
                modal: false,
                url: url,
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
                        template: '<div id="dynamic-payment-component"><akaunting-modal-add-new modal-dialog-class="max-w-md" modal-position-top :show="payment.modal" @submit="onSubmit" @cancel="onCancel" :buttons="payment.buttons" :title="payment.title" :is_component=true :message="payment.html"></akaunting-modal-add-new></div>',

                        components: {
                            AkauntingDropzoneFileUpload,
                            AkauntingContactCard,
                            AkauntingCompanyEdit,
                            AkauntingEditItemColumns,
                            AkauntingItemButton,
                            AkauntingDocumentButton,
                            AkauntingSearch,
                            AkauntingRadioGroup,
                            AkauntingSelect,
                            AkauntingSelectRemote,
                            AkauntingMoney,
                            AkauntingModal,
                            AkauntingModalAddNew,
                            AkauntingDate,
                            AkauntingRecurring,
                            AkauntingHtmlEditor,
                            AkauntingCountdown,
                            AkauntingCurrencyConversion,
                            AkauntingConnectTransactions,
                            AkauntingSwitch,
                            AkauntingSlider,
                            AkauntingColor,
                            CardForm,
                            [Select.name]: Select,
                            [Option.name]: Option,
                            [Steps.name]: Steps,
                            [Step.name]: Step,
                            [Button.name]: Button,
                            [Link.name]: Link,
                            [Tooltip.name]: Tooltip,
                            [ColorPicker.name]: ColorPicker,
                        },

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

                                documentClasses.remove('overflow-y-hidden', 'overflow-overlay');
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

        async onEditPayment(url) {
            let payment = {
                modal: false,
                url: url,
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
                        template: '<div id="dynamic-payment-component"><akaunting-modal-add-new modal-dialog-class="max-w-md" modal-position-top :show="payment.modal" @submit="onSubmit" @cancel="onCancel" :buttons="payment.buttons" :title="payment.title" :is_component=true :message="payment.html"></akaunting-modal-add-new></div>',

                        components: {
                            AkauntingDropzoneFileUpload,
                            AkauntingContactCard,
                            AkauntingCompanyEdit,
                            AkauntingEditItemColumns,
                            AkauntingItemButton,
                            AkauntingDocumentButton,
                            AkauntingSearch,
                            AkauntingRadioGroup,
                            AkauntingSelect,
                            AkauntingSelectRemote,
                            AkauntingMoney,
                            AkauntingModal,
                            AkauntingModalAddNew,
                            AkauntingDate,
                            AkauntingRecurring,
                            AkauntingHtmlEditor,
                            AkauntingCountdown,
                            AkauntingCurrencyConversion,
                            AkauntingConnectTransactions,
                            AkauntingSwitch,
                            AkauntingSlider,
                            AkauntingColor,
                            CardForm,
                            [Select.name]: Select,
                            [Option.name]: Option,
                            [Steps.name]: Steps,
                            [Step.name]: Step,
                            [Button.name]: Button,
                            [Link.name]: Link,
                            [Tooltip.name]: Tooltip,
                            [ColorPicker.name]: ColorPicker,
                        },

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

                                documentClasses.remove('overflow-y-hidden', 'overflow-overlay');
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

        async onSendEmail(url) {
            let email = {
                modal: false,
                url: url,
                title: '',
                html: '',
                buttons:{}
            };

            let email_promise = Promise.resolve(window.axios.get(email.url));

            if (this.email_template) {
                email_promise = Promise.resolve(window.axios.get(email.url, {
                    params: {
                        email_template: this.email_template
                    }
                }));
            }

            this.email_template = false;

            email_promise.then(response => {
                email.modal = true;
                email.title = response.data.data.title;
                email.html = response.data.html;
                email.buttons = response.data.data.buttons;

                this.component = Vue.component('add-new-component', (resolve, reject) => {
                    resolve({
                        template: '<div id="dynamic-email-component"><akaunting-modal-add-new modal-dialog-class="max-w-screen-md" modal-position-top :show="email.modal" @submit="onSubmit" @cancel="onCancel" :buttons="email.buttons" :title="email.title" :is_component=true :message="email.html"></akaunting-modal-add-new></div>',

                        components: {
                            AkauntingDropzoneFileUpload,
                            AkauntingContactCard,
                            AkauntingCompanyEdit,
                            AkauntingEditItemColumns,
                            AkauntingItemButton,
                            AkauntingDocumentButton,
                            AkauntingSearch,
                            AkauntingRadioGroup,
                            AkauntingSelect,
                            AkauntingSelectRemote,
                            AkauntingMoney,
                            AkauntingModal,
                            AkauntingModalAddNew,
                            AkauntingDate,
                            AkauntingRecurring,
                            AkauntingHtmlEditor,
                            AkauntingCountdown,
                            AkauntingCurrencyConversion,
                            AkauntingConnectTransactions,
                            AkauntingSwitch,
                            AkauntingSlider,
                            AkauntingColor,
                            CardForm,
                            [Select.name]: Select,
                            [Option.name]: Option,
                            [Steps.name]: Steps,
                            [Step.name]: Step,
                            [Button.name]: Button,
                            [Link.name]: Link,
                            [Tooltip.name]: Tooltip,
                            [ColorPicker.name]: ColorPicker,
                        },

                        data: function () {
                            return {
                                form:{},
                                email: email,
                            }
                        },

                        methods: {
                            onSubmit(event) {
                                this.$emit('submit', event);

                                event.submit();
                            },

                            onCancel() {
                                this.email.modal = false;
                                this.email.html = null;

                                let documentClasses = document.body.classList;

                                documentClasses.remove('overflow-y-hidden', 'overflow-overlay');
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

        onShareLink(url) {
            let share = {
                modal: false,
                url: url,
                title: '',
                html: '',
                buttons:{}
            };

            let share_promise = Promise.resolve(window.axios.get(share.url));

            share_promise.then(response => {
                share.modal = true;
                share.title = response.data.data.title;
                share.success_message = response.data.data.success_message;
                share.html = response.data.html;
                share.buttons = response.data.data.buttons;

                this.component = Vue.component('add-new-component', (resolve, reject) => {
                    resolve({
                        template: '<div id="dynamic-share-component"><akaunting-modal-add-new modal-dialog-class="max-w-screen-md" :show="share.modal" @submit="onCopyLink" @cancel="onCancel" :buttons="share.buttons" :is_component=true :title="share.title" :message="share.html"></akaunting-modal-add-new></div>',

                        components: {
                            AkauntingModalAddNew,
                        },

                        data: function () {
                            return {
                                share: share,
                                form: {},
                            }
                        },

                        methods: {
                            onCopyLink() {
                                let type = 'success';
                                let copy_html = document.getElementById('share');

                                copy_html.select();
                                document.execCommand('copy');

                                this.$notify({
                                    verticalAlign: 'bottom',
                                    horizontalAlign: 'left',
                                    message: this.share.success_message,
                                    timeout: 5000,
                                    icon: 'error_outline',
                                    type
                                });

                                this.onCancel();
                            },

                            onCancel() {
                                this.share.modal = false;
                                this.share.html = null;

                                let documentClasses = document.body.classList;

                                documentClasses.remove('overflow-y-hidden', 'overflow-overlay');
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

        onCopyLink() {
            let copy_html = document.getElementById('share');
            let copy_badge = document.querySelector('[data-copied]');

            copy_html.select();
            document.execCommand('copy');

            copy_badge.classList.remove('hidden');
            copy_badge.classList.add('flex');
            copy_html.classList.add('hidden');

            setTimeout(() => {
                copy_badge.classList.add('hidden');
                copy_badge.classList.remove('flex');
                copy_html.classList.remove('hidden');
            }, 800);
        },

        //connect transactions for account, document or etc.
        onConnectTransactions(route) {
            let dial_promise = Promise.resolve(window.axios.get(route));

            dial_promise.then(response => {
                this.connect.show = true;

                this.connect.transaction = JSON.parse(response.data.transaction);

                let currency = JSON.parse(response.data.currency);

                this.connect.currency = {
                    decimal_mark: currency.decimal_mark,
                    precision: currency.precision,
                    symbol: currency.symbol,
                    symbol_first: currency.symbol_first,
                    thousands_separator: currency.thousands_separator,
                };
    
                this.connect.documents = JSON.parse(response.data.documents);
            })
            .catch(error => {
            })
            .finally(function () {
                // always executed
            });
        },

        // if you use modal dynamic form. This method ger url form and posr it.
        onModalAddNew(url) {
            let modal = {
                show: true,
                title: '',
                html: '',
                buttons:{}
            };

            Promise.resolve(window.axios.get(url))
            .then(response => {
                if (response.data.success) {
                    modal.title = response.data.data.title;
                    modal.html = response.data.html;
                    modal.buttons = response.data.data.buttons;

                    this.component = Vue.component('add-new-component', (resolve, reject) => {
                        resolve({
                            template: '<div id="dynamic-add-new-modal-component"><akaunting-modal-add-new modal-dialog-class="max-w-md" :show="modal.show" :buttons="modal.buttons" :title="modal.title" :message="modal.html" :is_component=true @submit="onSubmit" @cancel="onCancel"></akaunting-modal-add-new></div>',

                            components: {
                                AkauntingDropzoneFileUpload,
                                AkauntingContactCard,
                                AkauntingCompanyEdit,
                                AkauntingEditItemColumns,
                                AkauntingItemButton,
                                AkauntingDocumentButton,
                                AkauntingSearch,
                                AkauntingRadioGroup,
                                AkauntingSelect,
                                AkauntingSelectRemote,
                                AkauntingMoney,
                                AkauntingModal,
                                AkauntingModalAddNew,
                                AkauntingDate,
                                AkauntingRecurring,
                                AkauntingHtmlEditor,
                                AkauntingCountdown,
                                AkauntingCurrencyConversion,
                                AkauntingConnectTransactions,
                                AkauntingSwitch,
                                AkauntingSlider,
                                AkauntingColor,
                                CardForm,
                                [Select.name]: Select,
                                [Option.name]: Option,
                                [Steps.name]: Steps,
                                [Step.name]: Step,
                                [Button.name]: Button,
                                [Link.name]: Link,
                                [Tooltip.name]: Tooltip,
                                [ColorPicker.name]: ColorPicker,
                            },

                            data: function () {
                                return {
                                    form:{},
                                    modal: modal,
                                }
                            },

                            methods: {
                                onSubmit(event) {
                                    this.$emit('submit', event);
                                    event.submit();
                                },

                                onCancel() {
                                    this.modal.show = false;
                                    this.modal.html = null;
                                },
                            }
                        })
                    })
                }
            })
            .catch(e => {
                this.errors.push(e);
            })
        },

        //custom input settings for invoice
        onSmallWidthColumn(item) {
            this.$refs[item].$el.setAttribute('custom-half', true);
        },

        onFullWidthColumn(item) {
            this.$refs[item].$el.removeAttribute('custom-half');
        },

        settingsInvoice() {

            if (this.form.item_name == 'custom') {
                this.item_name_input = true;

                this.onSmallWidthColumn("item_name");
            } else {
                this.item_name_input = false;

                this.onFullWidthColumn("item_name");
            }

            if (this.form.price_name == 'custom') {
                this.price_name_input = true;

                this.onSmallWidthColumn("price_name");
            } else {
                this.price_name_input = false;

                this.onFullWidthColumn("price_name");
            }

            if (this.form.quantity_name == 'custom') {
                this.quantity_name_input = true;

                this.onSmallWidthColumn("quantity_name");
            } else {
                this.quantity_name_input = false;

                this.onFullWidthColumn("quantity_name");
            }

            if (this.form.item_name == 'hide' && this.form.hide_item_description === 1) {
                this.form.hide_item_description = 0;

                let type = 'warning';

                if (this.$notifications.state != undefined && this.$notifications.state.length > 0) {
                    this.$notifications.state.forEach((item, index) => {
                        if (item.message == this.form.item_name_or_description_required) {
                            return;
                        }
                    }, this);
                }   

                this.$notify({
                    verticalAlign: 'bottom',
                    horizontalAlign: 'left',
                    message: this.form.message_name_or_description_required,
                    timeout: 8000,
                    icon: 'error_outline',
                    type
                });
            }
        },

        // set minimum date for date component
        setMinDate(date) {
            this.min_date = date;
        },
    }
}
