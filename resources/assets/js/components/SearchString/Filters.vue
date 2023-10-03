<template>
    <div>
        <slot name="options">
            <div 
                :id="'search-field-option-' + id"
                class="absolute top-12 ltr:left-8 rtl:right-8 py-2 bg-white rounded-md border border-gray-200 shadow-xl z-20 list-none dropdown-menu"
                :class="[{'show': visible.options}]"
            >
                <li 
                    class="w-full flex items-center px-2 h-9 leading-9 whitespace-nowrap"
                    v-for="option in filteredOptions"
                    :data-value="option.key"
                >
                    <button
                        type="button" 
                        class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100"
                        @click="onOptionSelected(option.key)"
                    >{{ option.value }}</button>
                </li>

                <li v-if="search" class="p-2 hover:bg-lilac-900 dropdown-item">
                    <button type="button" class="text-left" @click="onInputConfirm">
                        {{ searchText }}
                    </button>
                </li>
            </div>
        </slot>

        <slot name="operators">
            <div
                :id="'search-field-operator-' + _uid"
                class="absolute top-12 ltr:left-8 rtl:right-8 py-2 bg-white rounded-md border border-gray-200 shadow-xl z-20 list-none dropdown-menu operator"
                :class="[{'show': visible.operator}]"
            >
                <li 
                    class="w-full flex items-center px-2 h-9 leading-9 whitespace-nowrap"
                    v-for="operator in filteredOperators"
                    :data-value="operator.sign"
                >
                    <button 
                        type="button"
                        class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100"
                        @click="onOperatorSelected(operator.sign)"
                    >
                        <span v-if="operator.symbol.icon !== 'undefined'"
                            class="material-icons text-2xl transform pointer-events-none"
                            :class="operator.symbol.class"
                        >
                            {{ operator.symbol.icon }}
                        </span>
                        <img v-else :src="operator.symbol.img" class="w-6 h-6 block m-auto pointer-events-none" />

                        <span class="text-gray hidden pointer-events-none">
                            {{ operator.text }}
                        </span>
                    </button>
                </li>
            </div>
        </slot>

        <slot name="values">
            <div :id="'search-field-value-' + _uid" class="absolute top-12 ltr:left-8 rtl:right-8 py-2 bg-white rounded-md border border-gray-200 shadow-xl z-20 list-none dropdown-menu" :class="[{'show': visible.values}]">
                <li ref="" class="w-full flex items-center px-2 h-9 leading-9 whitespace-nowrap" v-for="(value) in filteredValues" :data-value="value.key">
                    <div v-if="current_operator != '||'" class="w-full flex items-center px-2 h-9 leading-9 whitespace-nowrap">
                        <button type="button" class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100" @click="onValueSelected(value.key)">
                            <i v-if="value.level != null" class="material-icons align-middle text-lg ltr:mr-2 rtl:ml-2 pointer-events-none">subdirectory_arrow_right</i>
                            {{ value.value }}
                        </button>
                    </div>

                    <div v-else class="w-full flex items-center px-2 h-9 leading-9 whitespace-nowrap">
                        <div class="custom-control custom-checkbox flex text-sm hover:bg-lilac-100">
                            <input type="checkbox" name="multiple-filter-values" :id="'search-field-value-' + _uid + '-multiple-' + value.key"  :value="value.key" v-model="multiple_values" data-type="single" class="rounded-sm text-purple border-gray-300 cursor-pointer disabled:bg-gray-200 focus:outline-none focus:ring-transparent">

                            <label :for="'search-field-value-' + _uid + '-multiple-' + value.key" class="w-full h-full flex items-center rounded-md px-2">
                                <i v-if="value.level != null" class="material-icons align-middle text-lg ltr:mr-2 rtl:ml-2 pointer-events-none">subdirectory_arrow_right</i>
                                {{ value.value }}
                            </label>
                        </div>
                    </div>
                </li>

                <li v-if="current_operator == '||' && filteredValues.length" ref="" @click="onMultipleValueSelected()" class="w-full flex items-center px-5 h-9 leading-9 whitespace-nowrap border-t relative text-purple hover:bg-purple hover:text-white hover:rounded-b-md" style="margin-bottom: -8px;">
                    Ok
                </li>

                <li ref="" class="w-full flex items-center px-2 h-9 leading-9 whitespace-nowrap" v-if="!filteredValues.length">
                    <button type="button" class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100">{{ noMatchingDataText }}</button>
                </li>
            </div>
        </slot>
    </div>
</template>

