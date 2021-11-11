<template>
    <div class="row pr-0" :class="formClasses">
        <base-input :label="title"
            name="recurring_frequency"
            :class="frequencyClasses"
            :error="frequencyError">
            <template slot="label">
                <label v-if="title" :class="labelClasses">
                    {{ title }}
                    <el-tooltip class="item" effect="dark" placement="top-start">
                        <div slot="content" v-html="tooltip"></div>
                        <i class="far fa-question-circle fa-xs" style="vertical-align: top;"></i>
                    </el-tooltip>
                </label>
            </template>
            <el-select v-model="recurring_frequency" @input="change" filterable
                :placeholder="placeholder">
                <template slot="prefix">
                    <span class="el-input__suffix-inner el-select-icon">
                        <i :class="'el-input__icon el-icon-refresh'"></i>
                    </span>
                </template>

                <el-option v-for="(label, value) in frequencyOptions"
                :key="label"
                :label="label"
                :value="value">
                </el-option>
            </el-select>
        </base-input>

        <base-input :label="titleInterval"
            name="recurring_interval"
            type="number"
            :value="0"
            @input="change"
            :class="invertalClasses"
            :error="intervalError"
            v-model="recurring_interval"
            >
        </base-input>

        <base-input :label="titleFrequency"
            name="recurring_custom_frequency"
            :class="customFrequencyClasses"
            :error="customFrequencyError">
            <el-select v-model="recurring_custom_frequency" @input="change" filterable
                :placeholder="placeholder">
                <el-option v-for="(label, value) in customFrequencyOptions"
                :key="label"
                :label="label"
                :value="value">
                </el-option>
            </el-select>
        </base-input>

        <base-input :label="titleCount"
            name="recurring_count"
            type="number"
            :value="0"
            @input="change"
            :class="countClasses"
            :error="countError"
            v-model="recurring_count">
        </base-input>
    </div>
</template>

<script>
import { Select, Option, Tooltip } from 'element-ui'

export default {
    name: 'akaunting-recurring',

    components: {
        [Select.name]: Select,
        [Option.name]: Option,
        [Tooltip.name]: Tooltip,
    },

    props: {
        title: {
            type: String,
            default: '',
            description: "Modal header title"
        },
        titleInterval: {
            type: String,
            default: '',
            description: "Title of interval"
        },
        titleFrequency: {
            type: String,
            default: '',
            description: "Title of frequency"
        },
        titleCount: {
            type: String,
            default: '',
            description: "Title of count"
        },
        tooltip: {
            type: String,
            default: '',
            description: "Tooltip message"
        },
        placeholder: {
            type: String,
            default: '',
            description: "Modal header title"
        },
        formClasses: {
            default: 'col-md-6',
        },
        formError: null,

        frequencyOptions: null,
        frequencyValue: null,
        frequencyError: null,

        intervalValue: {
            type: [Number, String],	
            default: 0,	
            description: "Default interval value"	
        },
        intervalError: null,

        customFrequencyOptions: null,
        customFrequencyValue: null,
        customFrequencyError: null,

        countValue: {
            type: [Number, String],	
            default: 0,	
            description: "Default count value"	
        },
        countError: null,

        icon: {
            type: String,
            description: "Prepend icon (left)"
        },
        labelClasses: {
            type: String,
            description: "Input label css classes",
            default: "form-control-label"
        },
    },

    data() {
        return {
            recurring_frequency: null,
            recurring_interval: null,
            recurring_custom_frequency: null,
            recurring_count: null,
            frequencyClasses: 'col-md-12',
            invertalClasses: 'col-md-2 d-none',
            customFrequencyClasses: 'col-md-4 d-none',
            countClasses: 'col-md-2 d-none'
        }
    },

    created() {	
        this.recurring_frequency = this.frequencyValue;	
        this.recurring_interval = this.intervalValue;	
        this.recurring_custom_frequency = this.customFrequencyValue;	
        this.recurring_count = this.countValue;	
    },

    mounted() {
        this.recurring_frequency = this.frequencyValue;

        if (this.recurring_frequency != 'custom') {
            this.recurring_custom_frequency = '';
            this.recurring_interval = '0';
        }

        this.frequencyChanges();

        this.$emit('recurring_frequency', this.recurring_frequency);
        this.$emit('recurring_interval', this.recurring_interval);
        this.$emit('recurring_custom_frequency', this.recurring_custom_frequency);
        this.$emit('recurring_count', this.recurring_count);
    },

    methods: {
        change() {
            this.$emit('change', this.recurring_frequency);

            this.$emit('recurring_frequency', this.recurring_frequency);
            this.$emit('recurring_interval', this.recurring_interval);
            this.$emit('recurring_custom_frequency', this.recurring_custom_frequency);
            this.$emit('recurring_count', this.recurring_count);

            this.frequencyChanges();
        },

        frequencyChanges() {
            if (this.recurring_frequency == 'custom') {
                this.frequencyClasses = 'col-md-4';
                this.invertalClasses = 'col-md-2';
                this.customFrequencyClasses = 'col-md-4';
                this.countClasses = 'col-md-2 pr-0';
            } else if (this.recurring_frequency == 'no' || this.recurring_frequency == '') {
                this.frequencyClasses = 'col-md-12 pr-0';
                this.invertalClasses = 'col-md-2 d-none';
                this.customFrequencyClasses = 'col-md-4 d-none';
                this.countClasses = 'col-md-2 d-none';
            } else {
                this.frequencyClasses = 'col-md-10';
                this.invertalClasses = 'col-md-2 d-none';
                this.customFrequencyClasses = 'col-md-4 d-none';
                this.countClasses = 'col-md-2 pr-0';
            }
        }
    }
}
</script>
