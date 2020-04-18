<template>
    <div class="row col-md-6 pr-0">
        <base-input :label="title"
                name="recurring_frequency"
                :class="frequencyClasses"
                :error="frequencyError">
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

        <base-input :label="''"
            name="recurring_interval"
            type="number"
            :value="0"
            class="recurring-single"
            :class="invertalClasses"
            :error="intervalError"
            v-model="recurring_interval"
            @input="change"
        >
        </base-input>

        <base-input :label="''"
            name="recurring_custom_frequency"
            class="recurring-single"
            :class="customFrequencyClasses"
            :error="customFrequencyError"
        >
            <el-select v-model="recurring_custom_frequency" @input="change" filterable
                :placeholder="placeholder">
                <el-option v-for="(label, value) in customFrequencyOptions"
                :key="label"
                :label="label"
                :value="value">
                </el-option>
            </el-select>
        </base-input>

        <base-input :label="''"
            name="recurring_count"
            type="number"
            class="recurring-single"
            :class="countClasses"
            :error="countError"
            v-model="recurring_count"
            @input="change"
        >
        </base-input>
    </div>
</template>

<script>
import { Select, Option } from 'element-ui'

export default {
    name: 'akaunting-recurring',

    components: {
        [Select.name]: Select,
        [Option.name]: Option,
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
        }
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
