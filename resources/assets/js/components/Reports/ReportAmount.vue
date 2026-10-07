<template>
    <fieldset class="min-w-0">
        <legend class="sr-only">{{ filter.label }}</legend>

        <div class="grid grid-cols-2 gap-x-4">
            <div v-for="end in ends" :key="end" class="min-w-0">
                <label :for="idBase + '-' + end" class="block mb-1 text-sm font-medium text-black">
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
                    class="h-9 w-full px-3 rounded-lg border border-light-gray bg-white text-sm focus:border-purple focus:ring-2 focus:ring-purple-200 aria-[invalid=true]:border-red"
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
