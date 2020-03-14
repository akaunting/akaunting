<template>
    <base-input
        v-if="title"
        :label="title"
        :name="name"
        :readonly="readonly"
        :disabled="disabled"
        :class="[
            {'readonly': readonly},
            {'disabled': disabled},
            formClasses
        ]"
        :error="formError">
        <el-select v-model="real_model" @input="change" disabled filterable v-if="disabled"
            :placeholder="placeholder">
            <div v-if="addNew.status && options.length != 0" class="el-select-dropdown__wrap" slot="empty">
                <p class="el-select-dropdown__empty">
                    {{ noMatchingDataText }}
                </p>
                <ul class="el-scrollbar__view el-select-dropdown__list">
                    <li class="el-select-dropdown__item el-select__footer">
                        <div @click="onAddItem">
                            <i class="fas fa-plus"></i>
                            <span>
                                {{ add_new_text }}
                            </span>
                        </div>
                    </li>
                </ul>
            </div>

            <div v-else-if="addNew.status && options.length == 0" slot="empty">
                <p class="el-select-dropdown__empty">
                    {{ noDataText }}
                </p>
                <ul class="el-scrollbar__view el-select-dropdown__list">
                    <li class="el-select-dropdown__item el-select__footer">
                        <div @click="onAddItem">
                            <i class="fas fa-plus"></i>
                            <span>
                                {{ add_new_text }}
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

            <el-option v-if="!group" v-for="(label, value) in selectOptions"
               :key="value"
               :label="label"
               :value="value">
                <span class="float-left">{{ label }}</span>
                <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
            </el-option>

            <el-option-group
                v-if="group"
                v-for="(options, name) in selectOptions"
                :key="name"
                :label="name">
                <el-option
                    v-for="(label, value) in options"
                    :key="value"
                    :label="label"
                    :value="value">
                    <span class="float-left">{{ label }}</span>
                    <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
                </el-option>
            </el-option-group>

            <el-option v-if="addNew.status && options.length != 0" class="el-select__footer" :disabled="true" :value="add_new">
                <div @click="onAddItem">
                    <i class="fas fa-plus"></i>
                    <span>
                        {{ add_new_text }}
                    </span>
                </div>
            </el-option>
        </el-select>

        <el-select v-model="real_model" @input="change" filterable v-if="!disabled && !multiple"
            :placeholder="placeholder">
            <div v-if="addNew.status && options.length != 0" class="el-select-dropdown__wrap" slot="empty">
                <p class="el-select-dropdown__empty">
                    {{ noMatchingDataText }}
                </p>
                <ul class="el-scrollbar__view el-select-dropdown__list">
                    <li class="el-select-dropdown__item el-select__footer">
                        <div @click="onAddItem">
                            <i class="fas fa-plus"></i>
                            <span>
                                {{ add_new_text }}
                            </span>
                        </div>
                    </li>
                </ul>
            </div>

            <div v-else-if="addNew.status && options.length == 0" slot="empty">
                <p class="el-select-dropdown__empty">
                    {{ noDataText }}
                </p>
                <ul class="el-scrollbar__view el-select-dropdown__list">
                    <li class="el-select-dropdown__item el-select__footer">
                        <div @click="onAddItem">
                            <i class="fas fa-plus"></i>
                            <span>
                                {{ add_new_text }}
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

            <el-option v-if="!group" v-for="(label, value) in selectOptions"
               :key="value"
               :label="label"
               :value="value">
                <span class="float-left">{{ label }}</span>
                <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
            </el-option>

            <el-option-group
                v-if="group"
                v-for="(options, name) in selectOptions"
                :key="name"
                :label="name">
                <el-option
                    v-for="(label, value) in options"
                    :key="value"
                    :label="label"
                    :value="value">
                    <span class="float-left">{{ label }}</span>
                    <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
                </el-option>
            </el-option-group>

            <el-option v-if="addNew.status && options.length != 0" class="el-select__footer" :disabled="true" :value="add_new">
                <div @click="onAddItem">
                    <i class="fas fa-plus"></i>
                    <span>
                        {{ add_new_text }}
                    </span>
                </div>
            </el-option>
        </el-select>

        <el-select v-model="real_model" @input="change" filterable v-if="!disabled && multiple && !collapse" multiple
            :placeholder="placeholder">
            <div v-if="addNew.status && options.length != 0" class="el-select-dropdown__wrap" slot="empty">
                <p class="el-select-dropdown__empty">
                    {{ noMatchingDataText }}
                </p>
                <ul class="el-scrollbar__view el-select-dropdown__list">
                    <li class="el-select-dropdown__item el-select__footer">
                        <div @click="onAddItem">
                            <i class="fas fa-plus"></i>
                            <span>
                                {{ add_new_text }}
                            </span>
                        </div>
                    </li>
                </ul>
            </div>

            <div v-else-if="addNew.status && options.length == 0" slot="empty">
                <p class="el-select-dropdown__empty">
                    {{ noDataText }}
                </p>
                <ul class="el-scrollbar__view el-select-dropdown__list">
                    <li class="el-select-dropdown__item el-select__footer">
                        <div @click="onAddItem">
                            <i class="fas fa-plus"></i>
                            <span>
                                {{ add_new_text }}
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

            <el-option v-if="!group" v-for="(label, value) in selectOptions"
               :key="value"
               :label="label"
               :value="value">
                <span class="float-left">{{ label }}</span>
                <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
            </el-option>

            <el-option-group
                v-if="group"
                v-for="(options, name) in selectOptions"
                :key="name"
                :label="name">
                <el-option
                    v-for="(label, value) in options"
                    :key="value"
                    :label="label"
                    :value="value">
                    <span class="float-left">{{ label }}</span>
                    <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
                </el-option>
            </el-option-group>

            <el-option v-if="addNew.status && options.length != 0" class="el-select__footer" :disabled="true" :value="add_new">
                <div @click="onAddItem">
                    <i class="fas fa-plus"></i>
                    <span>
                        {{ add_new_text }}
                    </span>
                </div>
            </el-option>
        </el-select>

        <el-select v-model="real_model" @input="change" filterable v-if="!disabled && multiple && collapse" multiple collapse-tags
            :placeholder="placeholder">
            <div v-if="addNew.status && options.length != 0" class="el-select-dropdown__wrap" slot="empty">
                <p class="el-select-dropdown__empty">
                    {{ noMatchingDataText }}
                </p>
                <ul class="el-scrollbar__view el-select-dropdown__list">
                    <li class="el-select-dropdown__item el-select__footer">
                        <div @click="onAddItem">
                            <i class="fas fa-plus"></i>
                            <span>
                                {{ add_new_text }}
                            </span>
                        </div>
                    </li>
                </ul>
            </div>

            <div v-else-if="addNew.status && options.length == 0" slot="empty">
                <p class="el-select-dropdown__empty">
                    {{ noDataText }}
                </p>
                <ul class="el-scrollbar__view el-select-dropdown__list">
                    <li class="el-select-dropdown__item el-select__footer">
                        <div @click="onAddItem">
                            <i class="fas fa-plus"></i>
                            <span>
                                {{ add_new_text }}
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

            <el-option v-if="!group" v-for="(label, value) in selectOptions"
               :key="value"
               :label="label"
               :value="value">
                <span class="float-left">{{ label }}</span>
                <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
            </el-option>

            <el-option-group
                v-if="group"
                v-for="(options, name) in selectOptions"
                :key="name"
                :label="name">
                <el-option
                    v-for="(label, value) in options"
                    :key="value"
                    :label="label"
                    :value="value">
                    <span class="float-left">{{ label }}</span>
                    <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
                </el-option>
            </el-option-group>

            <el-option v-if="addNew.status && options.length != 0" class="el-select__footer" :disabled="true" :value="add_new">
                <div @click="onAddItem">
                    <i class="fas fa-plus"></i>
                    <span>
                        {{ add_new_text }}
                    </span>
                </div>
            </el-option>
        </el-select>

        <component v-bind:is="add_new_html" @submit="onSubmit"></component>

        <select :name="name" v-model="real_model" class="d-none">
            <option v-for="(label, value) in selectOptions" :key="value" :value="value">{{ label }}</option>
        </select>

        <span slot="infoBlock" class="badge badge-success badge-resize float-right" v-if="new_options[real_model]">{{ new_text }}</span>
    </base-input>

    <span v-else>
        <el-select
            :class="'pl-20 mr-40'"
            v-model="real_model"
            @input="change"
            filterable
            remote
            reserve-keyword
            :placeholder="placeholder"
            :remote-method="remoteMethod"
            :loading="loading">
                <div v-if="loading" class="el-select-dropdown__wrap" slot="empty">
                    <p class="el-select-dropdown__empty loading">
                        {{ loadingText }}
                    </p>
                </div>

                <div v-else-if="addNew.status && options.length != 0" class="el-select-dropdown__wrap" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noMatchingDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div v-else-if="addNew.status && options.length == 0" slot="empty">
                    <p class="el-select-dropdown__empty">
                        {{ noDataText }}
                    </p>
                    <ul class="el-scrollbar__view el-select-dropdown__list">
                        <li class="el-select-dropdown__item el-select__footer">
                            <div @click="onAddItem">
                                <i class="fas fa-plus"></i>
                                <span>
                                    {{ add_new_text }}
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

                <el-option v-if="!group" v-for="option in selectOptions"
                :key="option.id"
                :label="option.name"
                :value="option.id">
                    <span class="float-left">{{ option.name }}</span>
                    <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[option.id]">{{ new_text }}</span>
                </el-option>

                <el-option-group
                    v-if="group"
                    v-for="(options, name) in selectOptions"
                    :key="name"
                    :label="name">
                    <el-option
                        v-for="(label, value) in options"
                        :key="value"
                        :label="label"
                        :value="value">
                        <span class="float-left">{{ label }}</span>
                        <span class="badge badge-pill badge-success float-right mt-2" v-if="new_options[value]">{{ new_text }}</span>
                    </el-option>
                </el-option-group>

                <el-option v-if="!loading && addNew.status && selectOptions != null && selectOptions.length != 0" class="el-select__footer" :disabled="true" :value="add_new">
                    <div @click="onAddItem">
                        <i class="fas fa-plus"></i>
                        <span>
                            {{ add_new_text }}
                        </span>
                    </div>
                </el-option>
        </el-select>

        <span class="badge badge-success badge-resize float-right mr-2" v-if="new_options[real_model]">{{ new_text }}</span>
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
        name: {
            type: String,
            default: null,
            description: "Selectbox attribute name"
        },
        value: {
            type: [String, Number, Array],
            default: null,
            description: "Selectbox selected value"
        },
        options: null,

        model: {
            type: [String, Number],
            default: '',
            description: "Selectbox selected model"
        },

        icon: {
            type: String,
            description: "Prepend icon (left)"
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

        group:  {
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
        remoteType: {
            type: String,
            default: 'invoice',
            description: "Ger remote item type."
        },
        currencyCode: {
            type: String,
            default: 'USD',
            description: "Get remote item price currecy code"
        },
    },

    data() {
        return {
            list: [],
            add_new: {
                text: this.addNew.text,
                show: false,
                path: this.addNew.path,
                type: this.addNew.type, // modal, inline
                field: this.addNew.field,
                buttons: this.addNew.buttons
            },
            add_new_text: this.addNew.text,
            new_text: this.addNew.new_text,
            selectOptions: this.options,
            real_model: this.model,
            add_new_html: '',
            form: {},
            loading: false,
            new_options: false,
        }
    },

    created() {
        this.new_options = {};
    },

    mounted() {
        this.real_model = this.value;

        if (this.multiple && !this.real_model.length) {
            this.real_model = [];
        }

        this.$emit('interface', this.real_model);
    },

    methods: {
        remoteMethod(query) {
            if (query !== '') {
                this.loading = true;

               if (!this.remoteAction) {
                   this.remoteAction = url + '/common/items/autocomplete';
               }

                window.axios({
                    method: 'GET',
                    url: this.remoteAction,
                    params: {
                        type: this.remoteType,
                        query: query,
                        currency_code: this.currencyCode,
                    },
                    headers: {
                        'X-CSRF-TOKEN': window.Laravel.csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    this.loading = false;

                    if (response.data.data) {
                        this.selectOptions = response.data.data;
                    } else {
                        this.selectOptions = [];
                    }
                })
                .catch(e => {
                })
                .finally(function () {
                    // always executed
                });
            } else {
                this.selectOptions = this.options;
            }
        },

        change() {
            if (typeof(this.real_model) === 'object') {
                return false;
            }

            this.$emit('interface', this.real_model);
            this.$emit('change', this.real_model);

            this.selectOptions.forEach(item => {
                if (item.id == this.real_model) {
                    this.$emit('label', item.name);
                    this.$emit('option', item);

                    return true;
                }
            });
        },

        onPressEnter() {
            alert('Press Enter');
        },

        OnPressTab() {
            alert('Press Tab');
        },

        async onAddItem() {
            // Get Select Input value
            if (this.title) {
                var value = this.$children[0].$children[0].$children[0].$refs.input.value;
            } else {
                var value = this.$children[0].$children[0].$refs.input.value;
            }

            if (value === '') {
                return false;
            }

            if (this.add_new.type == 'inline') {
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
                    if (!Object.keys(this.options).length) {
                        this.selectOptions =  [];
                    }

                    this.selectOptions.push(response.data.data);
                    //this.selectOptions[response.data.data[this.add_new.field.key]] = response.data.data[this.add_new.field.value];
                    this.new_options[response.data.data[this.add_new.field.key]] = response.data.data[this.add_new.field.value];
                    this.real_model = response.data.data[this.add_new.field.key];//.toString();

                    this.add_new.show = false;

                    this.add_new.html = '';
                    this.add_new_html = null;

                    this.$emit('new', response.data.data);

                    this.change();
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
        },

        addModal() {

        },
    },

    watch: {
        options: function (options) {
            // update options
            this.selectOptions = options;

            if (Object.keys(this.new_options).length) {
                if (!Object.keys(this.options).length) {
                    this.selectOptions =  [];
                }

                Object.values(this.new_options).forEach(item => {
                    this.selectOptions.push(item);
                });
            }
        },

        value: function (value) {
            if (this.multiple) {
                this.real_model = value;
            } else {
                //this.real_model = value.toString();
                this.real_model = value;
            }
        },

        model: function (value) {
            if (this.multiple) {
                this.real_model = value;
            } else {
                this.real_model = value;
            }
        }
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
        text-align: center;
        border-top: 1px solid #dee2e6;
        padding: 0px;
        cursor: pointer;
        color: #3c3f72;
        font-weight: bold;
        height: 38px;
        line-height: 38px;
        margin-top: 5px;
        margin-bottom: -6px;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
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
</style>
