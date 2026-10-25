<template>
    <fieldset class="min-w-0">
        <legend class="sr-only">{{ filter.label }}</legend>

        <div class="grid grid-cols-2 gap-x-4">
            <div v-for="end in ends" :key="end" class="min-w-0">
                <label :for="idBase + '-' + end" class="block text-sm font-medium text-black">
                    {{ (end === 'min') ? texts.from : texts.to }}
                </label>

                <!-- Text with a decimal keypad: a number input reports '' for what it cannot read, such as 1,000 or
                     12,5, so the filter would be dropped without a word; as text it is checked and refused instead -->
                <input
                    :id="idBase + '-' + end"
                    type="text"
                    inputmode="decimal"
                    autocomplete="off"
                    :value="current[end]"
                    class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple aria-[invalid=true]:border-red"
                    :aria-invalid="error ? 'true' : null"
                    :aria-describedby="error ? idBase + '-error' : null"
                    @input="change(end, $event.target.value)"
                    @keydown.enter="submit"
                >
            </div>
        </div>

        <p v-if="error" :id="idBase + '-error'" role="alert" class="mt-1 text-xs text-red">{{ error }}</p>
    </fieldset>
</template>

<script>
/**
 * The From/To pair of a number range filter, such as Invoice List's Amount. Enter runs the report.
 */
export default {
    name: 'report-amount',

    props: {
        filter: {
            type: Object,
            required: true,
        },

        // {min, max}, '' when unset
        value: {
            type: Object,
            default: null,
        },

        texts: {
            type: Object,
            default: () => ({}),
        },

        error: {
            type: String,
            default: '',
        },

        idBase: {
            type: String,
            required: true,
        },
    },

    data() {
        return {
            ends: ['min', 'max'],
        };
    },

    computed: {
        current() {
            return {
                min: (this.value && (this.value.min !== undefined)) ? String(this.value.min) : '',
                max: (this.value && (this.value.max !== undefined)) ? String(this.value.max) : '',
            };
        },
    },

    methods: {
        change(end, value) {
            this.$emit('input', Object.assign({}, this.current, {[end]: value}));
        },

        submit(event) {
            if (event.isComposing) {
                return;
            }

            event.preventDefault();

            this.$emit('submit', event.target.id);
        },
    },
}
</script>
