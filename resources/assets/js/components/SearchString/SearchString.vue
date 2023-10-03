<template>
    <div
        :id="'search-string-' + _uid"
        class="lg:h-12 my-5 searh-field flex flex-col lg:flex-row border-b transition-all js-search"
        :class="input_focus ? 'border-gray-500' : 'border-gray-300'"
    >
        <slot name="filtered">
            <div 
                class="w-full lg:w-auto flex overflow-x-scroll large-overflow-unset"
                :class="{
                    'h-12 lg:h-auto': filtered.length
                }"
            >
                <filtered-list
                    v-for="(filter, index) in filtered"
                    :key="index"
                    :index="index"
                    :filter="filter"
                    @delete="onFilterDelete(index)"
                >
                </filtered-list>
            </div>
        </slot>

        <slot name="main">
            <filter-main
                :id="_uid"
                :filters="filters"
                :search-text="searchText"
            ></filter-main>
        </slot>
    </div>
</template>

<script>
    import FilteredList from "./SearchString/FilteredList";
    import FilterMain from "./SearchString/FilterMain";
    import {getQueryVariable} from './../plugins/functions';

export default {
    name: 'search-string',

    components: {
        FilteredList,
        FilterMain,
    },

    props: {
        placeholder: {
            type: String,
            default: 'Search or filter results...',
            description: 'Input placeholder'
        },

        selectPlaceholder: {
            type: String,
        },

        enterPlaceholder: {
            type: String,
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

        operatorInText: {
            type: String,
            default: 'in',
            description: 'Operator in "||"',
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

            equal: true,
            not_equal: true,
            multiple: false,
            range: false,
            option_values: [],
            selected_options: [],
            selected_operator: [],
            selected_values: [],
            values: [],
            multiple_values: [],
            current_operator: '',
            current_value: null,
            input_focus: false,
            defaultPlaceholder: this.placeholder,
            dynamicPlaceholder: this.placeholder,
        };
    },

    methods: {
        onInputDateSelected(selectedDates, dateStr, instance) {
            this.filtered[this.filter_index].value = dateStr;

            let date = selectedDates.length ? instance.formatDate(selectedDates[0], 'Y-m-d') : null;

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

            this.current_operator = value;

            this.selected_operator.push({
                key: value
            });

            this.filter_last_step = 'values';
        },

        onValueSelected(value) {
            this.show_close_icon = true;
            let select_value = false;

            this.onChangeSearchAndFilterText(this.enterPlaceholder, false);

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

        onMultipleValueSelected() {
            this.show_close_icon = true;
            let select_values = [];

            this.onChangeSearchAndFilterText(this.enterPlaceholder, false);

            for (let i = 0; i < this.values.length; i++) {
                this.multiple_values.forEach(function (value, index) {
                    if (this.values[i].key == value) {
                        select_values.push(this.values[i]);
                    }
                }, this);
            }

            this.selected_values.push(select_values);
            //this.option_values[this.current_value].splice(i, 1);

            this.filtered[this.filter_index].value = select_values;

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

            this.multiple_values = [];
        },

        onFilterDelete(index) {
            this.show_icon = true;

            this.filter_list.push(this.selected_options[index]);

            if (this.filter_last_step == 'options') {
                this.filter_index--;
            }

            this.filtered.splice(index, 1);

            this.selected_options.splice(index, 1);
            this.selected_operator.splice(index, 1);
            this.selected_values.splice(index, 1);

            this.show_date = false;

            if (this.filter_index == 0) {
                this.onChangeSearchAndFilterText(this.defaultPlaceholder, true);
                this.show_close_icon = false;
            } else {
                this.show_icon = false;
                this.show_close_icon = true;
            }

            this.filter_last_step = 'options';
        },

        onSearchAndFilterClear() {
            this.filtered = [];
            this.search = '';

            Cookies.remove('search-string');

            this.onInputConfirm();
        },

        onChangeSearchAndFilterText(arg, param) {
            this.dynamicPlaceholder = arg;
            this.show_icon = param;
        },

        convertOption(options) {
            let values = [];

            // Option set sort_option data
            if (! Array.isArray(options)) {
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
            if (document.getElementById('search-field-' + this._uid)) {
                if (!document.getElementById('search-field-' + this._uid).contains(event.target) && event.target.getAttribute('data-btn') != 'btn btn-link') {
                    this.visible.options = false;
                    this.visible.operator = false;
                    this.visible.values = false;
                    this.show_button = false;

                    document.removeEventListener('click', this.closeIfClickedOutside);
                }
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

                            if (! value && (cookie != undefined && cookie[_filter.key])) {
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
        if (this.filter_index > 0) {
            this.onChangeSearchAndFilterText(this.enterPlaceholder, false);
        }

        if (this.selected_values.length > 0) {
            this.show_close_icon = true;
        }
    },

    computed: {
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
        //margin-left: 10px;
    }

    html[dir='rtl'] .searh-field .el-tag-option {
        border-radius: 0 0.5rem 0.5rem 0;
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
        border-radius: 0.5rem 0 0 0.5rem;
        margin-left: 10px;
    }

    html[dir='rtl'] .searh-field .el-tag-operator {
        border-radius: 0;
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
