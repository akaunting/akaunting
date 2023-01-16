<template>
    <div :id="'select-item-button-' + _uid" class="w-full border-b">
        <button type="button" class="w-full h-10 flex items-center justify-center text-purple font-medium disabled:bg-gray-200 hover:bg-gray-100" @click="showItems">
            <span class="material-icons-outlined text-base font-bold ltr:mr-1 rtl:ml-1">
                add
            </span>
            {{ addItemText }}
        </button>
 
        <div :class="[{'is-open': show.item_list}]" tabindex="-1">
            <div class="-mt-10.5 left-0 right-0 bg-white border rounded-lg" v-if="show.item_list">
                <div class="relative">
                    <span class="material-icons-round absolute left-4 top-3 text-lg">search</span>
                    <input 
                        type="text"
                        data-input="true"
                        class="w-full text-sm px-10 py-2.5 mt-1rounded-none border border-gray-200 border-t-0 border-l-0 border-r-0 text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"
                        autocapitalize="default" 
                        autocorrect="ON" 
                        :placeholder="placeholder"
                        :value="search"
                        @input="onInput($event)"
                        :ref="'input-item-field-' + _uid"
                    />
                </div>

                <ul class="w-full text-sm p-0 mt-0 rounded-lg border-0 border-light-gray text-black placeholder-light-gray bg-white cursor-pointer disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple">
                    <div 
                        class="hover:bg-gray-100 px-4" 
                        v-for="(item, index) in sortedItems" 
                        :key="index" 
                        @click="onItemSelected(item)"
                    >
                        <div class="w-full flex items-center justify-between">
                            <span class="w-9/12">{{ item.name }}</span>

                            <money 
                                :name="'item-id-' + item.id"
                                :value="item.amount"
                                v-bind="money"
                                masked
                                disabled
                                class="w-1/12 text-right disabled-money text-gray"
                            ></money>
                            -
                            <money 
                                :name="'item-id-' + item.id"
                                :value="item.paid"
                                v-bind="money"
                                masked
                                disabled
                                class="w-1/12 text-right disabled-money text-gray"
                            ></money>
                            =
                            <money 
                                :name="'item-id-' + item.id"
                                :value="item.open"
                                v-bind="money"
                                masked
                                disabled
                                class="w-1/12 text-right disabled-money text-gray"
                            ></money>
                        </div>
                    </div>

                    <div class="hover:bg-gray-100 text-center py-2 px-4" v-if="!sortedItems.length">
                        <div class="text-center">
                            <span>{{ noDataText }}</span>
                        </div>
                    </div>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from 'vue';

import {Money} from 'v-money';

export default {
    name: 'akaunting-document-button',

    components: {
        Money,
    },

    props: {
        placeholder: {
            type: String,
            default: 'Type an item name',
            description: 'Input placeholder'
        },
        items: {
            type: Array,
            default: () => [],
            description: 'List of Items'
        },
        selectedItems: {
            type: Array,
            default: () => [],
            description: 'List of Selected Items'
        },
        addItemText: {
            type: String,
            default: 'Add an item',
            description: ""
        },
        noDataText: {
            type: String,
            default: 'No Data',
            description: "Selectbox empty options message"
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
            search: '', // search column model
            show: {
                item_list: false,
            },
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

            // Option set sort_option data
            if (Array.isArray(items)) {
                items.forEach(function (item, index) {
                    let selected = this.selectedItems.find(function (selected_item) {
                        return selected_item.id === item.id;
                    }, this);

                    if (selected) {
                        return;
                    }

                    this.item_list.push({
                        id: item.id,
                        name: item.document_number + ' | ' + item.contact_name + (item.notes ? ' | ' + item.notes : ''),
                        amount: item.amount,
                        paid: item.paid,
                        open: item.amount - item.paid,
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

            //to optimize performance we kept the condition that checks for if search exists or not
            if (!this.search) {
                return;
            }

            //condition that checks if input is below the given character limit 
            this.setItemList(this.items); //once the user deletes the search input, we show the overall item list
            this.sortItems(); // we order it as wanted
            this.$emit('input', this.search); // keep the input binded to v-model
        },

        onItemSelected(param_item) {
            let selected_item = this.items.find(function (item) {
                return item.id === param_item.id
            }, this);

            this.$emit('document-selected', selected_item);

            this.show.item_list = false;

            this.search = '';

            // Set default item list
            this.setItemList(this.items);
        },

        closeIfClickedOutside(event) {
            if (!document.getElementById('select-item-button-' + this._uid).contains(event.target)) {
                this.show.item_list = false;
                this.search = '';

                document.removeEventListener('click', this.closeIfClickedOutside);

                this.setItemList(this.items);
            }
        },

        sortItems() {
            this.item_list.sort(function (a, b) {
                var nameA = a.name.toUpperCase(); // ignore upper and lowercase
                var nameB = b.name.toUpperCase(); // ignore upper and lowercase

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
                item.name.toLowerCase().includes(this.search.toLowerCase())
            );

            return sortedItemList;
        },
    },

    computed: {
        sortedItems() {
            return this.sortItems();
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
        },

        items: function (items) {
            this.setItemList(items);
        },

        selectedItems: function () {
            this.setItemList(this.items);
        }
    },
};
</script>
