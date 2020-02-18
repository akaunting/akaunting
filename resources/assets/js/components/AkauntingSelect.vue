<template>
    <base-input
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

        <el-select v-model="real_model" @input="change" :disabled="disabled" filterable v-if="(disabled) && !multiple && !collapse"
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
                </el-option>
            </el-option-group>

            <el-option v-if="addNew.status && options.length != 0" class="el-select__footer" :value="add_new">
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
                </el-option>
            </el-option-group>

            <el-option v-if="addNew.status && options.length != 0" class="el-select__footer" :value="add_new">
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
                </el-option>
            </el-option-group>

            <el-option v-if="addNew.status && options.length != 0" class="el-select__footer" :value="add_new">
                <div @click="onAddItem">
                    <i class="fas fa-plus"></i>
                    <span>
                        {{ add_new_text }}
                    </span>
                </div>
            </el-option>
        </el-select>

        <el-select v-model="real_model" @input="change" filterable disabled v-if="disabled && multiple && !collapse" multiple
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
                </el-option>
            </el-option-group>

            <el-option v-if="addNew.status && options.length != 0" class="el-select__footer" :value="add_new">
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
                </el-option>
            </el-option-group>

            <el-option v-if="addNew.status && options.length != 0" class="el-select__footer" :value="add_new">
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
            <option v-for="(label, value) in selectOptions" :value="value">{{ label }}</option>
        </select>
    </base-input>
</template>

<script>
import Vue from 'vue';

import { Select, Option, OptionGroup, ColorPicker } from 'element-ui';

import AkauntingModalAddNew from './AkauntingModalAddNew';
import AkauntingModal from './AkauntingModal';
import AkauntingRadioGroup from './forms/AkauntingRadioGroup';
import AkauntingSelect from './AkauntingSelect';
import AkauntingDate from './AkauntingDate';
import AkauntingRecurring from './AkauntingRecurring';

import Form from './../plugins/form';

export default {
    name: "akaunting-select",

    components: {
        [Select.name]: Select,
        [Option.name]: Option,
        [OptionGroup.name]: OptionGroup,
        [ColorPicker.name]: ColorPicker,
        AkauntingModalAddNew,
        AkauntingRadioGroup,
        AkauntingSelect,
        AkauntingModal,
        AkauntingDate,
        AkauntingRecurring,
    },

    props: {
        title: {
            type: String,
            default: '',
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
            default: '',
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

        noDataText: {
            type: String,
            default: 'No Data',
            description: "Selectbox empty options message"
        },
        noMatchingDataText: {
            type: String,
            default: 'No Matchign Data',
            description: "Selectbox search option not found item message"
        }
    },

    data() {
        return {
            add_new: this.addNew,
            add_new_text: this.addNew.text,
            selectOptions: this.options,
            real_model: this.model,
            add_new_html: '',
            form: {},
        }
    },

    mounted() {
        this.real_model = this.value;

        this.$emit('interface', this.real_model);
    },

    methods: {
        change() {
            this.$emit('change', this.real_model);
            this.$emit('interface', this.real_model);
        },

        onAddItem() {
            // Get Select Input value
            var value = this.$children[0].$children[0].$children[0].$refs.input.value;

            if (this.add_new.type == 'inline') {
                this.addInline(value);
            } else {
                this.onModal(value);
            }
        },

        addInline(value) {

        },

        onModal(value) {
            let add_new = this.add_new;

            axios.get(this.add_new.path)
            .then(response => {
                add_new.status = true;
                add_new.html = response.data.html;

                this.$children[0].$children[0].visible = false;

                this.add_new_html = Vue.component('add-new-component', function (resolve, reject) {
                    resolve({
                        template: '<div><akaunting-modal-add-new :show="add_new.status" @submit="onSubmit" @cancel="add_new.status = false" :buttons="add_new.buttons" :title="add_new.text" :is_component=true :message="add_new.html"></akaunting-modal-add-new></div>',

                        components: {
                            AkauntingModalAddNew,
                            AkauntingRadioGroup,
                            AkauntingSelect,
                            AkauntingModal,
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

            axios.post(this.form.action, this.form.data())
            .then(response => {
                this.form.loading = false;

                if (response.data.success) {
                    this.selectOptions[response.data.data.id] = response.data.data['name'];
                    this.real_model = response.data.data.id.toString();

                    this.change();

                    //this.add_new.status = false;
                }
            })
            .catch(error => {
                this.form.loading = false;

                this.form.onFail(error);

                this.method_show_html = error.message;
            });
        },

        addModal() {

        },
    },

    watch: {
        options: function (options) {
            // update options
            this.selectOptions = options;
        },

        value: function (value) {
            if (this.multiple) {
                this.real_model = value;
            } else {
                this.real_model = value.toString();
            }
        },

        model: function (value) {
            if (this.multiple) {
                this.real_model = value;
            } else {
                this.real_model = value.toString();
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
</style>
