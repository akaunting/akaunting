<template>
    <div
        :id="'search-field-' + _uid"
        class="h-12 my-5 searh-field flex border-b transition-all js-search"
        :class="input_focus ? 'border-gray-500' : 'border-gray-300'"
    >
        <div class="tags-group group items-center" style="display:contents;" v-for="(filter, index) in filtered" :index="index">
            <span v-if="filter.option" class="flex items-center bg-purple-lighter text-black border-0 mt-3 px-3 py-4 text-sm cursor-pointer el-tag el-tag--small el-tag-option">
                {{ filter.option }}

                <i v-if="!filter.operator && !filter.value" class="mt-1 ltr:-right-2 rtl:left-0 rtl:right-0 el-tag__close el-icon-close" style="font-size: 16px;" @click="onFilterDelete(index)"></i>
            </span>

            <span v-if="filter.operator" class="flex items-center bg-purple-lighter text-black border-2 border-body border-l border-r border-t-0 border-b-0 mt-3 px-3 py-4 text-sm cursor-pointer el-tag el-tag--small el-tag-operator" style="margin-left:0; margin-right:0;">
                <span v-if="filter.operator == '='" class="material-icons text-2xl">drag_handle</span>
                <span v-else-if="filter.operator == '><'" class="material-icons text-2xl transform rotate-90">height</span>

                <img v-else :src="equal_image" class="w-5 h-5 object-cover block" />

                <i v-if="!filter.value" class="mt-1 ltr:-right-2 rtl:left-0 rtl:right-0 el-tag__close el-icon-close " style="font-size: 16px;" @click="onFilterDelete(index)"></i>
            </span>

            <span v-if="filter.value" class="flex items-center bg-purple-lighter text-black border-0 mt-3 px-3 py-4 text-sm cursor-pointer el-tag el-tag--small  el-tag-value">
                {{ filter.value }}

                <i class="mt-1 ltr:-right-2 rtl:left-0 rtl:right-0 el-tag__close el-icon-close " style="font-size: 16px;" @click="onFilterDelete(index)"></i>
            </span>
        </div>

        <div class="relative w-full h-full flex">
            <input
            v-if="!show_date"
            type="text"
            class="w-full bg-transparent text-black text-sm border-0 px-10 pb-0 focus:outline-none focus:ring-transparent focus:border-purple-100"
            :class="[{'px-4' : !show_icon}]"
            :placeholder="placeholder"
            :ref="'input-search-field-' + _uid"
            v-model="search"
            @focus="onInputFocus"
            @input="onInput"
            @blur="onBlur"
            @keyup.enter="onInputConfirm"
            />

            <flat-picker
                v-else
                @on-open="onInputFocus"
                @blur="onBlur"
                :config="dateConfig"
                class="w-full bg-transparent text-black text-sm border-0 px-10 pb-0 focus:outline-none focus:ring-transparent focus:border-purple-100 datepicker"
                :placeholder="placeholder"
                :ref="'input-search-date-field-' + _uid"
                value=""
                @focus="onInputFocus"
                @on-close="onInputDateSelected"
                @keyup.enter="onInputConfirm"
            >
            </flat-picker>
                <span class="material-icons absolute bottom-1 ltr:left-3 rtl:right-3 text-lg text-black" style="z-index:-1;">search</span>

            <button type="button" class="absolute ltr:right-0 rtl:left-0 top-2 clear" v-if="show_close_icon" @click="onSearchAndFilterClear">
                <span class="material-icons text-sm">close</span>
            </button>

            <div :id="'search-field-option-' + _uid" class="absolute top-12 ltr:left-8 rtl:right-8 py-2 bg-white rounded-md border border-gray-200 shadow-xl z-20 list-none dropdown-menu" :class="[{'show': visible.options}]">
                <li ref="" class="w-full flex items-center px-2 h-9 leading-9 whitespace-nowrap" v-for="option in filteredOptions" :data-value="option.key">
                    <button type="button" class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100" data-btn="btn btn-link" @click="onOptionSelected(option.key)">{{ option.value }}</button>
                </li>

                <li ref="" v-if="search" class="p-2 hover:bg-lilac-900 dropdown-item">
                    <button type="button" class="text-left" @click="onInputConfirm">{{ searchText }}</button>
                </li>
            </div>

            <div :id="'search-field-operator-' + _uid" class="absolute top-12 ltr:left-8 rtl:right-8 py-2 bg-white rounded-md border border-gray-200 shadow-xl z-20 list-none dropdown-menu operator" :class="[{'show': visible.operator}]">
                <li ref="" class="w-full flex items-center px-2 h-9 leading-9 whitespace-nowrap">
                    <button type="button" class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100" @click="onOperatorSelected('=')">
                        <span class="material-icons text-2xl transform">drag_handle</span>
                        <span class="text-gray hidden">{{ operatorIsText }}
                        </span>
                    </button>
                </li>

                <li ref="" class="w-full flex items-center px-2 h-9 leading-9 whitespace-nowrap">
                    <button type="button" class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100" @click="onOperatorSelected('!=')">
                        <img :src="equal_image" class="w-6 h-6 block m-auto" />
                        <span class="text-gray hidden">{{ operatorIsNotText }}</span>
                    </button>
                </li>

                <li v-if="range" ref="" class="w-full flex items-center px-2 h-9 leading-9 whitespace-nowrap">
                    <button type="button" class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100" @click="onOperatorSelected('><')">
                        <span class="material-icons text-2xl transform rotate-90">height</span>
                        <span class="text-gray hidden">{{ operatorIsNotText }}</span>
                    </button>
                </li>
            </div>

            <div :id="'search-field-value-' + _uid" class="absolute top-12 ltr:left-8 rtl:right-8 py-2 bg-white rounded-md border border-gray-200 shadow-xl z-20 list-none dropdown-menu" :class="[{'show': visible.values}]">
                <li ref="" class="w-full flex items-center px-2 h-9 leading-9 whitespace-nowrap" v-for="(value) in filteredValues" :data-value="value.key">
                    <button type="button" class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100" @click="onValueSelected(value.key)">{{ value.value }}</button>
                </li>

                <li ref="" class="w-full flex items-center px-2 h-9 leading-9 whitespace-nowrap" v-if="!filteredValues.length">
                    <button type="button" class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100">{{ noMatchingDataText }}</button>
                </li>
            </div>
        </div>
    </div>
