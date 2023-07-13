<template>
    <div :id="'select-item-button-' + _uid" class="w-full border-b">
        <button type="button" class="w-full h-10 flex items-center justify-center text-purple font-medium disabled:bg-gray-200 hover:bg-gray-100" @click="showItems">
            <span class="material-icons-outlined text-base font-bold ltr:mr-1 rtl:ml-1">add</span>
             {{ addItemText }}
        </button>
 
        <div :class="[{'is-open': show.item_list}]" tabindex="-1">
            <div class="-mt-10.5 left-0 right-0 bg-white border rounded-lg" v-if="show.item_list">
               <div class="relative">
                   <span class="material-icons-round absolute left-4 top-3 text-lg">search</span>
                   <input 
                       type="text"
                       data-input="true"
                       class="w-full text-sm py-2.5 mt-1 border text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple px-10 border-t-0 border-l-0 border-r-0 border-gray-200 rounded-none"
                       autocapitalize="default" 
                       autocorrect="ON" 
                       :placeholder="placeholder"
                       :value="search"
                        @input="onInput($event)"
                       :ref="'input-item-field-' + _uid"
                       @keydown.enter="inputEnterEvent"
                   />
               </div>

                <div v-bind:class="(sortedItems.length > 7) ? 'h-72 overflow-y-auto' : ''">
                    <ul class="w-full text-sm rounded-lg border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple p-0 mt-0 border-0 cursor-pointer">
                        <div 
                            class="hover:bg-gray-100 px-4" 
                            v-for="(item, index) in sortedItems" 
                            :key="index" 
                            :class="isItemMatched ? 'highlightItem' : ''"
                            @click="onItemSelected(item)"
                        >
                            <div class="w-full flex items-center justify-between">
                                <span>{{ item.name }}</span>

                                <money 
                                    :name="'item-id-' + item.id"
                                    :value="item.price"
                                    v-bind="money"
                                    masked
                                    disabled
                                    class="ltr:text-right rtl:text-left disabled-money text-gray"
                                ></money>
                            </div>
                        </div>

                        <div class="hover:bg-gray-100 text-center py-2 px-4" v-if="!sortedItems.length">
                            <div class="text-center">
                                <span v-if="!items.length && !search">{{ noDataText }}</span>

                                <span v-else>{{ noMatchingDataText }}</span>
                            </div>
                        </div>
                    </ul>
                </div>

                <div class="flex items-center justify-center h-11 text-center text-purple font-bold border border-l-0 border-r-0 border-b-0 rounded-bl-lg rounded-br-lg hover:bg-gray-100 cursor-pointer" @click="onItemCreate">
                     <span class="material-icons text-lg font-bold mr-1">add</span>
                     {{ createNewItemText }}
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
import AkauntingRadioGroup from './AkauntingRadioGroup';
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
            default: 'Add New Item',
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
        searchCharLimit: {
            type: Number,
            default: 3,
            description: "Character limit for item search input"
        }
    },

    data() {
        return {
            item_list: [],
            selected_items: [],
            changeBackground: true,
            search: '', // search column model
            show: {
                item_selected: false,
                item_list: false,
            },
            isItemMatched: false,
            form: {},
            add_new: {
                text: this.addNew.text,
                show: false,
                buttons: this.addNew.buttons,
            },
            newItems: [],
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

    created() {
        this.setItemList(this.items);
    },

    mounted() {
        if (this.dynamicCurrency.code != this.currency.code) {
            if (!this.dynamicCurrency.decimal) {
                this.money = {
                    decimal: this.dynamicCurrency.decimal_mark,
                    thousands: this.dynamicCurrency.thousands_separator,
                    prefix: (this.dynamicCurrency.symbol_first) ? this.dynamicCurrency.symbol : '',
                    suffix: (!this.dynamicCurrency.symbol_first) ? this.dynamicCurrency.symbol : '',
                    precision: parseInt(this.dynamicCurrency.precision),
                    masked: this.masked
                };
            } else {
                this.money = this.dynamicCurrency;
            }
        }
    },

    methods: {
        setItemList(items) {
            this.item_list = [];

            this.search.length === 0 ? this.isItemMatched = false : {}

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

        showItems() {
            this.show.item_list = true;

            setTimeout(function() {
                this.$refs['input-item-field-' + this._uid].focus();
            }.bind(this), 100);
        },

        onInput(event) {
            this.search = event.target.value;

            this.isItemMatched = false;
            //to optimize performance we kept the condition that checks for if search exists or not
            if (!this.search) {
                this.isItemMatched = false; //to remove the style from matched item on input is cleared (option)
                return;
            }

            //condition that checks if input is below the given character limit 
            if (this.search.length < this.searchCharLimit) {
                this.setItemList(this.items); //once the user deletes the search input, we show the overall item list
                this.sortItems(); // we order it as wanted
                this.$emit('input', this.search); // keep the input binded to v-model

                return;
            }

            this.fetchMatchedItems().then(() => this.item_list.length > 0 ? this.isItemMatched = true : this.isItemMatched = false );

            this.$emit('input', this.search);

            this.isItemMatched === true && this.search.length > 0  ? this.isItemMatched = true : this.isItemMatched = true;
        },

        inputEnterEvent() {
            this.isItemMatched 
                ? this.onItemSelected()
                : this.onItemCreate()
        },

        async fetchMatchedItems() {
            await window.axios.get(url + '/common/items?search="' + this.search + '" not ' + this.price + ':NULL enabled:1 limit:10')
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
                .catch(error => {});
        },

        onItemSelected(clickSelectedItem) {
            let item; 
            const firstMatchedItem = this.item_list[0];
            const isClickSelectedItem = clickSelectedItem ? true : false;
            isClickSelectedItem ? item = clickSelectedItem  : item = firstMatchedItem;
            const indexeditem = { ...item, index: this.currentIndex };

            this.addItem(indexeditem, 'oldItem');
            this.changeBackground = false;
        },

        addItem(item, itemType) {
            this.selected_items.push(item);

            this.$emit('item',  { item, itemType } );
            this.$emit('items', this.selected_items);

            this.show.item_selected = false;
            this.show.item_list = false;

            this.search = '';

            // Set default item list
            this.setItemList(this.items);
        },

        onItemCreate() {
            let item = {
                index: this.currentIndex,
                key: 0,
                value: this.search,
                type: this.type,
                id: 0,
                name: this.search,
                description: '',
                price: 0,
                tax_ids: [],
            };
            
            this.newItems.push(item);

            this.addItem(item, 'newItem');
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

                    documentClasses.remove('overflow-y-hidden', 'overflow-overlay');
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

            documentClasses.remove('overflow-y-hidden', 'overflow-overlay');
        },

        closeIfClickedOutside(event) {
            if (!document.getElementById('select-item-button-' + this._uid).contains(event.target)) {
                this.show.item_selected = false;
                this.show.item_list = false;
                this.search = '';

                document.removeEventListener('click', this.closeIfClickedOutside);

                this.setItemList(this.items);
            }
        },

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

            const sortedItemList = this.item_list.filter(item => 
                item.value.toLowerCase().includes(this.search.toLowerCase())
            );

            return sortedItemList;
        },
    },

    computed: {
        sortedItems() {
            return this.sortItems();
        },
        currentIndex() {
            return this.$root.form.items.length;
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

<style scoped>
    .highlightItem:first-child {
        background-color: #F5F7FA;    
    }
</style>
