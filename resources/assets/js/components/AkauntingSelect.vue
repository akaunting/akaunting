<template>
    <base-input :label="title"
            :name="name"
            :class="formClasses"
            :error="formError">

        <el-select v-model="real_model" @input="change" disabled filterable v-if="disabled"
            :placeholder="placeholder">
            <div v-if="addNew.status && selectOptions.lenght > 0" class="el-select-dropdown__wrap" slot="empty">
                <span></span>
            </div>
            <div v-if="selectOptions.lenght <= 0" class="el-select-dropdown__wrap" slot="empty">
                <span>
                    <li v-if="addNew.status" class="el-select-dropdown__item hover" @click="onAddItem">
                        <span>{{ add_new_text }}</span>
                    </li>
                </span>
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

            <li v-if="addNew.status" class="el-select-dropdown__item hover" @click="onAddItem">
                <span>{{ add_new_text }}</span>
            </li>
        </el-select>

        <el-select v-model="real_model" @input="change" filterable v-if="!disabled && !multiple"
            :placeholder="placeholder">
            <div v-if="addNew.status" class="el-select-dropdown__wrap" slot="empty">
                <span></span>
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

            <li v-if="addNew.status" class="el-select-dropdown__item hover" @click="onAddItem">
                <span>{{ add_new_text }}</span>
            </li>
        </el-select>

        <el-select v-model="real_model" @input="change" filterable v-if="!disabled && multiple && !collapse" multiple
            :placeholder="placeholder">
            <div v-if="addNew.status" class="el-select-dropdown__wrap" slot="empty">
                <span></span>
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

            <li v-if="addNew.status" class="el-select-dropdown__item hover" @click="onAddItem">
                <span>{{ add_new_text }}</span>
            </li>
        </el-select>

        <el-select v-model="real_model" @input="change" filterable v-if="!disabled && multiple && collapse" multiple collapse-tags
            :placeholder="placeholder">
            <div v-if="addNew.status" class="el-select-dropdown__wrap" slot="empty">
                <span></span>
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

            <li v-if="addNew.status" class="el-select-dropdown__item hover" @click="onAddItem">
                <span>{{ add_new_text }}</span>
            </li>
        </el-select>

        <component v-bind:is="add_new_html" @interface="onRedirectConfirm"></component>
    </base-input>
</template>

<script>
import { Select, Option, OptionGroup } from 'element-ui';

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
    },

    props: {
        title: {
            type: String,
            default: '',
            description: "Modal header title"
        },
        placeholder: {
            type: String,
            default: '',
            description: "Modal header title"
        },
        formClasses: null,
        formError: null,
        name: null,
        value: null,
        options: null,
        model: null,
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
                    field: 'name'
                };
            },
            description: "Selectbox Add New Item Feature"
        },

        group: false,
        multiple:false,
        disabled:false,
        collapse: false
    },

    data() {
        return {
            add_new_text: this.addNew.text,
            selectOptions: this.options,
            real_model: this.model,
            add_new_html: '',
        }
    },

    mounted() {
        this.real_model = this.value;

        this.$emit('interface', this.real_model);
    },

    methods: {
        change() {
            alert('Helooo');

            this.$emit('change', this.real_model);
            this.$emit('interface', this.real_model);
        },

        onAddItem() {
            // Get Select Input value
            var value = this.$children[0].$children[0].$children[0].$refs.input.value;

            if (this.addNew.type == 'inline') {
                this.addInline(value);
            } else {
                this.onModal(value);
            }
            /*
            this.$emit('new_item', {
                value: value,
                path: this.addNew.path,
                title: this.addNew.text,
            });
            */
        },

        addInline(value) {

        },

        onModal(value) {
            let add_new = this.addNew;

            axios.get(this.addNew.path)
            .then(response => {
                add_new.modal = true;
                add_new.html = response.data.html;

                this.add_new_html = Vue.component('add-new-component', function (resolve, reject) {
                    resolve({
                        template: '<div><akaunting-modal :show="addNew.modal" @cancel="addNew.modal = false" :title="addNew.text" :message="addNew.html">' +
                        +    '<template #card-footer>'
                        +        '<div class="float-right">'
                        +            '<button type="button" class="btn btn-outline-secondary">'
                        +                '<span>Cancel</span>'
                        +            '</button>'
                        +            '<button type="button" class="btn btn-success button-submit">'
                        +                '<div class="aka-loader d-none"></div>'
                        +                '<span>Confirm</span>'
                        +            '</button>'
                        +        '</div>'
                        +    '</template>'
                        + '</akaunting-modal></div>',

                        components: {
                            AkauntingRadioGroup,
                            AkauntingSelect,
                            AkauntingModal,
                            AkauntingDate,
                            AkauntingRecurring,
                        },

                        data: function () {
                            return {
                                form:  new Form('form-create-category'),
                                addNew: add_new,
                            }
                        },

                        methods: {
                            onRedirectConfirm() {
                                let redirect_form = new Form('redirect-form');

                                this.$emit('interface', redirect_form);
                            }
                        }
                    })
                });

                /*
                this.selectOptions[3] = value;

                let newOption = {
                    value: "3",
                    currentLabel: value,
                    label: value
                };

                this.$children[0].$children[0].handleOptionSelect(newOption);
                this.$children[0].$children[0].onInputChange('3');

                this.real_model = "3";

                this.$emit('change', this.real_model);
                */
            })
            .catch(e => {
                this.errors.push(e);
            })
            .finally(function () {
                // always executed
            });
        },

        onRedirectConfirm() {
            this.redirectForm = new Form('redirect-form');

            axios.post(this.redirectForm.action, this.redirectForm.data())
            .then(response => {
                if (response.data.redirect) {
                    location = response.data.redirect;
                }

                if (response.data.success) {
                    location.reload();
                }
            })
            .catch(error => {
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
        }
    },
}
</script>
