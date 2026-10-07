<template>
    <div class="min-w-0">
        <div class="flex flex-wrap items-baseline gap-x-2 mb-1">
            <span :id="idBase + '-label'" class="text-sm font-medium text-black">
                {{ control.label }}<template v-if="changed"><span class="inline-block w-1.5 h-1.5 ms-1.5 align-middle rounded-full bg-purple" aria-hidden="true"></span><span class="sr-only">{{ ' ' + changed }}</span></template>
            </span>

            <span v-if="! note && columns" class="text-xs text-gray-500">{{ countText }}</span>
        </div>

        <p v-if="note" class="px-3 py-2 rounded-lg bg-orange-50 text-sm text-orange-800">{{ note }}</p>

        <p v-else-if="! columns" class="text-sm text-gray-500">
            {{ texts.default_columns }}

            <button v-if="appliedColumns" :id="idBase + '-edit'" type="button" class="ms-1 font-medium text-purple hover:underline" @click="edit">
                {{ texts.choose_columns }}
            </button>
        </p>

        <template v-else>
            <ol :aria-labelledby="idBase + '-label'" class="space-y-1">
                <li v-for="(column, index) in columns" :key="column" class="flex items-center gap-1 py-1 ps-2 pe-1 rounded-lg border border-gray-200 bg-white">
                    <span class="flex-none w-5 text-xs text-gray-500 tabular-nums" aria-hidden="true">{{ index + 1 }}</span>

                    <span class="flex-1 min-w-0 text-sm text-black truncate">{{ name(column) }}</span>

                    <button
                        :id="buttonId(column, 'up')"
                        type="button"
                        :disabled="index === 0"
                        :aria-label="say('move_up', column)"
                        :title="say('move_up', column)"
                        class="flex flex-none items-center justify-center w-7 h-7 rounded-md border border-gray-200 text-purple hover:bg-lilac-100 disabled:opacity-40 disabled:cursor-default disabled:hover:bg-transparent"
                        @click="move(index, -1)"
                    >
                        <span class="material-icons text-base" aria-hidden="true">arrow_upward</span>
                    </button>

                    <button
                        :id="buttonId(column, 'down')"
                        type="button"
                        :disabled="index === columns.length - 1"
                        :aria-label="say('move_down', column)"
                        :title="say('move_down', column)"
                        class="flex flex-none items-center justify-center w-7 h-7 rounded-md border border-gray-200 text-purple hover:bg-lilac-100 disabled:opacity-40 disabled:cursor-default disabled:hover:bg-transparent"
                        @click="move(index, 1)"
                    >
                        <span class="material-icons text-base" aria-hidden="true">arrow_downward</span>
                    </button>

                    <button
                        :id="buttonId(column, 'remove')"
                        type="button"
                        :disabled="columns.length < 2"
                        :aria-label="say('remove_column', column)"
                        :title="say('remove_column', column)"
                        class="flex flex-none items-center justify-center w-7 h-7 rounded-md border border-gray-200 text-purple hover:bg-lilac-100 disabled:opacity-40 disabled:cursor-default disabled:hover:bg-transparent"
                        @click="remove(index)"
                    >
                        <span class="material-icons text-base" aria-hidden="true">close</span>
                    </button>
                </li>
            </ol>

            <button
                v-if="unused.length"
                :id="idBase + '-add'"
                type="button"
                class="mt-2 text-sm font-medium text-purple hover:underline"
                :aria-expanded="adding ? 'true' : 'false'"
                :aria-controls="adding ? idBase + '-unused' : null"
                @click="adding = ! adding"
            >
                {{ addText }}
            </button>

            <div v-if="adding && unused.length" :id="idBase + '-unused'" class="grid grid-cols-1 sm:grid-cols-2 gap-1 mt-1">
                <label v-for="(column, index) in unused" :key="column" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-sm text-black cursor-pointer hover:bg-lilac-100">
                    <input :id="idBase + '-add-' + index" type="checkbox" class="rounded border-light-gray text-purple focus:ring-purple" @change="add(column, index)">

                    <span class="min-w-0 truncate">{{ name(column) }}</span>
                </label>
            </div>

            <div v-if="restorable">
                <button :id="idBase + '-restore'" type="button" class="mt-2 text-sm font-medium text-purple hover:underline" @click="restore">
                    {{ texts.restore_columns }}
                </button>
            </div>
        </template>
    </div>