</template>

<script>
import flatPicker from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";

export default {
    name: 'akaunting-search',

    components: {
        flatPicker
    },

    props: {
        placeholder: {
            type: String,
            default: 'Search or filter results...',
            description: 'Input placeholder'
        },
        searchText: {
            type: String,
            default: 'Search for this text',
            description: 'Input placeholder'
        },
        operatorIsText: {
            type: String,
            default: 'is',
            description: 'Operator is "="'
        },
        operatorIsNotText: {
            type: String,
            default: 'is not',
            description: 'Operator is not "!="'
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
        value: {
            type: String,
            default: null,
            description: 'Search attribute value'
        },
        filters: {
            type: Array,
            default: () => [],
            description: 'List of filters'
        },
        defaultFiltered: {
            type: Array,
            default: () => [],
            description: 'List of filters'
        },

        dateConfig: null

    },

    model: {
        prop: 'value',
        event: 'change'
    },

    data() {
        return {
            filter_list: this.filters, // set filters prop assing it.
            search: '', // search cloumn model
            filtered:[], // Show selected filters
            filter_index: 0, // added filter count
            filter_last_step: 'options', // last fitler step
            visible: { // Which visible dropdown
                options: false,
                operator: false,
                values: false,
            },

            range: false,
            option_values: [],
            selected_options: [],
            selected_operator: [],
            selected_values: [],
            values: [],
            current_value: null,
            show_date: false,
            show_close_icon: false,
            show_icon: true,
            equal_image: app_url +  "/public/img/tailwind_icons/not-equal.svg",
            input_focus: false
        };
    },

    methods: {
        onInputFocus() {
            if (!this.filter_list.length) {
                return;
            }

            if (this.filter_last_step != 'values' || (this.filter_last_step == 'values' && this.selected_options[this.filter_index].type != 'date')) {
                this.visible[this.filter_last_step] = true;

                this.$nextTick(() => {
                    this.$refs['input-search-field-' + this._uid].focus();
                });
            } else {
                this.show_date = true;

                this.$nextTick(() => {
                    this.$refs['input-search-date-field-' + this._uid].fp.open();
                });
            }

            this.input_focus = true;
        },

        onBlur() {
            this.input_focus = false;
        },

        onInputDateSelected(selectedDates, dateStr, instance) {
            this.filtered[this.filter_index].value = dateStr;

            let date = instance.formatDate(selectedDates[0], 'Y-m-d');

            if (selectedDates.length > 1) {
                let dates = [];

                selectedDates.forEach(function (item) {
                    dates.push(instance.formatDate(item, 'Y-m-d'));
                }, this);

                date = dates.join('-to-');
            }

            this.selected_values.push({
                key: date,
                value: dateStr,
            });

            this.$emit('change', this.filtered);

            this.show_date = false;
            this.search = '';

            this.$nextTick(() => {
                this.$refs['input-search-field-' + this._uid].focus();
            });

            this.filter_index++;

            if (this.filter_list.length) {
                this.visible = {
                    options: true,
                    operator: false,
                    values: false,
                };
            } else {
                this.visible = {
                    options: false,
                    operator: false,
                    values: false,
                };
            }

            this.filter_last_step = 'options';
        },

        onInput(evt) {
            this.search = evt.target.value;

            let option_url = this.selected_options[this.filter_index].url;

            if (this.search) {
                if (option_url.indexOf('?') === -1) {
                    option_url += '?search="' + this.search + '" limit:10';
                } else {
                    if (option_url.indexOf('search=') === -1) {
                        option_url += '&search="' + this.search + '" limit:10';
                    } else {
                        option_url += ' "' + this.search + '" limit:10';
                    }
                }
            }

            if (option_url) {
                if (option_url.indexOf('limit') === -1) {
                    option_url += ' limit:10';
                }

                window.axios.get(option_url)
                .then(response => {
                    this.values = [];

                    let data = response.data.data;

                    data.forEach(function (item) {
                        this.values.push({
                            key: item.id,
                            value: item.name
                        });
                    }, this);

                    this.option_values[value] = this.values;
                })
                .catch(error => {

                });
            }

            this.$emit('input', evt.target.value);
        },

        onInputConfirm() {
            let path = window.location.href.replace(window.location.search, '');
            let args = '';

            if (this.search) {
                args += '?search="' + this.search + '" ';
            }

            let now = new Date();
            now.setTime(now.getTime() + 1 * 3600 * 1000);
            let expires = now.toUTCString();

            let search_string = {};
            search_string[path] = {};

            this.filtered.forEach(function (filter, index) {
                if (!args) {
                    args += '?search=';
                }

                if (this.selected_operator[index].key == '!=') {
                    args += 'not ';
                }

                if (this.selected_operator[index].key == '><') {
                    let dates = this.selected_values[index].key.split('-to-');

                    args += this.selected_options[index].key + '>=' + dates[0] + ' ';
                    args += this.selected_options[index].key + '<=' + dates[1] + ' ';
                } else {
                    args += this.selected_options[index].key + ':' + this.selected_values[index].key + ' ';
                }

                search_string[path][this.selected_options[index].key] = {
                    'key': this.selected_values[index].key,
                    'value': this.selected_values[index].value,
                    'operator': this.selected_operator[index].key,
                };
            }, this);

            Cookies.set('search-string', search_string, expires);

            window.location = path + args;
        },

        onOptionSelected(value) {
            this.show_icon = false;
            this.current_value = value;
            this.range = false;

            let option = false;
            let option_url = false;

            for (let i = 0; i < this.filter_list.length; i++) {
                if (this.filter_list[i].key == value) {
                    option = this.filter_list[i].value;

                    if (this.filter_list[i].values !== 'undefined' && Object.keys(this.filter_list[i].values).length) {
                        this.option_values[value] = this.convertOption(this.filter_list[i].values);
                    }

                    if (typeof this.filter_list[i].url !== 'undefined' && this.filter_list[i].url) {
                        option_url = this.filter_list[i].url;
                    }

                    if (typeof this.filter_list[i].type !== 'undefined' && this.filter_list[i].type == 'boolean') {
                        this.option_values[value] = this.filter_list[i].values;
                    }

                    if (typeof this.filter_list[i].type !== 'undefined' && this.filter_list[i].type == 'date') {
                        this.range = true;
                    }

                    this.selected_options.push(this.filter_list[i]);
                    this.filter_list.splice(i, 1);
                    break;
                }
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

            if (!this.option_values[value] && option_url) {
                if (option_url.indexOf('limit') === -1) {
                    option_url += ' limit:10';
                }

                window.axios.get(option_url)
                .then(response => {
                    let data = response.data.data;

                    this.values = [];

                    data.forEach(function (item) {
                        this.values.push({
                            key: (item.code) ? item.code : item.id,
                            value: (item.title) ? item.title : (item.display_name) ? item.display_name : item.name
                        });
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

        onOperatorSelected(value) {
            this.filtered[this.filter_index].operator = value;

            this.$emit('change', this.filtered);

            if (this.selected_options[this.filter_index].type != 'date') {
                this.$nextTick(() => {
                    this.$refs['input-search-field-' + this._uid].focus();
                });

                this.visible = {
                    options: false,
                    operator: false,
                    values: true,
                };
            } else {
                this.show_date = true;

                this.$nextTick(() => {
                    let mode = this.selected_operator[this.filter_index].key == '><' ? 'range' : 'single';

                    this.$refs['input-search-date-field-' + this._uid].fp.set('mode', mode);
                    this.$refs['input-search-date-field-' + this._uid].fp.open();
                });

                this.visible = {
                    options: false,
                    operator: false,
                    values: false,
                };
            }

            this.selected_operator.push({
                key: value
            });

            this.filter_last_step = 'values';
        },

        onValueSelected(value) {
            this.show_close_icon = true;
            let select_value = false;

            for (let i = 0; i < this.values.length; i++) {
                if (this.values[i].key == value) {
                    select_value = this.values[i].value;

                    this.selected_values.push(this.values[i]);
                    this.option_values[this.current_value].splice(i, 1);
                    break;
                }
            }

            this.filtered[this.filter_index].value = select_value;

            this.$emit('change', this.filtered);

            this.$nextTick(() => {
                this.$refs['input-search-field-' + this._uid].focus();
            });

            this.filter_index++;

            if (this.filter_list.length) {
                this.visible = {
                    options: true,
                    operator: false,
                    values: false,
                };
            } else {
                this.visible = {
                    options: false,
                    operator: false,
                    values: false,
                };
            }

            this.show_date = false;

            this.filter_last_step = 'options';
            this.search = '';
        },

        onFilterDelete(index) {
            this.show_icon = true;
            this.show_close_icon = false;
            this.filter_list.push(this.selected_options[index]);

            if (this.filter_last_step == 'options') {
                this.filter_index--;
            }

            this.filtered.splice(index, 1);

            this.selected_options.splice(index, 1);
            this.selected_operator.splice(index, 1);
            this.selected_values.splice(index, 1);

            this.show_date = false;

            this.filter_last_step = 'options';
        },

        onSearchAndFilterClear() {
            this.filtered = [];
            this.search = '';

            Cookies.remove('search-string');

            this.onInputConfirm();
        },

        convertOption(options) {
            let values = [];

            // Option set sort_option data
            if (!Array.isArray(options)) {
                for (const [key, value] of Object.entries(options)) {
                    values.push({
                        key: (key).toString(),
                        value: (value).toString()
                    });
                }
            } else {
                options.forEach(function (option, index) {
                    values.push({
                        key: (option.key).toString(),
                        value: (option.value).toString()
                    });
                }, this);
            }

            return values;
        },

        closeIfClickedOutside(event) {
            if (!document.getElementById('search-field-' + this._uid).contains(event.target) && event.target.getAttribute('data-btn') != 'btn btn-link') {
                this.visible.options = false;
                this.visible.operator = false;
                this.visible.values = false;

                document.removeEventListener('click', this.closeIfClickedOutside);
            }
        },
    },

    created() {
        let path = window.location.href.replace(window.location.search, '');

        let cookie = Cookies.get('search-string');

        if (cookie != undefined) {
            cookie = JSON.parse(cookie)[path];
        }

        if (this.value) {
            this.value = this.value.replace(/\s+[a-zA-Z\w]+[<=]+/g, '-to-');
            this.value = this.value.replace('>=', ':');

            let search_string = this.value.replace('not ', '').replace(' not ', ' ');

            search_string = search_string.split(' ');

            search_string.forEach(function (string) {
                if (string.search(':') === -1) {
                    this.search = string.replace(/[\"]+/g, '');
                } else {
                    let filter = string.split(':');
                    let option = '';
                    let operator = '=';
                    let value = '';
                    let value_assigned = false;

                    this.filter_list.forEach(function (_filter, i) {
                        let filter_values = this.convertOption(_filter.values);

                        if (_filter.key == filter[0]) {
                            option = _filter.value;
                            operator = _filter.operator;

                            filter_values.forEach(function (_value) {
                                if (_value.key == filter[1]) {
                                    value = _value.value;
                                }
                            }, this);

                            if (!value && (cookie != undefined && cookie[_filter.key])) {
                                value = cookie[_filter.key].value;
                            }

                            if (cookie != undefined && cookie[_filter.key]) {
                                operator = cookie[_filter.key].operator;
                            }

                            this.selected_options.push(this.filter_list[i]);

                            this.selected_operator.push({
                                key: operator,
                            });

                            this.filter_list.splice(i, 1);

                            this.option_values[_filter.key] = filter_values;

                            filter_values.forEach(function (value, j) {
                                if (value.key == filter[1]) {
                                    this.selected_values.push(value);

                                    this.option_values[_filter.key].splice(j, 1);

                                    value_assigned = true
                                }
                            }, this);

                            if (!value_assigned && (cookie != undefined && cookie[_filter.key])) {
                                this.selected_values.push(cookie[_filter.key]);
                            }
                        }
                    }, this);

                    this.filtered.push({
                        option: option,
                        operator: operator,
                        value: value
                    });

                    this.filter_index++;
                }
            }, this);
        } else if (this.defaultFiltered) {
            this.defaultFiltered.forEach(function (filter) {
                let option = '';
                let operator = '=';
                let value = '';

                this.filter_list.forEach(function (_filter, i) {
                    let filter_values = this.convertOption(_filter.values);

                    if (_filter.key == filter.option) {
                        option = _filter.value;
                        operator = filter.operator;

                        filter_values.forEach(function (_value) {
                            if (_value.key == filter.value) {
                                value = _value.value;
                            }
                        }, this);

                        this.selected_options.push(this.filter_list[i]);

                        this.selected_operator.push({
                            key: operator,
                        });

                        this.filter_list.splice(i, 1);

                        this.option_values[_filter.key] = filter_values;

                        filter_values.forEach(function (value, j) {
                            if (value.key == filter.value) {
                                this.selected_values.push(value);

                                this.option_values[_filter.key].splice(j, 1);
                            }
                        }, this);
                    }
                }, this);

                this.filtered.push({
                    option: option,
                    operator: operator,
                    value: value
                });

                this.filter_index++;
            }, this);
        }
    },

    mounted() {
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

    watch: {
        visible: {
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
    .searh-field .tags-group:hover > span {
        background:#cbd4de;
        background-color: #cbd4de;
        border-color: #cbd4de;
    }

    .searh-field .el-tag.el-tag--primary .el-tag__close.el-icon-close {
        color: #8898aa;
        margin-top: -3px;
    }

    .searh-field .el-tag.el-tag--primary .el-tag__close.el-icon-close:hover {
        background-color: transparent;
    }

    html[dir='ltr'] .searh-field .el-tag-option {
        border-radius: 0.50rem 0 0 0.50rem;
        margin-left: 10px;
    }

    html[dir='rtl'] .searh-field .el-tag-option {
        border-radius: 0.50rem;
        margin-right: 10px;
    }

    .searh-field .el-tag-operator {
        border-radius: 0;
        margin-left: -1px;
        margin-right: -1px;
    }

    html[dir='ltr'] .searh-field .el-tag-value {
        border-radius: 0 0.50rem 0.50rem 0;
        margin-right: 10px;
    }

    html[dir='rtl'] .searh-field .el-tag-value {
        border-radius: 0.50rem;
        margin-left: 10px;
    }

    html[dir='rtl'] .searh-field .el-tag-operator {
        border-radius: 0.50rem;
    }

    .searh-field .el-select.input-new-tag {
        width: 100%;
    }

    .searh-field .btn-helptext {
        margin-left: auto;
        color: var(--gray);
    }

    .searh-field .btn:not(:disabled):not(.disabled):active:focus,
    .searh-field .btn:not(:disabled):not(.disabled).active:focus {
    -webkit-box-shadow: none !important;
        box-shadow: none !important;
    }

    .searh-field .form-control.datepicker.flatpickr-input {
        padding: inherit !important;
    }

    .searh-field .dropdown-menu.operator {
        min-width: 50px !important;
    }

    .searh-field .dropdown-menu.operator .btn i:not(:last-child), .btn svg:not(:last-child) {
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