<script>
    export default {
        name: 'filters',

        props: {
            id: {
                type: Number,
                default: 0,
                description: 'Search String _uid',
            },

            filters: {
                type: Array|Object,
                default: [],
                description: 'Get Filter List'
            },

            operators: {
                type: Array|Object,
                default: {
                    'equal': {
                        'enabled': true,
                        'symbol': {
                            'sign': '=',
                            'icon': 'drag_handle',
                        },
                        'text': '',
                    },
                    'not_equal': {
                        'enabled': true,
                        'symbol': {
                            'sign': '=',
                            'img': app_url +  "/public/img/tailwind_icons/not-equal.svg",
                        },
                        'text': '',
                    },
                    'range' : {
                        'enabled': false,
                        'symbol': {
                            'sign': '><',
                            'class': 'transform rotate-90',
                            'icon': 'height',
                        },
                        'text': '',
                    },
                },
                description: 'Get Filter Operator'
            },

            search: {
                type: String,
                default: '',
                description: 'Searcg Field set here',
            },

            searchText: {
                type: String,
                default: 'Search for this text',
                description: 'Input placeholder'
            },
        },

        data() {
            return {
                // Which visible dropdown
                visible: {
                    options: false,
                    operator: false,
                    values: false,
                },

                filter_list: this.filters, // set filters prop assing it.
                filter_operators: this.operators, // set operators prop assing it.

                values,
            };
        },

        methods: {
            onOptionSelected(value) {
                this.current_value = value;
                this.equal = true;
                this.not_equal = true;
                this.multiple = false;
                this.range = false;

                this.onChangeSearchAndFilterText(this.selectPlaceholder, false);

                let option = false;
                let option_url = false;
                let option_fields = {};

                for (let i = 0; i < this.filter_list.length; i++) {
                    if (this.filter_list[i].key != value) {
                        continue;
                    }

                    option = this.filter_list[i].value;
                    option_fields = (this.filter_list[i]['value_option_fields']) ? this.filter_list[i].value_option_fields : {};

                    if (this.filter_list[i].values !== 'undefined' && Object.keys(this.filter_list[i].values).length) {
                        this.option_values[value] = this.convertOption(this.filter_list[i].values);
                    }

                    if (typeof this.filter_list[i].url !== 'undefined' && this.filter_list[i].url) {
                        option_url = this.filter_list[i].url;
                    }

                    if (typeof this.filter_list[i].type !== 'undefined' && this.filter_list[i].type == 'boolean') {
                        this.option_values[value] = this.filter_list[i].values;
                    }

                    if (typeof this.filter_list[i].type !== 'undefined' && this.filter_list[i].multiple !== 'undefined' && this.filter_list[i].multiple) {
                        this.multiple = true;
                    }

                    if (typeof this.filter_list[i].type !== 'undefined' && this.filter_list[i].type == 'date') {
                        this.range = true;
                    }

                    if (typeof this.filter_list[i].operators !== 'undefined' && Object.keys(this.filter_list[i].operators).length) {
                        this.equal = (typeof this.filter_list[i].operators.equal) ? this.filter_list[i].operators.equal : this.equal;
                        this.not_equal = (typeof this.filter_list[i].operators['not_equal']) ? this.filter_list[i].operators['not_equal'] : this.not_equal;
                        this.multiple = (typeof this.filter_list[i].operators['multiple']) ? this.filter_list[i].operators['multiple'] : this.multiple;
                        this.range = (typeof this.filter_list[i].operators['range']) ? this.filter_list[i].operators['range'] : this.range;
                    }

                    this.selected_options.push(this.filter_list[i]);
                    this.filter_list.splice(i, 1);
                }

                // Set filtered select option
                this.filtered.push({
                    option: option,
                    operator: false,
                    value: false
                });

                this.$emit('change', this.filtered);

                this.search = '';

                if (option_url) {
                    if (option_url.indexOf('?') === -1) {
                        option_url += '?search=limit:10';
                    } else {
                        option_url += ' limit:10';
                    }
                }

                if (! this.option_values[value] && option_url) {
                    if (option_url.indexOf('limit') === -1) {
                        option_url += ' limit:10';
                    }

                    window.axios.get(option_url)
                    .then(response => {
                        let data = response.data.data;

                        this.values = [];

                        data.forEach(function (item) {
                            if (Object.keys(option_fields).length) {
                                this.values.push({
                                    key: (option_fields['key']) ? item[option_fields['key']] : (item.code) ? item.code : item.id,
                                    value: (option_fields['value']) ? item[option_fields['value']] : (item.title) ? item.title : (item.display_name) ? item.display_name : item.name,
                                    level: (option_fields['level']) ? item[option_fields['level']] : (item.level) ? item.level : null,
                                });
                            } else {
                                this.values.push({
                                    key: (item.code) ? item.code : item.id,
                                    value: (item.title) ? item.title : (item.display_name) ? item.display_name : item.name,
                                    level: (item.level) ? item.level : null,
                                });
                            }
                        }, this);

                        this.option_values[value] = this.values;
                    })
                    .catch(error => {

                    });
                } else {
                    this.values = (this.option_values[value]) ? this.option_values[value] : [];
                }

                this.$nextTick(() => {
                    this.$refs['input-search-field-' + this._uid].focus();
                });

                this.visible = {
                    options: false,
                    operator: true,
                    values: false,
                };

                this.filter_last_step = 'operator';
            },
        },

        computed: {
            filteredOptions() {
                this.filter_list.sort(function (a, b) {
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

                return this.filter_list.filter(option => {
                    return option.value.toLowerCase().includes(this.search.toLowerCase())
                });
            },

            filteredOperators() {
                return Object.values(this.filter_operators).filter(operator => {
                    return operator.enabled;
                });
            },

            filteredValues() {
                this.values.sort(function (a, b) {
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

                return this.values.filter(value => {
                    return value.value.toLowerCase().includes(this.search.toLowerCase())
                })
            }
        },
    };
</script>

<style>
    .searh-field .dropdown-menu.operator {
        min-width: 50px !important;
    }

    .searh-field .dropdown-menu.operator .btn i:not(:last-child),
    .btn svg:not(:last-child) {
        margin-right: inherit !important;
    }

    .dropdown-menu {
        z-index: 1000;
        display: none;
        min-width: 10rem;
    }

    .dropdown-menu li {
        margin-bottom: 5px;
    }

    .dropdown-menu.operator li {
        margin-bottom: 5px;
    }

    .dropdown-menu li:last-child {
        margin-bottom: 0;
    }

    .dropdown-menu > li > button {
        width: 100%;
    }

    .dropdown-menu.show {
        display: block;
    }
</style>
