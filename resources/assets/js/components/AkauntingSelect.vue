<template>
    <base-input :label="title"
            :name="name"
            :class="formClasses"
            :error="formError">
        <el-select v-model="real_model" @input="change" filterable v-if="!multiple"
            :placeholder="placeholder">
            <div v-if="addNew" class="el-select-dropdown__wrap" slot="empty">
                <ul class="el-scrollbar__view el-select-dropdown__list">
                    <li class="el-select-dropdown__item hover" @click="onAddItem">
                        <span>{{ add_new_text }}</span>
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
        </el-select>


        <el-select v-model="real_model" @input="change" filterable v-if="multiple" multiple collapse-tags
            :placeholder="placeholder">
            <div v-if="addNew" class="el-select-dropdown__wrap" slot="empty">
                <ul class="el-scrollbar__view el-select-dropdown__list">
                    <li class="el-select-dropdown__item hover" @click="onAddItem">
                        <span>{{ add_new_text }}</span>
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
        </el-select>
    </base-input>
</template>

<script>
import { Select, Option, OptionGroup } from 'element-ui';

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
        addNew: false,
        addNewText: null,
        addNewPath: null,
        group: false,
        multiple:false
    },

    data() {
        return {
            add_new_text: this.addNewText,
            selectOptions: this.options,
            real_model: this.model,
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

            this.$emit('new_item', {
                value: value,
                path: this.addNewPath,
                title: this.addNewText,
            });
        }
    },

    watch: {
        options: function (options) {
            // update options
            this.selectOptions = options;
        }
    },
}
</script>
