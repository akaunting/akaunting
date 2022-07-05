<template>
    <div class="sm:col-span-6 space-y-8 sm:space-y-2">
        <div class="flex items-center" :class="{ 'justify-between sm:justify-start': frequency == 'custom' }">
            <div class="w-60 px-2 text-sm">
                {{ frequencyText }}
            </div>

            <el-select class="w-36" v-model="frequency" @input="change">
                <el-option
                v-for="(label, value) in frequencies"
                :key="value"
                :label="label"
                :value="value">
                </el-option>
            </el-select>

            <div class="w-20 px-2 text-sm text-center" v-if="frequency == 'custom'">
                {{ frequencyEveryText }}
            </div>

            <input type="text" class="w-20 text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple" v-model="interval" @input="change" v-if="frequency == 'custom'">

            <el-select class="w-36 ml-2" v-model="customFrequency" @input="change" v-if="frequency == 'custom'">
                <el-option
                v-for="(label, value) in customFrequencies"
                :key="value"
                :label="label"
                :value="value">
                </el-option>
            </el-select>
        </div>

        <div class="flex items-center" :class="{ 'justify-between sm:justify-start': limit !== 'never' }">
            <div class="w-60 px-2 text-sm">
                {{ startText }}
            </div>

            <el-date-picker
                class="w-36 recurring-invoice-data"
                v-model="started_at"
                @input="change"
                type="date"
                align="right"
                :format="formatDate"
                value-format="yyyy-MM-dd"
                :picker-options="{
                    disabledDate(time) {
                        return time.getTime() < Date.now();
                    },
                    shortcuts: [
                        {
                            text: dateRangeText['today'],
                            onClick(picker) {
                                picker.$emit('pick', new Date());
                            }
                        },
                        {
                            text: dateRangeText['yesterday'],
                            onClick(picker) {
                                const date = new Date();
                                date.setTime(date.getTime() - 3600 * 1000 * 24);

                                picker.$emit('pick', date);
                            }
                        },
                        {
                            text: dateRangeText['week_ago'],
                            onClick(picker) {
                                const date = new Date();
                                date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);

                                picker.$emit('pick', date);
                            }
                        }
                    ]
                }">
            </el-date-picker>

            <div class="w-20 px-2 text-sm text-center">
                {{ middleText }}
            </div>

            <el-select class="w-20" v-model="limit" @input="change">
                <el-option
                v-for="(label, value) in limits"
                :key="value"
                :label="label"
                :value="value">
                </el-option>
            </el-select>

            <input type="text" class="w-20 text-sm px-3 py-2.5 mt-1 ml-2 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple" v-model="limitCount" v-if="limit == 'after'" @input="change">

            <div class="pl-2 text-sm" v-if="limit == 'after'">
                {{ endText }}
            </div>

            <el-date-picker
                class="w-36 ml-2 recurring-invoice-data"
                v-model="limitDate"
                type="date"
                align="right"
                :format="formatDate"
                value-format="yyyy-MM-dd"
                v-if="limit == 'on'"
                @input="change"
            >
            </el-date-picker>
        </div>
    </div>
</template>

<script>
import { Select, Option, DatePicker } from 'element-ui';

export default {
    name: 'akaunting-recurring',

    components: {
        [Select.name]: Select,
        [Option.name]: Option,
        [DatePicker.name]: DatePicker,
    },

    props: {
        startText: {
            type: String,
            default: 'Create first invoice on',
            description: "Default reccuring text"
        },
        dateRangeText: {
            type: Object,
            default: {
                today : 'Today',
                yesterday : 'Yesterday',
                week_ago : 'A week ago',
            },
            description: "Default reccuring text"
        },
        middleText: {
            type: String,
            default: 'and end',
            description: "Default reccuring text"
        },
        endText: {
            type: String,
            default: 'invoices',
            description: "Default reccuring text"
        },
        frequencies: null,
        frequencyText: {
            type: String,
            default: 'Repeat this invoice',
            description: "Default reccuring text"
        },
        frequencyEveryText: {
            type: String,
            default: 'every',
            description: "Default reccuring text"
        },
        frequencyValue: {
            type: String,
            default: 'monthly',
            description: "Default reccuring type"
        },

        customFrequencies: null,
        customFrequencyValue: {
            type: String,
            default: 'monthly',
            description: "Default reccuring type"
        },

        startedValue: {
            type: String,
            default: 'never',
            description: "Default reccuring limit"
        },

        limits: null,

        limitValue: {
            type: String,
            default: 'never',
            description: "Default reccuring limit"
        },

        limitCountValue: {
            type: [Number, String],
            default: 0,
            description: "Default reccuring limit"
        },

        limitDateValue: {
            type: String,
            default: '',
            description: "Default reccuring limit"
        },

        dateFormat: {
            type: String,
            default: 'dd MM yyyy',
            description: "Default date format"
        },
    },

    data() {
        return {
            frequency: '',
            interval: '',
            customFrequency: '',
            started_at: '',
            limit: '',
            limitCount: 0,
            limitDate: '',
            formatDate: 'dd MM YYYY',
        }
    },

    created() {
        this.formatDate = this.convertToDarteFormat(this.dateFormat);
    },

    mounted() {
        this.frequency = this.frequencyValue;
        this.customFrequency = this.customFrequencyValue;
        this.started_at = this.startedValue;

        this.limit = this.limitValue;
        this.limitCount = this.limitCountValue;
        this.limitDate = this.limitDateValue;

        if (this.limit == 'count') {
            if (this.limitCount > 0) {
                this.limit = 'after';
            } else {
                this.limit = 'never';
            }
        } else {
            this.limit = 'on';
        }

        setTimeout(function() {
            this.change();
        }.bind(this), 800);
    },

    methods: {
        change() {
            this.$emit('change', this.frequency);

            this.$emit('frequency', this.frequency);
            this.$emit('interval', this.interval);
            this.$emit('custom_frequency', this.customFrequency);
            this.$emit('started', this.started_at);

            switch (this.limit) {
                case 'after':
                    this.$emit('limit', 'count');
                    this.$emit('limit_count', this.limitCount);
                    this.$emit('limit_date', null);
                    break;
                case 'on':
                    this.$emit('limit', 'date');
                    this.$emit('limit_date', this.limitDate);
                    this.$emit('limit_count', 0);
                    break;
                case 'never':
                default:
                    this.$emit('limit', 'count');
                    this.$emit('limit_count', 0);
                    break;
            }
        },

        convertToDarteFormat(format) {
            return format.replace('d', 'dd')
                .replace('M', 'MMM')
                .replace('m', 'MM')
                .replace('F', 'MMMM')
                .replace('y', 'yyyy')
                .replace('Y', 'yyyy');
        },
    }
}
</script>

<style>
    .el-input__inner {
        height: 42px;
    }
</style>
