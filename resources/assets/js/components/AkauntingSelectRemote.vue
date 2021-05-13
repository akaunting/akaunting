<template>
    <base-input v-if="title" :label="title" :name="name"
        :readonly="readonly"
        :disabled="disabled"
        :class="[
            {'readonly': readonly},
            {'disabled': disabled},
            formClasses
        ]"
        :error="formError">

        <el-select v-model="selected" :placeholder="placeholder" filterable remote reserve-keyword
            @change="change" @visible-change="visibleChange" @remove-tag="removeTag" @clear="clear" @blur="blur" @focus="focus"
            :disabled="disabled"
            :multiple="multiple"
            :readonly="readonly"
            :collapse-tags="collapse"
            :remote-method="remoteMethod"
            :loading="loading"
        >
            <div v-if="loading" class="el-select-dropdown__wrap" slot="empty">
                <p class="el-select-dropdown__empty loading">
                    {{ loadingText }}
                </p>
            </div>

            <div v-if="!loading && addNew.status && options.length != 0 && sortOptions.length == 0" class="el-select-dropdown__wrap" slot="empty">
                <p class="el-select-dropdown__empty">
                    {{ noMatchingDataText }}
                </p>

                <ul class="el-scrollbar__view el-select-dropdown__list">
                    <li class="el-select-dropdown__item el-select__footer" disabled value="">
                        <div @click="onAddItem">
                            <i class="fas fa-plus"></i>
                            <span>
                                {{ addNew.text }}
                            </span>
                        </div>
                    </li>
                </ul>
            </div>

            <div v-else-if="!loading && addNew.status && options.length == 0" slot="empty">
                <p class="el-select-dropdown__empty">
                    {{ noDataText }}
                </p>
                <ul class="el-scrollbar__view el-select-dropdown__list">
                    <li class="el-select-dropdown__item el-select__footer">
                        <div @click="onAddItem">
                            <i class="fas fa-plus"></i>
                            <span>
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

            <el-option v-if="!group" v-for="(option, index) in sortOptions"
                :key="option.key"
                :disabled="disabledOptions.includes(option.key)"
                :label="option.value"
                :value="option.key">
                <span class="float-left">{{ option.value }}</span>
                <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[option.key]">{{ addNew.new_text }}</span>
            </el-option>

            <el-option-group
                v-if="group"
                v-for="(group_options, group_index) in sortOptions"
                :key="group_index"
                :label="group_options.key">
                <el-option
                    v-for="(option, option_index) in group_options.value"
                    :key="option.key"
                    :disabled="disabledOptions.includes(option.key)"
                    :label="option.value"
                    :value="option.key">
                    <span class="float-left">{{ option.value }}</span>
                    <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[option.key]">{{ addNew.new_text }}</span>
                </el-option>
            </el-option-group>

            <el-option v-if="!loading && addNew.status && options.length != 0 && sortOptions.length > 0" class="el-select__footer" :disabled="disabled" value="">
                <div @click="onAddItem">
                    <i class="fas fa-plus"></i>
                    <span>
                        {{ addNew.text }}
                    </span>
                </div>
            </el-option>

        </el-select>

        <component v-bind:is="add_new_html" @submit="onSubmit" @cancel="onCancel"></component>

        <span slot="infoBlock" class="badge badge-success badge-resize float-right" v-if="new_options[selected]">{{ addNew.new_text }}</span>

        <select :name="name"  :id="name" v-model="selected" class="d-none">
            <option v-for="option in sortOptions" :key="option.key" :value="option.key">{{ option.value }}</option>
        </select>

    </base-input>

    <span v-else>
        <el-select v-model="selected" :placeholder="placeholder" filterable remote reserve-keyword
            @change="change" @visible-change="visibleChange" @remove-tag="removeTag" @clear="clear" @blur="blur" @focus="focus"
            :disabled="disabled"
            :multiple="multiple"
            :readonly="readonly"
            :collapse-tags="collapse"
            :remote-method="remoteMethod"
            :loading="loading"
        >
            <div v-if="loading" class="el-select-dropdown__wrap" slot="empty">
                <p class="el-select-dropdown__empty loading">
                    {{ loadingText }}
                </p>
            </div>

            <div v-if="!loading && addNew.status && options.length != 0 && sortOptions.length == 0" class="el-select-dropdown__wrap" slot="empty">
                <p class="el-select-dropdown__empty">
                    {{ noMatchingDataText }}
                </p>

                <ul class="el-scrollbar__view el-select-dropdown__list">
                    <li class="el-select-dropdown__item el-select__footer" disabled value="">
                        <div @click="onAddItem">
                            <i class="fas fa-plus"></i>
                            <span>
                                {{ addNew.text }}
                            </span>
                        </div>
                    </li>
                </ul>
            </div>

            <div v-else-if="!loading && addNew.status && options.length == 0" slot="empty">
                <p class="el-select-dropdown__empty">
                    {{ noDataText }}
                </p>
                <ul class="el-scrollbar__view el-select-dropdown__list">
                    <li class="el-select-dropdown__item el-select__footer">
                        <div @click="onAddItem">
                            <i class="fas fa-plus"></i>
                            <span>
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

            <el-option v-if="!group" v-for="(option, index) in sortOptions"
                :key="option.key"
                :disabled="disabledOptions.includes(option.key)"
                :label="option.value"
                :value="option.key">
                <span class="float-left">{{ option.value }}</span>
                <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[option.key]">{{ addNew.new_text }}</span>
            </el-option>

            <el-option-group
                v-if="group"
                v-for="(group_options, group_index) in sortOptions"
                :key="group_index"
                :label="group_options.key">
                <el-option
                    v-for="(option, option_index) in group_options.value"
                    :key="option.key"
                    :disabled="disabledOptions.includes(option.key)"
                    :label="option.value"
                    :value="option.key">
                    <span class="float-left">{{ option.value }}</span>
                    <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[option.key]">{{ addNew.new_text }}</span>
                </el-option>
            </el-option-group>

            <el-option v-if="!loading && addNew.status && options.length != 0 && sortOptions.length > 0" class="el-select__footer" disabled  value="">
                <div @click="onAddItem">
                    <i class="fas fa-plus"></i>
                    <span>
                        {{ addNew.text }}
                    </span>
                </div>
            </el-option>

        </el-select>

        <component v-bind:is="add_new_html" @submit="onSubmit" @cancel="onCancel"></component>

        <span slot="infoBlock" class="badge badge-success badge-resize float-right" v-if="new_options[selected]">{{ addNew.new_text }}</span>

        <select :name="name"  :id="name" v-model="selected" class="d-none">
            <option v-for="option in sortOptions" :key="option.key" :value="option.key">{{ option.value }}</option>
        </select>
    </span>
