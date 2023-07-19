<template>
    <div class="sm:col-span-6 space-y-6 sm:space-y-2">
        <div class="flex flex-wrap lg:flex-nowrap items-center space-y-1 lg:space-y-0" :class="{ 'justify-start': frequency == 'custom' }">
            <div class="w-24 sm:w-60 px-0 sm:px-2 text-sm">
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

            <div class="w-20 sm:w-auto px-2 text-sm text-center" v-if="frequency == 'custom'">
                {{ frequencyEveryText }}
            </div>

            <input type="text" class="w-20 text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple" v-model="interval" @input="change" v-if="frequency == 'custom'">

            <div class="text-red text-sm mt-1 block" v-if="invertalError" v-html="invertalError"></div>

            <el-select class="w-36 ml-2" v-model="customFrequency" @input="change" v-if="frequency == 'custom'">
                <el-option
                v-for="(label, value) in customFrequencies"
                :key="value"
                :label="label"
                :value="value">
                </el-option>
            </el-select>

            <div class="text-red text-sm mt-1 block" v-if="customFrequencyError" v-html="customFrequencyError"></div>
        </div>

        <div class="flex flex-wrap lg:flex-nowrap items-center space-y-3 lg:space-y-0" :class="{ 'justify-start': limit !== 'never' }">
            <div class="w-24 sm:w-60 px-0 sm:px-2 text-sm">
                {{ startText }}
            </div>

            <div
            :class="startedError ? 'pt-0' : '' && startedError || limitDateError ? 'pt-0 pb-5' : 'pb-10' && startedError && limitDateError ? 'pt-6 pb-5' : 'pb-10'">
                    <el-date-picker
                    class="w-36 cursor-pointer recurring-invoice-data"
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

                <div class="text-red text-sm mt-1 block" v-if="startedError" v-html="startedError"></div>

            </div>

            <div class="w-24 px-2 text-sm text-center"
            :class="(startedError || limitDateError ? 'pt-10 pb-14' : 'pb-10') && (startedError && limitDateError ? 'pt-6 pb-14' : 'pb-10')">
                {{ middleText }}
            </div>

            <el-select class="w-20" v-model="limit" @input="change"
            :class="startedError || limitDateError ? 'pt-0 pb-6' : '' && startedError && limitDateError ? 'pt-6 pb-16' : 'pb-10'">
                <el-option
                v-for="(label, value) in limits"
                :key="value"
                :label="label"
                :value="value">
                </el-option>
            </el-select>

            <input type="text" class="w-20 cursor-pointer text-sm px-3 py-2.5 mt-1 ml-2 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple" v-model="limitCount" v-if="limit == 'after'" @input="change">

            <div class="text-red text-sm mt-1 block" v-if="limitCountError" v-html="limitCountError"></div>

            <div class="pl-2 text-sm" v-if="limit == 'after'">
                {{ endText }}
            </div>

            <div 
            :class="startedError || limitDateError ? 'pt-10 pb-10' : 'pb-10'  &&  startedError && limitDateError ? ' pt-20 pb-14' : 'pb-10'">
                    <el-date-picker
                    class="w-36 ml-2 cursor-pointer recurring-invoice-data"
                    v-model="limitDate"
                    type="date"
                    align="right"
                    :format="formatDate"
                    value-format="yyyy-MM-dd"
                    v-if="limit == 'on'"
                    @input="change"
                >
                </el-date-picker>

                <div class="text-red text-sm mt-1 ml-2 block" v-if="limitDateError" v-html="limitDateError"></div>
            </div>
        </div>

        <div v-if="sendEmailShow" class="flex flex-wrap lg:flex-nowrap items-center space-y-1 lg:space-y-0">
            <div class="w-24 sm:w-60 px-0 sm:px-2 text-sm">
                {{ sendEmailText }}
            </div>

            <div class="flex items-center mt-1">
                <label @click="sendEmail=1;change();" v-bind:class="[sendEmail == 1 ? ['bg-green-500','text-white'] : 'bg-black-100']" class="relative w-10 ltr:rounded-tl-lg ltr:rounded-bl-lg rtl:rounded-tr-lg rtl:rounded-br-lg py-2 px-1 text-sm text-center transition-all cursor-pointer">
                    {{ sendEmailYesText }}
                </label>

                <label @click="sendEmail=0;change();"v-bind:class="[sendEmail == 0 ? ['bg-red-500','text-white'] : 'bg-black-100']" class="relative w-10 ltr:rounded-tr-lg ltr:rounded-br-lg rtl:rounded-tl-lg rtl:rounded-bl-lg py-2 px-1 text-sm text-center transition-all cursor-pointer">
                    {{ sendEmailNoText }}
                </label>
            </div>
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
        invertalError: {
            type: String,
            default: null,
            description: "Selectbox input error message"
        },

        customFrequencies: null,
        customFrequencyValue: {
            type: String,
            default: 'monthly',
            description: "Default reccuring type"
        },
        customFrequencyError: {
            type: String,
            default: null,
            description: "Selectbox input error message"
        },

        startedValue: {
            type: String,
            default: 'never',
            description: "Default reccuring limit"
        },
        startedError: {
            type: String,
            default: null,
            description: "Selectbox input error message"
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
        limitCountError: {
            type: String,
            default: null,
            description: "Selectbox input error message"
        },

        limitDateValue: {
            type: String,
            default: '',
            description: "Default reccuring limit"
        },
        limitDateError: {
            type: String,
            default: null,
            description: "Selectbox input error message"
        },

        dateFormat: {
            type: String,
            default: 'dd MM yyyy',
            description: "Default date format"
        },

        sendEmailShow: {
            type: [String, Number, Array, Object, Boolean],
            default: '1',
            description: "Created recurring model send automatically option"
        },
        sendEmailText: {
            type: String,
            default: 'Send email automatically',
            description: "Created recurring model send automatically option"
        },
        sendEmailYesText: {
            type: String,
            default: 'Yes',
            description: "Send email option yes text"
        },
        sendEmailNoText: {
            type: String,
            default: 'No',
            description: "Send email option no text"
        },
        sendEmailValue: {
            type: [Number, String],
            default: 0,
            description: "Send Email value"
        }
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
            sendEmail: 0,
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

        this.sendEmail = this.sendEmailValue;

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

            this.$emit('send_email', this.sendEmail);
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
