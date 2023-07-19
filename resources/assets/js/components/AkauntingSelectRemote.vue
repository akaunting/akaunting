<template>
    <base-input v-if="title" :label="title" :name="name"
        :readonly="readonly"
        :disabled="disabled"
        :not-required="notRequired"
        :class="[
            {'readonly': readonly},
            {'disabled': disabled},
            {'no-arrow': noArrow},
            formClasses
        ]"
        :error="formError">

        <el-select v-model="selected" :placeholder="dynamicPlaceholder" filterable remote reserve-keyword
            @change="change" @visible-change="visibleChange" @remove-tag="removeTag" @clear="clear" @blur="blur" @focus="focus"
            :clearable="clearable"
            :disabled="disabled"
            :multiple="multiple"
            :readonly="readonly"
            :collapse-tags="collapse"
            :remote-method="remoteMethod"
            :loading="loading"
        >
            <div v-if="loading" class="el-select-dropdown__wrap" slot="empty">
                <p class="el-select-dropdown__empty pt-2 pb-0 loading">
                   <span class="material-icons form-spin text-lg animate-spin">data_usage</span>
                </p>
            </div>

            <div v-if="!loading && addNew.status && options.length != 0 && sortedOptions.length == 0" class="el-select-dropdown__wrap" slot="empty">
                <p class="el-select-dropdown__empty pt-2 pb-0">
                    {{ noMatchingDataText }}
                </p>

                <ul class="el-scrollbar__view el-select-dropdown__list">
                    <li class="el-select-dropdown__item el-select__footer bg-purple" disabled value="">
                        <div class="w-full flex items-center" @click="onAddItem">
                            <span class="material-icons text-xl text-purple">add</span>
                            <span class="flex-1 font-bold text-purple">
                                {{ addNew.text }}
                            </span>
                        </div>
                    </li>
                </ul>
            </div>

            <div v-if="!loading && addNew.status && options.length == 0">
                <el-option class="text-center" disabled :label="noDataText" value="value"></el-option>
                <ul class="el-scrollbar__view el-select-dropdown__list">
                    <li class="el-select-dropdown__item el-select__footer bg-purple">
                        <div class="w-full flex items-center" @click="onAddItem">
                            <span class="material-icons text-xl text-purple">add</span>
                            <span class="flex-1 font-bold text-purple">
                                {{ addNew.text }}
                            </span>
                        </div>
                    </li>
                </ul>
            </div>

            <template slot="prefix">
                <span class="el-input__suffix-inner el-select-icon">
                    <i :class="'select-icon-position el-input__icon fa fa-' + icon"></i>
                </span>
            </template>

            <el-option v-if="!group" v-for="(option, index) in sortedOptions"
                :key="option.key"
                :disabled="disabledOptions.includes(option.key)"
                :label="option.value"
                :value="option.key">
                <span class="float-left" :style="'padding-left: ' + (10 * option.level).toString() + 'px;'"><i v-if="option.level != 0" class="material-icons align-middle text-lg ltr:mr-2 rtl:ml-2">subdirectory_arrow_right</i>{{ option.value }}</span>
                <span class="new-badge absolute right-2 bg-green text-white px-2 py-1 rounded-md text-xs" v-if="new_options[option.key] || (option.mark_new)">{{ addNew.new_text }}</span>
            </el-option>

            <el-option-group
                v-if="group"
                v-for="(group_options, group_index) in sortedOptions"
                :key="group_index"
                :label="group_options.key">
                <el-option
                    v-for="(option, option_index) in group_options.value"
                    :key="option.key"
                    :disabled="disabledOptions.includes(option.key)"
                    :label="option.value"
                    :value="option.key">
                    <span class="float-left" :style="'padding-left: ' + (10 * option.level).toString() + 'px;'"><i v-if="option.level != 0" class="material-icons align-middle text-lg ltr:mr-2 rtl:ml-2">subdirectory_arrow_right</i>{{ option.value }}</span>
                    <span class="new-badge absolute right-2 bg-green text-white px-2 py-1 rounded-md text-xs" v-if="new_options[option.key] || (option.mark_new)">{{ addNew.new_text }}</span>
                </el-option>
            </el-option-group>

            <el-option v-if="!loading && addNew.status && options.length != 0 && sortedOptions.length > 0" class="el-select__footer bg-purple" :disabled="disabled" value="">
                <div class="w-full flex items-center" @click="onAddItem">
                    <span class="material-icons text-xl text-purple">add</span>
                    <span class="flex-1 font-bold text-purple">
                        {{ addNew.text }}
                    </span>
                </div>
            </el-option>

        </el-select>

        <component v-bind:is="add_new_html" @submit="onSubmit" @cancel="onCancel"></component>

        <span slot="infoBlock" class="absolute right-8 top-3 bg-green text-white px-2 py-1 rounded-md text-xs" v-if="new_options[selected] || (sorted_options.length && sorted_options[sorted_options.length - 1].mark_new && sorted_options[sorted_options.length - 1].key == selected)">{{ addNew.new_text }}</span>

        <select :name="name"  :id="name" class="hidden">
            <option v-for="option in sortedOptions" :key="option.key" :value="option.key">{{ option.value }}</option>
        </select>

    </base-input>

    <span v-else>
        <el-select v-model="selected" :placeholder="dynamicPlaceholder" filterable remote reserve-keyword
            @change="change" @visible-change="visibleChange" @remove-tag="removeTag" @clear="clear" @blur="blur" @focus="focus"
            :clearable="clearable"
            :disabled="disabled"
            :multiple="multiple"
            :readonly="readonly"
            :collapse-tags="collapse"
            :remote-method="remoteMethod"
            :loading="loading"
        >
            <div v-if="loading" class="el-select-dropdown__wrap" slot="empty">
                <p class="el-select-dropdown__empty pt-2 pb-0 loading">
                    <span class="material-icons form-spin text-lg animate-spin">data_usage</span>
                </p>
            </div>

            <div v-if="!loading && addNew.status && options.length != 0 && sortedOptions.length == 0" class="el-select-dropdown__wrap" slot="empty">
                <p class="el-select-dropdown__empty pt-2 pb-0">
                    {{ noMatchingDataText }}
                </p>

                <ul class="el-scrollbar__view el-select-dropdown__list">
                    <li class="el-select-dropdown__item el-select__footer bg-purple" disabled value="">
                        <div class="w-full flex items-center" @click="onAddItem">
                           <span class="material-icons text-xl text-purple">add</span>
                           <span class="flex-1 font-bold text-purple">
                                {{ addNew.text }}
                            </span>
                        </div>
                    </li>
                </ul>
            </div>

            <div v-if="!loading && addNew.status && options.length == 0">
                <el-option class="text-center" disabled :label="noDataText" value="value"></el-option>
                <ul class="el-scrollbar__view el-select-dropdown__list">
                    <li class="el-select-dropdown__item el-select__footer bg-purple">
                        <div class="w-full flex items-center" @click="onAddItem">
                            <span class="material-icons text-xl text-purple">add</span>
                            <span class="flex-1 font-bold text-purple">
                                {{ addNew.text }}
                            </span>
                        </div>
                    </li>
                </ul>
            </div>

            <template slot="prefix">
                <span class="el-input__suffix-inner el-select-icon">
                    <i :class="'select-icon-position el-input__icon fa fa-' + icon"></i>
                </span>
            </template>

            <el-option v-if="!group" v-for="(option, index) in sortedOptions"
                :key="option.key"
                :disabled="disabledOptions.includes(option.key)"
                :label="option.value"
                :value="option.key">
                <span class="float-left" :style="'padding-left: ' + (10 * option.level).toString() + 'px;'"><i v-if="option.level != 0" class="material-icons align-middle text-lg ltr:mr-2 rtl:ml-2">subdirectory_arrow_right</i>{{ option.value }}</span>
                <span class="new-badge absolute right-2 bg-green text-white px-2 py-1 rounded-md text-xs" v-if="new_options[option.key] || (option.mark_new)">{{ addNew.new_text }}</span>
            </el-option>

            <el-option-group
                v-if="group"
                v-for="(group_options, group_index) in sortedOptions"
                :key="group_index"
                :label="group_options.key">
                <el-option
                    v-for="(option, option_index) in group_options.value"
                    :key="option.key"
                    :disabled="disabledOptions.includes(option.key)"
                    :label="option.value"
                    :value="option.key">
                    <span class="float-left" :style="'padding-left: ' + (10 * option.level).toString() + 'px;'"><i v-if="option.level != 0" class="material-icons align-middle text-lg ltr:mr-2 rtl:ml-2">subdirectory_arrow_right</i>{{ option.value }}</span>
                    <span class="new-badge absolute right-2 bg-green text-white px-2 py-1 rounded-md text-xs" v-if="new_options[option.key] || (option.mark_new)">{{ addNew.new_text }}</span>
                </el-option>
            </el-option-group>

            <el-option v-if="!loading && addNew.status && options.length != 0 && sortedOptions.length > 0" class="el-select__footer bg-purple" disabled  value="">
                <div class="w-full flex items-center" @click="onAddItem">
                    <span class="material-icons text-xl text-purple">add</span>
                    <span class="flex-1 font-bold text-purple">
                        {{ addNew.text }}
                    </span>
                </div>
            </el-option>

        </el-select>

        <component v-bind:is="add_new_html" @submit="onSubmit" @cancel="onCancel"></component>

        <span slot="infoBlock" class="absolute right-8 top-3 bg-green text-white px-2 py-1 rounded-md text-xs" v-if="new_options[selected] || (sorted_options.length && sorted_options[sorted_options.length - 1].mark_new && sorted_options[sorted_options.length - 1].key == selected)">{{ addNew.new_text }}</span>

        <select :name="name"  :id="name" v-model="selected" class="d-none">
            <option v-for="option in sortedOptions" :key="option.key" :value="option.key">{{ option.value }}</option>
        </select>
    </span>