</template>

<script>
import { isSame, normalize, optionLabel, translate } from './state';

/**
 * The ordered column picker: move buttons rather than dragging, the unused columns to add, and the report's
 * default columns, which need no token. While a setting it depends on has a staged change, a note replaces it.
 */
export default {
    name: 'report-columns',

    props: {
        control: {
            type: Object,
            required: true,
        },

        // The staged columns in order, or null for the report's default columns
        value: {
            type: Array,
            default: null,
        },

        applied: {
            type: Array,
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

        // Shown instead of the picker while a setting it depends on has a staged change
        note: {
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
            adding: false,
        };
    },

    computed: {
        columns() {
            return normalize(this.control, this.value);
        },

        appliedColumns() {
            return normalize(this.control, this.applied);
        },

        options() {
            return Array.isArray(this.control.options) ? this.control.options : [];
        },

        // Restoring the defaults changes something only when the URL holds columns or they were edited
        restorable() {
            return this.control.in_url || ! isSame(this.control, this.appliedColumns, this.columns);
        },

        unused() {
            const used = this.columns || [];

            return this.options.map((option) => String(option[0])).filter((column) => ! used.includes(column));
        },

        countText() {
            return translate(this.texts.columns_count, {count: this.columns.length, total: this.options.length});
        },

        addText() {
            return translate(this.texts.add_columns, {count: this.unused.length});
        },
    },

    methods: {
        name(column) {
            return optionLabel(this.options, column);
        },

        say(key, column) {
            return translate(this.texts[key], {name: this.name(column)});
        },

        buttonId(column, action) {
            return this.idBase + '-' + String(column).replace(/[^A-Za-z0-9_-]/g, '-') + '-' + action;
        },

        // Moving a list item takes the focus with it in some browsers, so it is put back on the same button, or on
        // the other one when the item reached an end
        focus(ids) {
            this.$nextTick(() => {
                for (const id of ids) {
                    const element = document.getElementById(id);

                    if (element && ! element.disabled) {
                        element.focus();

                        return;
                    }
                }
            });
        },

        move(index, step) {
            const columns = this.columns.slice();
            const target = index + step;

            if ((target < 0) || (target >= columns.length)) {
                return;
            }

            const column = columns.splice(index, 1)[0];

            columns.splice(target, 0, column);

            this.$emit('input', columns);
            this.$emit('announce', translate(this.texts.position, {name: this.name(column), position: target + 1, total: columns.length}));

            const same = (step < 0) ? 'up' : 'down';
            const other = (step < 0) ? 'down' : 'up';

            this.focus([this.buttonId(column, same), this.buttonId(column, other)]);
        },

        remove(index) {
            if (this.columns.length < 2) {
                return;
            }

            const columns = this.columns.slice();
            const column = columns.splice(index, 1)[0];
            const next = columns[Math.min(index, columns.length - 1)];

            this.$emit('input', columns);
            this.$emit('announce', translate(this.texts.removed, {name: this.name(column)}));

            this.focus([this.buttonId(next, 'remove'), this.buttonId(next, 'up'), this.idBase + '-add']);
        },

        add(column, index) {
            this.$emit('input', this.columns.concat([column]));

            // Adding the last unused column removes the list and its toggle, so the new column's buttons follow
            this.focus([this.idBase + '-add-' + index, this.idBase + '-add-' + (index - 1), this.idBase + '-add', this.buttonId(column, 'remove'), this.buttonId(column, 'up'), this.idBase + '-restore']);
        },

        restore() {
            this.$emit('input', null);

            this.focus([this.idBase + '-edit']);
        },

        edit() {
            const columns = this.appliedColumns.slice();

            this.$emit('input', columns);

            // Restore shows only when it would change something, so the first column's buttons may follow
            this.focus([this.idBase + '-restore', this.buttonId(columns[0], 'remove'), this.buttonId(columns[0], 'down'), this.idBase + '-add']);
        },
    },
}
</script>