</template>

<script>
import Vue from 'vue';

import { Select, Option, OptionGroup, ColorPicker } from 'element-ui';

import AkauntingModalAddNew from './AkauntingModalAddNew';
import AkauntingModal from './AkauntingModal';
import AkauntingMoney from './AkauntingMoney';
import AkauntingRadioGroup from './forms/AkauntingRadioGroup';
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

        loadingText: {
            type: String,
            default: 'Loading...',
            description: "Selectbox loading message"
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
            sort_options: [],
            new_options: {},
            loading: false,
        }
    },

    created() {
        this.setSortOptions();
    },

    computed: {
        sortOptions() {
            if (this.group) {
                this.sort_options.sort(this.sortBy("key"));

                for (const [index, options] of Object.entries(this.sort_options)) {
                    options.value.sort(this.sortBy(this.option_sortable));
                }
            } else {
                this.sort_options.sort(this.sortBy(this.option_sortable));
            }

            return this.sort_options;
        },
    },

    mounted() {
        // Check Here..
        this.selected = this.value;

        if (this.model.length) {
            if (eval(this.model) !== undefined) {
                this.selected = eval(this.model);
            } else {
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

        setSortOptions() {
            // Reset sort_options 
            this.sort_options = [];

            let created_options = (this.dynamicOptions) ? this.dynamicOptions : this.options;

            if (this.group) {
                // Option set sort_option data
                if (!Array.isArray(created_options)) {
                    for (const [index, options] of Object.entries(created_options)) {
                        let values = [];

                        for (const [key, value] of Object.entries(options)) {
                            values.push({
                                key: key,
                                value: value
                            });
                        }

                        this.sort_options.push({
                            key: index,
                            value: values
                        });
                    }
                } else {
                    created_options.forEach(function (option, index) {
                        if (typeof(option) == 'string') {
                            this.sort_options.push({
                                index: index,
                                key: index.toString(),
                                value: option
                            });
                        } else {
                            this.sort_options.push({
                                index: index,
                                key: option.id,
                                value: (option.title) ? option.title : (option.display_name) ? option.display_name : option.name
                            });
                        }
                    }, this);
                }
            } else {
                // Option set sort_option data
                if (!Array.isArray(created_options)) {
                    for (const [key, value] of Object.entries(created_options)) {
                        this.sort_options.push({
                            key: key,
                            value: value
                        });
                    }
                } else {
                    created_options.forEach(function (option, index) {
                        if (typeof(option) == 'string') {
                            this.sort_options.push({
                                index: index,
                                key: index.toString(),
                                value: option
                            });
                        } else {
                            this.sort_options.push({
                                index: index,
                                key: option.id,
                                value: (option.title) ? option.title : (option.display_name) ? option.display_name : option.name
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
                this.sort_options.forEach(function (option_group, group_index) {
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
                this.sort_options.forEach(function (option, index) {
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
                        //this.sort_options = [];

                        data.forEach(function (option) {
                            let check = false;

                            this.sort_options.forEach(function (sort_option) {
                                if (sort_option.key == option.id) {
                                    check = true;
                                    return;
                                }
                            });

                            if (!check) {
                                this.sort_options.push({
                                    key: option.id.toString(),
                                    value: (option.title) ? option.title : (option.display_name) ? option.display_name : option.name
                                });
                            }

                        }, this);

                        this.sort_options = this.sort_options.filter(item => {
                            return item.value.toLowerCase()
                                .indexOf(query.toLowerCase()) > -1;
                        });
                    } else {
                        this.sortOptions = [];
                    }
                })
                .catch(e => {
                })
                .finally(function () {
                    // always executed
                });
            } else {
                this.setSortOptions();
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
            this.setSortOptions();

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
                        template: '<div><akaunting-modal-add-new :show="add_new.show" @submit="onSubmit" @cancel="onCancel" :buttons="add_new.buttons" :title="add_new.text" :is_component=true :message="add_new.html"></akaunting-modal-add-new></div>',

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
                    this.sort_options.push({
                        key: response.data.data[this.add_new.field.key].toString(),
                        value: response.data.data[this.add_new.field.value],
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

        addModal() {

        },
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
            if (!this.multiple) {
                this.selected = selected.toString();
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

            this.change();
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
            this.sort_options = [];
            this.selected = [];

            if (this.group) {
                // Option set sort_option data
                if (!Array.isArray(options)) {
                    for (const [index, _options] of Object.entries(options)) {
                        let values = [];

                        for (const [key, value] of Object.entries(_options)) {
                            values.push({
                                key: key,
                                value: value
                            });
                        }

                        this.sort_options.push({
                            key: index,
                            value: values
                        });
                    }
                } else {
                    options.forEach(function (option, index) {
                        if (typeof(option) == 'string') {
                            this.sort_options.push({
                                index: index,
                                key: index.toString(),
                                value: option
                            });
                        } else {
                            this.sort_options.push({
                                index: index,
                                key: option.id,
                                value: (option.title) ? option.title : (option.display_name) ? option.display_name : option.name
                            });
                        }
                    }, this);
                }
            } else {
                // Option set sort_option data
                if (!Array.isArray(options)) {
                    for (const [key, value] of Object.entries(options)) {
                        this.sort_options.push({
                            key: key,
                            value: value
                        });
                    }
                } else {
                    options.forEach(function (option, index) {
                        if (typeof(option) == 'string') {
                            this.sort_options.push({
                                index: index,
                                key: index.toString(),
                                value: option
                            });
                        } else {
                            this.sort_options.push({
                                index: index,
                                key: option.id,
                                value: (option.title) ? option.title : (option.display_name) ? option.display_name : option.name
                            });
                        }
                    }, this);
                }
            }
        },
    },
}
</script>

<style scoped>
    .form-group .modal {
        z-index: 3050;
    }

    .el-select-dropdown__empty {
        padding: 10px 0 0 !important;
    }

    .el-select-dropdown__empty.loading {
        padding: 10px 0 !important;
    }

    .el-select__footer {
        text-align: center !important;
        border-top: 1px solid #dee2e6 !important;
        padding: 0px !important;
        cursor: pointer !important;
        color: #3c3f72 !important;
        font-weight: bold !important;
        height: 38px !important;
        line-height: 38px !important;
        margin-top: 5px !important;
        margin-bottom: -6px !important;
        border-bottom-left-radius: 4px !important;
        border-bottom-right-radius: 4px !important;
    }

    .el-select__footer.el-select-dropdown__item.hover {
        background-color: inherit !important;
    }

    .el-select__footer.el-select-dropdown__item:hover, .el-select__footer.el-select-dropdown__item:focus {
        background-color: #3c3f72 !important;
        color: white !important;
        border-top-color: #3c3f72;
    }

    .el-select__footer div span {
        margin-left: 5px;
    }

    .badge-resize {
        float: right;
        margin-top: -32px;
        margin-right: 35px;
        position: relative;
    }

    .badge.badge-pill.badge-success {
        border-radius: 0.375rem;
    }
</style>
