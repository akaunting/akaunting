<template>
    <div :id="'select-item-button-' + _uid" class="product-select">
        <div class="item-add-new">
            <button type="button" class="btn btn-link w-100" @click="onItemList">
                <i class="fas fa-plus-circle"></i> &nbsp; {{ addItemText }}
            </button>
        </div>
 
        <div class="aka-select aka-select--fluid" :class="[{'is-open': show.item_list}]" tabindex="-1">
            <div class="aka-select-menu" v-if="show.item_list">
                <div class="aka-select-search-container">
                    <span class="aka-prefixed-input aka-prefixed-input--fluid">
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>

                            <input 
                                type="text"
                                data-input="true"
                                class="form-control"
                                autocapitalize="default" autocorrect="ON" 
                                :placeholder="placeholder"
                                :ref="'input-item-field-' + _uid"
                                v-model="search"
                                @input="onInput"
                                @keyup.enter="onInput"
                            />
                        </div>
                    </span>
                </div>

                <ul class="aka-select-menu-options">
                    <div class="aka-select-menu-option" v-for="(item, index) in sortItems" :key="index" @click="onItemSeleted(index, item.id)">
                        <div class="item-select w-100">
                            <div class="item-select-column item-select-info w-75">
                                <b class="item-select-info-name"><span>{{ item.name }}</span></b>
                            </div>

                            <div class="item-select-column item-select-price w-25">
                                <money 
                                    :name="'item-id-' + item.id"
                                    :value="item.price"
                                    v-bind="money"
                                    masked
                                    disabled
                                    class="text-right disabled-money"
                                ></money>
                            </div>
                        </div>
                    </div>

                    <div class="aka-select-menu-option" v-if="!sortItems.length">
                        <div>
                            <strong class="text-strong" v-if="!items.length && !search">
                                <span>{{ noDataText }}</span>
                            </strong>

                            <strong class="text-strong" v-else>
                                <span>{{ noMatchingDataText }}</span>
                            </strong>
                        </div>
                    </div>
                </ul>

                <div class="aka-select-footer" @click="onItemCreate">
                    <span>
                        <i class="fas fa-plus"></i> &nbsp; {{ createNewItemText }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from 'vue';

import { Select, Option, OptionGroup, ColorPicker } from 'element-ui';

import {Money} from 'v-money';
import AkauntingModalAddNew from './AkauntingModalAddNew';
import AkauntingModal from './AkauntingModal';
import AkauntingMoney from './AkauntingMoney';
import AkauntingRadioGroup from './forms/AkauntingRadioGroup';
import AkauntingSelect from './AkauntingSelect';
import AkauntingDate from './AkauntingDate';

import Form from './../plugins/form';

export default {
    name: 'akaunting-item-button',

    components: {
        [Select.name]: Select,
        [Option.name]: Option,
        [OptionGroup.name]: OptionGroup,
        [ColorPicker.name]: ColorPicker,
        AkauntingModalAddNew,
        AkauntingModal,
        AkauntingMoney,
        AkauntingRadioGroup,
        AkauntingSelect,
        AkauntingDate,
        Money,
    },

    props: {
        placeholder: {
            type: String,
            default: 'Type an item name',
            description: 'Input placeholder'
        },
        type: {
            type: String,
            default: 'sale',
            description: 'Show item price'
        },
        price: {
            type: String,
            default: 'sale_price',
            description: 'Show item price'
        },
        items: {
            type: Array,
            default: () => [],
            description: 'List of Items'
        },

        addNew: {
            type: Object,
            default: function () {
                return {
                    text: 'Add New Item',
                    status: false,
                    new_text: 'New',
                    buttons: {}
                };
            },
            description: "Selectbox Add New Item Feature"
        },
        addItemText: {
            type: String,
            default: 'Add an item',
            description: ""
        },
        createNewItemText: {
            type: String,
            default: 'Create a new item',
            description: ""
        },
        noDataText: {
            type: String,
            default: 'No Data',
            description: "Selectbox empty options message"
        },
        noMatchingDataText: {
            type: String,
            default: 'No Matchign Data',
            description: "Selectbox search option not found item message"
        },
        dynamicCurrency: {
            type: Object,
            default: function () {
                return {
                    decimal_mark: '.',
                    thousands_separator: ',',
                    symbol_first: 1,
                    symbol: '$',
                    precision: 2,
                };
            },
            description: "Dynamic currency"
        },
        currency: {
            type: Object,
            default: function () {
                return {
                    decimal_mark: '.',
                    thousands_separator: ',',
                    symbol_first: 1,
                    symbol: '$',
                    precision: 2,
                };
            },
            description: "Default currency"
        },
    },

    data() {
        return {
            item_list: [],
            selected_items: [],
            search: '', // search cloumn model
            show: {
                item_selected: false,
                item_list: false,
            },

            form: {},
            add_new: {
                text: this.addNew.text,
                show: false,
                buttons: this.addNew.buttons,
            },
            add_new_html: '',
            money: {
                decimal: this.currency.decimal_mark,
                thousands: this.currency.thousands_separator,
                prefix: (this.currency.symbol_first) ? this.currency.symbol : '',
                suffix: (!this.currency.symbol_first) ? this.currency.symbol : '',
                precision: parseInt(this.currency.precision),
                masked: this.masked
            }
        };
    },

    methods: {
        setItemList(items) {
            // Option set sort_option data
            if (!Array.isArray(items)) {
                let index = 0;

                for (const [key, value] of Object.entries(items)) {
                    this.item_list.push({
                        index: index,
                        key: key,
                        value: value,
                        type: 'item',
                        id: key,
                        name: value,
                        description: '',
                        price: 0,
                        tax_ids: [], 
                    });

                    index++;
                }
            } else {
                items.forEach(function (item, index) {
                    this.item_list.push({
                        index: index,
                        key: item.id,
                        value: (item.title) ? item.title : (item.display_name) ? item.display_name : item.name,
                        type: this.type,
                        id: item.id,
                        name: (item.title) ? item.title : (item.display_name) ? item.display_name : item.name,
                        description: (item.description) ? item.description : '',
                        price: (item.price) ? item.price : (this.price == 'purchase_price') ? item.purchase_price : item.sale_price,
                        tax_ids: (item.tax_ids) ? item.tax_ids : [],
                    });
                }, this);
            }
        },

        onItemList() {
            this.show.item_list = true;

            setTimeout(function() {
                this.$refs['input-item-field-' + this._uid].focus();
            }.bind(this), 100);
        },

        onInput() {
            if (!this.search) {
                return;
            }

            window.axios.get(url + '/common/items?search="' + this.search + '" limit:10')
            .then(response => {
                this.item_list = [];

                let items = response.data.data;

                items.forEach(function (item, index) {
                    this.item_list.push({
                        index: index,
                        key: item.id,
                        value: (item.title) ? item.title : (item.display_name) ? item.display_name : item.name,
                        type: this.type,
                        id: item.id,
                        name: (item.title) ? item.title : (item.display_name) ? item.display_name : item.name,
                        description: (item.description) ? item.description : '',
                        price: (item.price) ? item.price : (this.price == 'purchase_price') ? item.purchase_price : item.sale_price,
                        tax_ids: (item.tax_ids) ? item.tax_ids : [],
                    });
                }, this);
            })
            .catch(error => {

            });

            this.$emit('input', this.search);
        },

        onItemSeleted(index, item_id) {
            let item = this.item_list[index];

            this.selected_items.push(item);

            this.$emit('item', item);
            this.$emit('items', this.selected_items);

            this.show.item_selected = false;
            this.show.item_list = false;
            this.search = '';
        },

        onItemCreate() {
            let index = Object.keys(this.item_list).length;
            index++;

            let item = {
                index: index,
                key: 0,
                value: this.search,
                type: this.type,
                id: 0,
                name: this.search,
                description: '',
                price: 0,
                tax_ids: [],
            };

            this.selected_items.push(item);

            this.$emit('item', item);
            this.$emit('items', this.selected_items);

            this.setItemList(this.items);

            this.show.item_selected = false;
            this.show.item_list = false;
            this.search = '';

            /*
            let add_new = this.add_new;

            window.axios.get(this.createRoute)
            .then(response => {
                add_new.show = true;
                add_new.html = response.data.html;

                this.add_new_html = Vue.component('add-new-component', function (resolve, reject) {
                    resolve({
                        template: '<div><akaunting-modal-add-new :show="add_new.show" @submit="onSubmit" @cancel="onCancel" :buttons="add_new.buttons" :title="add_new.text" :is_component=true :message="add_new.html"></akaunting-modal-add-new></div>',

                        components: {
                            [Select.name]: Select,
                            [Option.name]: Option,
                            [OptionGroup.name]: OptionGroup,
                            [ColorPicker.name]: ColorPicker,
                            AkauntingModalAddNew,
                            AkauntingModal,
                            AkauntingMoney,
                            AkauntingRadioGroup,
                            AkauntingSelect,
                            AkauntingDate,
                        },

                        data: function () {
                            return {
                                add_new: add_new,
                            }
                        },

                        methods: {
                            onSubmit(event) {
                                this.$emit('submit', event);
                            },

                            onCancel(event) {
                                this.$emit('cancel', event);
                            }
                        }
                    })
                });
            })
            .catch(e => {
                console.log(e);
            })
            .finally(function () {
                // always executed
            });
            */
        },

        onSubmit(event) {
            this.form = event;

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
                this.form.loading = false;

                if (response.data.success) {
                    let item = response.data.data;

                    this.item_list.push({
                        index: index,
                        key: item.id,
                        value: (item.title) ? item.title : (item.display_name) ? item.display_name : item.name,
                        type: this.type,
                        id: item.id,
                        name: (item.title) ? item.title : (item.display_name) ? item.display_name : item.name,
                        description: (item.description) ? item.description : '',
                        price: (item.price) ? item.price : (this.price == 'purchase_price') ? item.purchase_price : item.sale_price,
                        tax_ids: (item.tax_ids) ? item.tax_ids : [],
                    });

                    this.add_new.show = false;

                    this.add_new.html = '';
                    this.add_new_html = null;

                    this.$emit('new', item);

                    let documentClasses = document.body.classList;

                    documentClasses.remove("modal-open");
                }
            })
            .catch(error => {
                this.form.loading = false;

                this.form.onFail(error);

                this.method_show_html = error.message;
            });
        },

        onCancel() {
            this.add_new.show = false;
            this.add_new.html = null;
            this.add_new_html = null;

            let documentClasses = document.body.classList;

            documentClasses.remove("modal-open");
        },

        closeIfClickedOutside(event) {
            if (!document.getElementById('select-item-button-' + this._uid).contains(event.target)) {
                this.show.item_selected = false;
                this.show.item_list = false;
                this.search = '';

                document.removeEventListener('click', this.closeIfClickedOutside);
            }
        },
    },

    created() {
        this.setItemList(this.items);
    },

    computed: {
        sortItems() {
            this.item_list.sort(function (a, b) {
                var nameA = a.value.toUpperCase(); // ignore upper and lowercase
                var nameB = b.value.toUpperCase(); // ignore upper and lowercase

                if (nameA < nameB) {
                    return -1;
                }

                if (nameA > nameB) {
                    return 1;
                }

                // names must be equal
                return 0;
            });

            return this.item_list.filter(item => {
                return item.value.toLowerCase().includes(this.search.toLowerCase())
            });
        },
    },

    watch: {
        dynamicCurrency: function (currency) {
            if (!currency) {
                return;
            }

            this.money = {
                decimal: currency.decimal_mark,
                thousands: currency.thousands_separator,
                prefix: (currency.symbol_first) ? currency.symbol : '',
                suffix: (!currency.symbol_first) ? currency.symbol : '',
                precision: parseInt(currency.precision),
                masked: this.masked
            };
        },

        show: {
            handler: function(newValue) {
                if (newValue) {
                    document.addEventListener('click', this.closeIfClickedOutside);
                }
            },
            deep: true
        }
    },
};
</script>