</template>

<script>
import Vue from 'vue';

import { Select, Option, OptionGroup, ColorPicker } from 'element-ui';

import AkauntingModalAddNew from './AkauntingModalAddNew';
import AkauntingModal from './AkauntingModal';
import AkauntingMoney from './AkauntingMoney';
import AkauntingRadioGroup from './AkauntingRadioGroup';
import AkauntingSelect from './AkauntingSelect';
import AkauntingDate from './AkauntingDate';
import AkauntingRecurring from './AkauntingRecurring';

import Form from './../plugins/form';

export default {
    name: "akaunting-select-remote",

    components: {
        [Select.name]: Select,
        [Option.name]: Option,
        [OptionGroup.name]: OptionGroup,
        [ColorPicker.name]: ColorPicker,
        AkauntingModalAddNew,
        AkauntingRadioGroup,
        AkauntingSelect,
        AkauntingModal,
        AkauntingMoney,
        AkauntingDate,
        AkauntingRecurring,
    },

    props: {
        title: {
            type: String,
            default: null,
            description: "Selectbox label text"
        },

        placeholder: {
            type: String,
            default: '',
            description: "Selectbox input placeholder text"
        },

        formClasses: {
            type: Array,
            default: null,
            description: "Selectbox input class name"
        },

        formError: {
            type: String,
            default: null,
            description: "Selectbox input error message"
        },

        icon: {
            type: String,
            description: "Prepend icon (left)"
        },

        name: {
            type: String,
            default: null,
            description: "Selectbox attribute name"
        },

        value: {
            type: [String, Number, Array, Object],
            default: '',
            description: "Selectbox selected value"
        },

        options: null,

        dynamicOptions: null,

        fullOptions: null,

        disabledOptions: {
            type: Array,
            default: function () {
                return [];
            },
            description: "Selectbox Add New Item Feature"
        },

        option_sortable: {
            type: String,
            default: 'value',
            description: "Option Sortable type (key|value)"
        },

        sortOptions: {
            type: Boolean,
            default: true,
            description: 'Sort options by the option_sortable prop, or sorting is made server-side',
        },

        model: {
            type: [String, Number, Array, Object],
            default: '',
            description: "Selectbox selected model"
        },

        addNew: {
            type: Object,
            default: function () {
                return {
                    text: 'Add New Item',
                    status: false,
                    path: null,
                    type: 'modal', // modal, inline
                    field: 'name',
                    new_text: 'New',
                    buttons: {}
                };
            },
            description: "Selectbox Add New Item Feature"
        },

        group: {
            type: Boolean,
            default: false,
            description: "Selectbox option group status"
        },

        multiple: {
            type: Boolean,
            default: false,
            description: "Multible feature status"
        },

        readonly: {
            type: Boolean,
            default: false,
            description: "Selectbox disabled status"
        },

        noArrow: {
            type: Boolean,
            default: false,
            description: "Selectbox show arrow"
        },

        clearable: {
            type: Boolean,
            default: true,
            description: "Selectbox clearable status"
        },

        notRequired: {
            type: Boolean,
            default: false
        },

        disabled: {
            type: Boolean,
            default: false,
            description: "Selectbox disabled status"
        },

        collapse:  {
            type: Boolean,
            default: false,
            description: "Selectbox collapse status"
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

        searchable: {
            type: Boolean,
            default: false,
            description: "Selectbox searchable"
        },

        searchText: {
            type: String,
            default: '',
            description: "Selectbox input search placeholder text"
        },

        remoteAction: {
            type: String,
            default: null,
            description: "Selectbox remote action path"
        },

        currencyCode: {
            type: String,
            default: 'USD',
            description: "Get remote item price currecy code"
        },
    },

    data() {
        return {
            dynamicPlaceholder: this.placeholder,
            add_new: {
                text: this.addNew.text,
                show: false,
                path: this.addNew.path,
                type: this.addNew.type, // modal, inline
                field: this.addNew.field,
                buttons: this.addNew.buttons,
            },
            add_new_html: '',

            selected: this.model,

            form: {},
            sorted_options: [],
            full_options:[],
            new_options: {},
            loading: false,
        }
    },

    created() {
        this.setSortedOptions();

        if (this.searchable) {
            this.setFullOptions();
        }
    },

    computed: {
        sortedOptions() {
            if (! this.sortOptions) {
                return this.sorted_options;
            }

            if (this.group) {
                this.sorted_options.sort(this.sortBy("key"));

                for (const [index, options] of Object.entries(this.sorted_options)) {
                    options.value.sort(this.sortBy(this.option_sortable));
                }
            } else {
                this.sorted_options.sort(this.sortBy(this.option_sortable));
            }

            return this.sorted_options;
        },
    },

    mounted() {
        // Check Here..
        this.selected = this.value;

        if (this.model.length) {
            try {
                if (eval(this.model) !== undefined) {
                    this.selected = eval(this.model);
                } else {
                    this.selected = this.model;
                }
            } catch (e) {
                this.selected = this.model;
            }
        }

        if (this.multiple && !this.selected.length) {
            this.selected = [];
        }

        this.$emit('interface', this.selected);

        setTimeout(function() {
            this.change();
        }.bind(this), 800);
    },

    methods: {
        sortBy(option) {
            return (firstEl, secondEl) => {
                let first_element = firstEl[option].toUpperCase(); // ignore upper and lowercase
                let second_element = secondEl[option].toUpperCase(); // ignore upper and lowercase

                if (first_element < second_element) {
                    return -1;
                }

                if (first_element > second_element) {
                    return 1;
                }

                // names must be equal
                return 0;
            }
        },

        setSortedOptions() {
            // Reset sorted_options 
            this.sorted_options = [];

            let created_options = (this.dynamicOptions) ? this.dynamicOptions : this.options;

            if (this.group) {
                // Option set sort_option data
                if (!Array.isArray(created_options)) {
                    for (const [index, options] of Object.entries(created_options)) {
                        let values = [];

                        for (const [key, value] of Object.entries(options)) {
                            values.push({
                                key: key,
                                value: value,
                                level: 0
                            });
                        }

                        this.sorted_options.push({
                            key: index,
                            value: values
                        });
                    }
                } else {
                    created_options.forEach(function (option, index) {
                        if (typeof(option) == 'string') {
                            this.sorted_options.push({
                                index: index,
                                key: index.toString(),
                                value: option,
                                level: 0
                            });
                        } else {
                            this.sorted_options.push({
                                index: index,
                                key: option.id.toString(),
                                value: (option.title) ? option.title : (option.display_name) ? option.display_name : option.name,
                                level: (option.level) ? option.level : 0
                            });
                        }
                    }, this);
                }
            } else {
                // Option set sort_option data
                if (!Array.isArray(created_options)) {
                    for (const [key, value] of Object.entries(created_options)) {
                        this.sorted_options.push({
                            key: key,
                            value: value,
                            level: 0
                        });
                    }
                } else {
                    created_options.forEach(function (option, index) {
                        if (typeof(option) == 'string') {
                            this.sorted_options.push({
                                index: index,
                                key: index.toString(),
                                value: option,
                                level: 0
                            });
                        } else {
                            this.sorted_options.push({
                                index: index,
                                key: option.id.toString(),
                                value: (option.title) ? option.title : (option.display_name) ? option.display_name : option.name,
                                level: (option.level) ? option.level : 0
                            });
                        }
                    }, this);
                }
            }
        },

        setFullOptions() {
            // Reset full_options 
            this.full_options = [];

            let created_options = (this.dynamicOptions) ? this.dynamicOptions : this.fullOptions;

            if (this.group) {
                // Option set sort_option data
                if (!Array.isArray(created_options)) {
                    for (const [index, options] of Object.entries(created_options)) {
                        let values = [];

                        for (const [key, value] of Object.entries(options)) {
                            values.push({
                                key: key,
                                value: value,
                                level: 0
                            });
                        }

                        this.full_options.push({
                            key: index,
                            value: values
                        });
                    }
                } else {
                    created_options.forEach(function (option, index) {
                        if (typeof(option) == 'string') {
                            this.full_options.push({
                                index: index,
                                key: index.toString(),
                                value: option,
                                level: 0
                            });
                        } else {
                            this.full_options.push({
                                index: index,
                                key: option.id.toString(),
                                value: (option.title) ? option.title : (option.display_name) ? option.display_name : option.name,
                                level: (option.level) ? option.level : 0
                            });
                        }
                    }, this);
                }
            } else {
                // Option set sort_option data
                if (!Array.isArray(created_options)) {
                    for (const [key, value] of Object.entries(created_options)) {
                        this.full_options.push({
                            key: key,
                            value: value,
                            level: 0
                        });
                    }
                } else {
                    created_options.forEach(function (option, index) {
                        if (typeof(option) == 'string') {
                            this.full_options.push({
                                index: index,
                                key: index.toString(),
                                value: option,
                                level: 0
                            });
                        } else {
                            this.full_options.push({
                                index: index,
                                key: option.id.toString(),
                                value: (option.title) ? option.title : (option.display_name) ? option.display_name : option.name,
                                level: (option.level) ? option.level : 0
                            });
                        }
                    }, this);
                }
            }
        },

        change() {
            // This controll added add new changed..
            if (typeof(this.selected) === 'object' && typeof(this.selected.type) !== 'undefined') {
                return false;
            }

            this.$emit('interface', this.selected);

            this.$emit('change', this.selected);

            // Option changed sort_option data
            if (this.group) {
                this.sorted_options.forEach(function (option_group, group_index) {
                    option_group.value.forEach(function (option, index) {
                        if (this.multiple) {
                            let indexs = [];
                            let values = [];
                            let labels = [];
                            let options = [];

                            this.selected.forEach(function (selected_option_id, selected_index) {
                                if (option.key == selected_option_id) {
                                    indexs.push(selected_index);
                                    values.push(option.id);
                                    labels.push(option.value);
                                    options.push(option);
                                }
                            });

                            this.$emit('index', indexs);
                            this.$emit('value', values);
                            this.$emit('label', labels);
                            this.$emit('option', options);
                        } else {
                            if (option.key == this.selected) {
                                this.$emit('index', index);
                                this.$emit('value', option.id);
                                this.$emit('label', option.value);
                                this.$emit('option', option);
                            }
                        }
                    }, this);
                }, this);
            } else {
                this.sorted_options.forEach(function (option, index) {
                    if (this.multiple) {
                        let indexs = [];
                        let values = [];
                        let labels = [];
                        let options = [];

                        this.selected.forEach(function (selected_option_id, selected_index) {
                            if (option.key == selected_option_id) {
                                indexs.push(selected_index);
                                values.push(option.id);
                                labels.push(option.value);
                                options.push(option);
                            }
                        });

                        this.$emit('index', indexs);
                        this.$emit('value', values);
                        this.$emit('label', labels);
                        this.$emit('option', options);
                    } else {
                        if (option.key == this.selected) {
                            this.$emit('index', index);
                            this.$emit('value', option.id);
                            this.$emit('label', option.value);
                            this.$emit('option', option);
                        }
                    }
                }, this);
            }
        },

        visibleChange(event) {
            this.$emit('visible-change', event);

            this.dynamicPlaceholder = this.placeholder;

            if (event && this.searchText) {
                this.dynamicPlaceholder = this.searchText;
            }

            if (this.searchable) {
                let selected = this.selected;
                this.sorted_options = [];

                this.setSortedOptions();

                for (const [key, value] of Object.entries(this.full_options)) {
                    if (selected == value.key) {
                        this.sorted_options.push({
                            index: value.index,
                            key: value.key,
                            value: value.value,
                            level: value.level
                        });
                    }
                }
            }
        },

        removeTag(event) {
            this.$emit('remove-tag', event);
        },

        clear(event) {
            this.$emit('clear', event);
        },

        blur(event) {
            this.$emit('blur', event);
        },

        focus(event) {
            this.$emit('focus', event);
        },

        remoteMethod(query) {
            if (document.getElementById('form-select-' + this.name)) {
                document.getElementById('form-select-' + this.name).getElementsByTagName("input")[0].readOnly = false;
            }

            if (this.searchable) {
                return this.serchableMethod(query);
            }

            if (query !== '') {
                this.loading = true;

                let path = this.remoteAction;

                if (!path) {
                   path = url + '/common/items/autocomplete';
                }

                if (path.indexOf('?search') === -1) {
                    path += '?search="' + query + '"';
                } else {
                    path += ' "' + query + '"';
                }

                path += ' limit:10';

                path += '&currency_code=' + this.currencyCode;

                window.axios({
                    method: 'GET',
                    url: path,
                    headers: {
                        'X-CSRF-TOKEN': window.Laravel.csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    this.loading = false;

                    if (response.data.data) {
                        let data = response.data.data;
                        //this.sorted_options = [];

                        data.forEach(function (option) {
                            let check = false;

                            this.sorted_options.forEach(function (sort_option) {
                                if (sort_option.key == option.id) {
                                    check = true;
                                    return;
                                }
                            });

                            if (!check) {
                                this.sorted_options.push({
                                    key: option.id.toString(),
                                    value: (option.title) ? option.title : (option.display_name) ? option.display_name : option.name,
                                    level: (option.parent_id) ? 1 : 0 // 0: parent, 1: child. Level data get 0 via backend. This control will refactor.
                                });
                            }

                        }, this);

                        this.sorted_options = this.sorted_options.filter(item => {
                            return item.value.toLowerCase()
                                .indexOf(query.toLowerCase()) > -1;
                        });
                    } else {
                        this.sortedOptions = [];
                    }
                })
                .catch(e => {
                })
                .finally(function () {
                    // always executed
                });
            } else {
                this.setSortedOptions();
            }
        },

        serchableMethod(query) {
            if (query !== '') {
                this.loading = true;

                setTimeout(() => {
                    this.loading = false;

                    this.sorted_options = this.full_options.filter(item => {
                        return item.value.toLowerCase()
                            .indexOf(query.toLowerCase()) > -1;
                    });
                }, 200);
            } else {
                this.setSortedOptions();
            }
        },

        async onAddItem() {
            // Get Select Input value
            if (this.multiple) {
                var value = this.$children[0].$children[0]. $refs.input.value;
            } else {
                if (this.title) {
                    var value = this.$children[0].$children[0].$children[0].$refs.input.value;
                } else {
                    var value = this.$children[0].$children[0].$children[0].$refs.input.value;
                }
            }

            if (this.add_new.type == 'inline') {
                if (value === '') {
                    return false;
                }

                await this.addInline(value);
            } else {
                await this.onModal(value);
            }
        },

        addInline(value) {
            window.axios.post(this.add_new.path, {
                '_token': window.Laravel.csrfToken,
                'type': 'inline',
                field: this.add_new.field.value,
                value: value,
            })
            .then(response => {
                if (response.data.success) {
                    if (!Object.keys(this.options).length) {
                        this.selectOptions =  [];
                    }

                    this.selectOptions.push(response.data.data);
                    this.new_options[response.data.data[this.add_new.field.key]] = response.data.data;
                    this.real_model = response.data.data[this.add_new.field.key];

                    if (this.title) {
                        this.$children[0].$children[0].visible = false;
                    } else {
                        this.$children[0].visible = false;
                    }

                    this.$emit('new', response.data.data);

                    this.change();
                }
            })
            .catch(error => {
                console.log(error);
            });
        },

        onModal(value) {
            this.setSortedOptions();

            let add_new = this.add_new;

            window.axios.get(this.add_new.path)
            .then(response => {
                add_new.show = true;
                add_new.html = response.data.html;

                if (this.title) {
                    this.$children[0].$children[0].visible = false;
                } else {
                    this.$children[0].visible = false;
                }

                this.add_new_html = Vue.component('add-new-component', function (resolve, reject) {
                    resolve({
                        template: '<div><akaunting-modal-add-new modal-dialog-class="max-w-md" modal-position-top :show="add_new.show" @submit="onSubmit" @cancel="onCancel" :buttons="add_new.buttons" :title="add_new.text" :is_component=true :message="add_new.html"></akaunting-modal-add-new></div>',

                        components: {
                            AkauntingModalAddNew,
                            AkauntingRadioGroup,
                            AkauntingSelect,
                            AkauntingModal,
                            AkauntingMoney,
                            AkauntingDate,
                            AkauntingRecurring,
                            [ColorPicker.name]: ColorPicker,
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
                this.errors.push(e);
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
                    this.sorted_options.push({
                        key: response.data.data[this.add_new.field.key].toString(),
                        value: response.data.data[this.add_new.field.value],
                        level: response.data.data.parent_id ? 1 : 0,
                    });

                    this.new_options[response.data.data[this.add_new.field.key]] = response.data.data[this.add_new.field.value];

                    if (this.multiple) {
                        this.selected.push(response.data.data[this.add_new.field.key].toString());
                    } else {
                        this.selected = response.data.data[this.add_new.field.key].toString();
                    }

                    this.add_new.show = false;

                    this.add_new.html = '';
                    this.add_new_html = null;

                    this.$emit('new', response.data.data);

                    this.change();

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

        addModal() {

        },
    },

    dynamicOptionsValue(options) {
        if (! this.forceDynamicOptionValue) {
            if (this.multiple) {
                this.selected = [];
            } else {
                this.selected = '';
            }

            return;
        }

        if (this.multiple) {
            let selected = this.selected;
            this.selected = [];

            selected.forEach(function (select, index) {
                if (Array.isArray(this.sorted_options) && this.sorted_options.find((option) => option.key == select)) {
                    this.selected.push(select);
                }
            }, this);
        } else {
            if (Array.isArray(options) && ! options.find((option) => option == this.selected)) {
                this.selected = '';
            }
        }
    },

    watch: {
        selected: function (selected) {
            if (!this.multiple) {
                if (typeof selected != 'string' && selected !== undefined) {
                    this.selected = selected.toString();
                } else {
                    this.selected = selected;
                }
            } else {
                if (Array.isArray(this.selected) && !this.selected.length) {
                    this.selected = selected;
                } else {
                    let is_string = false;
                    let pre_value = [];

                    selected.forEach(item => {
                        if (typeof item != 'string') {
                            is_string = true;
                            pre_value.push(item.toString());
                        }
                    });

                    if (is_string) {
                        this.selected = pre_value;
                    }
                }
            }
        },

        value: function (selected) {
            // we tested this function works with post a form and after the selected function so put in the comment line
            // if (!this.multiple) {
            //     this.selected = selected.toString();
            // } else {
            //     if (Array.isArray(this.selected) && !this.selected.length) {
            //         this.selected = selected;
            //     } else {
            //         let is_string = false;
            //         let pre_value = [];

            //         selected.forEach(item => {
            //             if (typeof item != 'string') {
            //                 is_string = true;
            //                 pre_value.push(item.toString());
            //             }
            //         });

                    // if (is_string) {
                    //     this.selected = pre_value;
                    // }
            //     }
            // }

            // this.change();
        },

        model: function (selected) {
            if (!this.multiple) {
                this.selected = selected.toString();
            } else {
                let is_string = false;
                let pre_value = [];

                selected.forEach(item => {
                    if (typeof item != 'string') {
                        is_string = true;
                        pre_value.push(item.toString());
                    }
                });

                if (is_string) {
                    this.selected = pre_value;
                }
            }

            this.change();
        },

        dynamicOptions: function(options) {
            this.sorted_options = [];
            this.selected = [];

            if (this.group) {
                // Option set sort_option data
                if (!Array.isArray(options)) {
                    if (typeof(this.selected) == 'string') {
                        this.selected = '';
                    }
                    
                    for (const [index, _options] of Object.entries(options)) {
                        let values = [];

                        for (const [key, value] of Object.entries(_options)) {
                            values.push({
                                key: key,
                                value: value,
                                level: 0
                            });
                        }

                        this.sorted_options.push({
                            key: index,
                            value: values
                        });
                    }
                } else {
                    options.forEach(function (option, index) {
                        if (typeof(option) == 'string') {
                            this.sorted_options.push({
                                index: index,
                                key: index.toString(),
                                value: option,
                                level: 0
                            });
                        } else {
                            this.sorted_options.push({
                                index: index,
                                key: option.id.toString(),
                                value: (option.title) ? option.title : (option.display_name) ? option.display_name : option.name,
                                level: (option.level) ? option.level : 0
                            });
                        }
                    }, this);
                }
            } else {
                // Option set sort_option data
                if (!Array.isArray(options)) {
                    for (const [key, value] of Object.entries(options)) {
                        this.sorted_options.push({
                            key: key,
                            value: value,
                            level: 0
                        });
                    }
                } else {
                    options.forEach(function (option, index) {
                        if (typeof(option) == 'string') {
                            this.sorted_options.push({
                                index: index,
                                key: index.toString(),
                                value: option,
                                level: 0
                            });
                        } else {
                            this.sorted_options.push({
                                index: index,
                                key: option.id.toString(),
                                value: (option.title) ? option.title : (option.display_name) ? option.display_name : option.name,
                                level: (option.level) ? option.level : 0
                            });
                        }
                    }, this);
                }

                this.dynamicOptionsValue(options);
            }
        },
    },
}
</script>
