<template>
    <div :id="'select-item-button-' + _uid" class="product-select">
        <div class="aka-select aka-select--fluid" :class="[{'is-open': show.item_list}]" tabindex="-1">
            <div>
                <button type="button" class="btn btn-link w-100" @click="onItemList">
                    <i class="fas fa-plus-circle"></i> &nbsp; Add an item
                </button>
            </div>

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
                                :ref="'input-contact-field-' + _uid"
                                v-model="search"
                                @input="onInput"
                                @keyup.enter="onInput"
                            />
                        </div>
                    </span>
                </div>

                <ul class="aka-select-menu-options">
                    <div class="aka-select-menu-option" v-for="(item, index) in sortItems" :key="index" @click="onItemSeleted(index, item.id)">
                        <div class="product-select__product">
                            <div class="product-select__product__column product-select__product__info">
                                <b class="product-select__product__info__name"><span>{{ item.name }}</span></b>
                            </div>
                            <div class="product-select__product__column product-select__product__price">
                                {{ item.price }}
                            </div>
                        </div>
                    </div>

                    <div class="aka-select-menu-option" v-if="!sortItems.length">
                        <div>
                            <strong class="text-strong" v-if="!items.length && !search"><span>{{ noDataText }}</span></strong>

                            <strong class="text-strong" v-else><span>{{ noMatchingDataText }}</span></strong>
                        </div>
                    </div>
                </ul>

                <div class="aka-select__footer" tabindex="0" @click="onItemCreate">
                    <span>
                        <i class="fas fa-plus"></i> Create a new item
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
        onItemList() {
            this.show.item_list = true;
        },

        onInput() {
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
                        price: (item.price) ? item.price : (this.type == 'sale') ? item.sale_price : item.purchase_price,
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
            this.show.item_selected = false;
            this.show.item_list = false;
            
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
                    let contact = response.data.data;

                    this.contact_list.push({
                        key: contact.id,
                        value: (contact.title) ? contact.title : (contact.display_name) ? contact.display_name : contact.name,
                        type: (contact.type) ? contact.type : 'customer',
                        id: contact.id,
                        name: (contact.title) ? contact.title : (contact.display_name) ? contact.display_name : contact.name,
                        address: (contact.address) ? contact.address : '' 
                    });

                    this.add_new.show = false;

                    this.add_new.html = '';
                    this.add_new_html = null;

                    this.$emit('new', contact);

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
        // Option set sort_option data
        if (!Array.isArray(this.items)) {
            let index = 0;

            for (const [key, value] of Object.entries(this.items)) {
                this.item_list.push({
                    index: index,
                    key: key,
                    value: value,
                    type: 'item',
                    id: key,
                    name: value,
                    price: 0,
                    tax_ids: [], 
                });

                index++;
            }
        } else {
            this.items.forEach(function (item, index) {
                this.item_list.push({
                    index: index,
                    key: item.id,
                    value: (item.title) ? item.title : (item.display_name) ? item.display_name : item.name,
                    type: this.type,
                    id: item.id,
                    name: (item.title) ? item.title : (item.display_name) ? item.display_name : item.name,
                    price: (item.price) ? item.price : (this.type == 'sale') ? item.sale_price : item.purchase_price,
                    tax_ids: (item.tax_ids) ? item.tax_ids : [],
                });
            }, this);
        }
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

<style>
.aka-select.aka-select--fluid {
    min-width: 160px;
}

.aka-select.aka-select--fluid {
    max-width: 96%;
    min-width: 0;
}

.product-select .aka-select-menu {
    width: 100%;
    min-width: 0;
    padding: 0;
    top: 0;
    overflow: hidden;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    border-top-width: 0;
}

.aka-select-menu {
    list-style: none;
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    text-align: left;
    display: block;
    visibility: hidden;
    position: absolute;
    top: 110%;
    z-index: 1000;
    min-width: 100%;
    padding: 8px 0;
    border-radius: 4px;
    color: #1c252c;
    background-color: white;
    box-shadow: 0 0 0 1px rgba(77,101,117,0.1), 0 3px 10px 0 rgba(77,101,117,0.2);
    -webkit-transform-origin: 0 0;
    transform-origin: 0 0;
    height: 0;
    -webkit-transform: translateY(4px);
    transform: translateY(4px);
    overflow: hidden;
    padding: 0;
    right: 0;
    left: 0;
    top: 100%;
    border-top: 0;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    -webkit-transform: translateY(0);
    transform: translateY(0);
}

.product-select .aka-select.aka-select--fluid.is-open {
    position: absolute;
    display: block;
}

.product-select__product {
    display: flex;
    flex-flow: row nowrap;
}

.product-select__product__column {
    white-space: nowrap;
    overflow: hidden;
}

.product-select__product__price {
    width: 135px;
    text-align: right;
}
.product-select__product__info__description, .product-select__product__info__name {
    width: 710px;
    display: block;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
}

.product-select__product__column {
    white-space: nowrap;
    overflow: hidden;
}
</style>
