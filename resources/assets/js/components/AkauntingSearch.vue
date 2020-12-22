<template>

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

        <div v-else-if="options.length != 0" class="el-select-dropdown__wrap" slot="empty">
            <p class="el-select-dropdown__empty">
                {{ noMatchingDataText }}
            </p>
        </div>

        <div v-else-if="options.length == 0" slot="empty">
            <p class="el-select-dropdown__empty">
                {{ noDataText }}
            </p>
        </div>

        <template v-if="icon" slot="prefix">
            <span class="el-input__suffix-inner el-select-icon">
                <i :class="'select-icon-position el-input__icon fa fa-' + icon"></i>
            </span>
        </template>

        <el-option v-if="!group" v-for="option in selectOptions"
            :key="option.id"
            :label="option.name"
            :value="option.id">
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

</template>

<style>
    .el-select {
        display: inline;
    }
</style>

<script>
    import { Select, Option, OptionGroup } from 'element-ui';

    export default {
        name: 'akaunting-select',

        components: {
            [Select.name]: Select,
            [Option.name]: Option,
            [OptionGroup.name]: OptionGroup,
        },

        props: {
            name: {
                type: String,
                default: null,
                description: "Selectbox attribute name"
            },
            placeholder: {
                type: String,
                default: '',
                description: "Selectbox input placeholder text"
            },
            options: null,
            value: {
                type: String,
                default: null,
                description: "Selectbox selected value"
            },

            icon: {
                type: String,
                description: "Prepend icon (left)"
            },

            group:  {
                type: Boolean,
                default: false,
                description: "Selectbox option group status"
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
        },

        data() {
            return {
                list: [],
                selectOptions: this.options,
                real_model: this.model,
                loading: false,
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

                this.selectOptions.forEach(item => {
                    if (item.id == this.real_model) {
                        this.$emit('label', item.name);
                        this.$emit('option', item);

                        return;
                    }
                });
            },
            remoteMethod() {

            },
        },

        watch: {
            options: function (options) {
                // update options
                //this.selectOptions = options;
            }
        },
    }
</script>
