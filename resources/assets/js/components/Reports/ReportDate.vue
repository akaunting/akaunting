<template>
    <!-- Its cells sit in the strip's grid: a date range takes three (preset, From, To), a single date two -->
    <div class="contents">
        <div class="min-w-0" :class="{'col-span-2': phone}">
            <label :for="idBase + '-preset'" class="block text-sm font-medium text-black truncate">
                {{ control.label }}<template v-if="changed"><span class="inline-block w-1.5 h-1.5 ms-1.5 align-middle rounded-full bg-purple" aria-hidden="true"></span><span class="sr-only">{{ ' ' + changed }}</span></template>
            </label>

            <report-select :id="idBase + '-preset'" :value="presetValue" :options="presetOptions" @input="choose"></report-select>
        </div>

        <div
            v-for="end in ends"
            :key="end"
            class="min-w-0"
            :class="{'col-span-2': phone && ! isRange}"
            @keydown.enter="submit($event, end)"
        >
            <label :for="idBase + '-' + end" class="block text-sm font-medium text-black truncate">{{ labels[end] }}</label>

            <!-- Akaunting's date input (AkauntingDate): its classes and its calendar icon -->
            <div class="relative">
                <flat-picker
                    :ref="end"
                    :value="dateOf(end) || null"
                    :config="configs[end]"
                    class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple aria-[invalid=true]:border-red"
                    @input="change(end, $event)"
                ></flat-picker>

                <span class="absolute ltr:right-2 rtl:left-2 bottom-2 text-light-gray pointer-events-none" aria-hidden="true">
                    <span class="material-icons w-4 h-5 text-sm">calendar_today</span>
                </span>
            </div>
        </div>

        <p v-if="error" :id="idBase + '-error'" role="alert" class="col-span-full -mt-1 text-xs text-red">{{ error }}</p>
    </div>
</template>

<script>
import flatPicker from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

import ReportSelect from './ReportSelect';

import { normalize, presetIndex } from './state';

/**
 * A date range (preset, From, To) or a single date (preset, Date). Picking a preset stages its dates; editing a
 * date stages it and shows Custom unless the dates are a preset's. Enter in a date runs the report.
 */
export default {
    name: 'report-date',

    components: {
        flatPicker,
        ReportSelect,
    },

    props: {
        // A range or an as_of control
        control: {
            type: Object,
            required: true,
        },

        // {start, end} for a range, 'Y-m-d' for a single date
        value: {
            type: [Object, String],
            default: null,
        },

        texts: {
            type: Object,
            default: () => ({}),
        },

        changed: {
            type: String,
            default: '',
        },

        error: {
            type: String,
            default: '',
        },

        idBase: {
            type: String,
            required: true,
        },

        phone: {
            type: Boolean,
            default: false,
        },

        // A flatpickr locale, loaded by the bar as AkauntingDate loads it
        locale: {
            type: Object,
            default: null,
        },

        // The company date format, in the PHP tokens flatpickr also reads
        dateFormat: {
            type: String,
            default: 'Y-m-d',
        },
    },

    data() {
        return {
            // The preset or Custom last picked, kept while the dates stay the same: presets may share dates
            choice: null,
        };
    },

    computed: {
        isRange() {
            return this.control.kind === 'range';
        },

        ends() {
            return this.isRange ? ['start', 'end'] : ['date'];
        },

        labels() {
            return {
                start: this.texts.from,
                end: this.texts.to,
                date: this.texts.date,
            };
        },

        presets() {
            return Array.isArray(this.control.presets) ? this.control.presets : [];
        },

        // By index, as presets may share dates, then Custom
        presetOptions() {
            return this.presets.map((preset, index) => [String(index), preset.label]).concat([['custom', this.texts.custom]]);
        },

        current() {
            return normalize(this.control, this.value);
        },

        presetValue() {
            if (this.choice && (this.choice.value === JSON.stringify(this.current))) {
                return this.choice.index;
            }

            const index = presetIndex(this.control, this.current);

            return (index < 0) ? 'custom' : String(index);
        },

        // One config per input, made once: the picker redraws whenever its config object changes
        configs() {
            const configs = {};

            ['start', 'end', 'date'].forEach((end) => {
                configs[end] = {
                    dateFormat: 'Y-m-d',
                    altInput: true,
                    altFormat: this.dateFormat || 'Y-m-d',
                    allowInput: true,
                    onReady: (dates, text, picker) => this.ready(picker, end),
                };

                if (this.locale) {
                    configs[end].locale = this.locale;
                }
            });

            return configs;
        },
    },

    watch: {
        error() {
            this.$nextTick(() => {
                this.ends.forEach((end) => {
                    const picker = this.picker(end);

                    if (picker) {
                        this.describe(this.visibleInput(picker));
                    }
                });
            });
        },
    },

    methods: {
        dateOf(end) {
            return this.isRange ? this.current[end] : this.current;
        },

        picker(end) {
            const component = Array.isArray(this.$refs[end]) ? this.$refs[end][0] : this.$refs[end];

            return (component && component.fp) ? component.fp : null;
        },

        // The input people see: flatpickr's alternative input, or its native one on phones
        visibleInput(picker) {
            return picker.mobileInput || picker.altInput || picker.input;
        },

        // The label points at the input people see, not at the hidden one that holds Y-m-d
        ready(picker, end) {
            const input = this.visibleInput(picker);

            input.id = this.idBase + '-' + end;

            if (input !== picker.input) {
                picker.input.removeAttribute('id');
            }

            // flatpickr gives its native phone input tabindex 1, which would make it the page's first stop
            if (picker.mobileInput) {
                picker.mobileInput.removeAttribute('tabindex');
            }

            this.describe(input);
        },

        describe(input) {
            if (this.error) {
                input.setAttribute('aria-invalid', 'true');
                input.setAttribute('aria-describedby', this.idBase + '-error');
            } else {
                input.removeAttribute('aria-invalid');
                input.removeAttribute('aria-describedby');
            }
        },

        choose(selected) {
            if (selected === 'custom') {
                this.choice = {index: selected, value: JSON.stringify(this.current)};

                return;
            }

            const preset = this.presets[Number(selected)];

            if (! preset) {
                return;
            }

            const value = this.isRange ? {start: preset.start, end: preset.end} : preset.date;

            this.choice = {index: selected, value: JSON.stringify(normalize(this.control, value))};

            this.$emit('input', value);
        },

        change(end, date) {
            date = date || '';

            if (this.dateOf(end) === date) {
                return;
            }

            this.$emit('input', this.isRange ? Object.assign({}, this.current, {[end]: date}) : date);
        },

        submit(event, end) {
            if (event.isComposing) {
                return;
            }

            event.preventDefault();

            // flatpickr has already read what was typed (its own keydown handler sits on the input), while the
            // picker component reports it a tick later, so take the date from flatpickr
            const picker = this.picker(end);

            if (picker) {
                this.change(end, picker.selectedDates.length ? picker.formatDate(picker.selectedDates[0], 'Y-m-d') : '');
            }

            this.$emit('submit', event.target.id);
        },
    },
}
</script>
