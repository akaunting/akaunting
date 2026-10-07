<template>
    <div class="space-y-3">
        <report-switch
            v-if="filter.exclude"
            :name="idBase + '-mode'"
            :aria-label="modeLabel"
            :options="modes"
            :value="mode"
            @input="setMode"
        ></report-switch>

        <input
            v-if="searchable"
            :id="idBase + '-search'"
            v-model="query"
            type="search"
            autocomplete="off"
            :placeholder="searchLabel"
            :aria-label="searchLabel"
            :aria-describedby="hint ? idBase + '-hint' : null"
            class="h-9 w-full px-3 rounded-lg border border-light-gray bg-white text-sm focus:border-purple focus:ring-2 focus:ring-purple-200"
            @keydown="onSearchKeydown"
        >

        <p v-if="hint" :id="idBase + '-hint'" class="text-xs text-gray-500">{{ hint }}</p>

        <fieldset class="min-w-0">
            <legend class="sr-only">{{ filter.label }}</legend>

            <div ref="list" class="max-h-72 overflow-y-auto">
                <label v-if="isRadio" class="flex items-start gap-2 px-2 py-1.5 rounded-md text-sm text-black cursor-pointer hover:bg-lilac-100">
                    <input
                        type="radio"
                        :name="idBase + '-value'"
                        value=""
                        :checked="! value"
                        class="mt-0.5 border-light-gray text-purple focus:ring-purple"
                        @change="pick(['', filter.all], true)"
                    >

                    <span class="min-w-0 break-words">{{ filter.all }}</span>
                </label>

                <template v-if="pinned.length">
                    <p class="px-2 pt-2 pb-1 text-xs font-semibold uppercase tracking-wider text-gray-500">{{ texts.applied }}</p>

                    <label v-for="option in pinned" :key="'applied-' + option[0]" class="flex items-start gap-2 px-2 py-1.5 rounded-md text-sm text-black cursor-pointer hover:bg-lilac-100">
                        <input
                            :type="isRadio ? 'radio' : 'checkbox'"
                            :name="isRadio ? idBase + '-value' : null"
                            :value="option[0]"
                            :checked="isChecked(option[0])"
                            class="mt-0.5 border-light-gray text-purple focus:ring-purple"
                            :class="{'rounded': ! isRadio}"
                            @change="pick(option, $event.target.checked)"
                        >

                        <span class="min-w-0 break-words">{{ option[1] }}</span>
                    </label>

                    <p class="px-2 pt-3 pb-1 text-xs font-semibold uppercase tracking-wider text-gray-500">{{ texts.others }}</p>
                </template>

                <label v-for="option in others" :key="'other-' + option[0]" class="flex items-start gap-2 px-2 py-1.5 rounded-md text-sm text-black cursor-pointer hover:bg-lilac-100">
                    <input
                        :type="isRadio ? 'radio' : 'checkbox'"
                        :name="isRadio ? idBase + '-value' : null"
                        :value="option[0]"
                        :checked="isChecked(option[0])"
                        class="mt-0.5 border-light-gray text-purple focus:ring-purple"
                        :class="{'rounded': ! isRadio}"
                        @change="pick(option, $event.target.checked)"
                    >

                    <span class="min-w-0 break-words">{{ option[1] }}</span>
                </label>

                <p v-if="emptyText" class="px-2 py-1.5 text-sm text-gray-500">{{ emptyText }}</p>
            </div>
        </fieldset>
    </div>
</template>

<script>
import ReportSwitch from './ReportSwitch';

import { labelOf, matchesSearch, normalize, pluralize, remoteOptions, remoteSearchUrl, translate } from './state';

/**
 * A data filter's values: a checklist (several values, with Include/Exclude where the report honours "not") or a
 * list of one choice with "All" first. The applied values stay pinned on top; a search box filters the loaded
 * values, or searches the filter's listing route when the user may read it.
 */
export default {
    name: 'report-checklist',

    components: {
        ReportSwitch,
    },

    props: {
        // A checklist or radio filter
        filter: {
            type: Object,
            required: true,
        },

        // The staged value: {ids, not} for a checklist, a string for a radio list
        value: {
            type: [Object, String],
            default: null,
        },

        // The applied value, whose values are pinned
        applied: {
            type: [Object, String],
            default: null,
        },

        texts: {
            type: Object,
            default: () => ({}),
        },

        // Names learnt from remote results, by value
        learnt: {
            type: Object,
            default: () => ({}),
        },

        idBase: {
            type: String,
            required: true,
        },

        // A remote search failed (no permission, or any error), so the loaded values are searched instead. Kept by
        // the bar, as this list is rebuilt whenever its row opens
        remoteFailed: {
            type: Boolean,
            default: false,
        },
    },

    data() {
        return {
            query: '',
            // The remote results of the last finished search, as [value, label] options, and its query
            results: null,
            resultsFor: '',
            searching: false,
        };
    },

    created() {
        this.timer = null;
        this.controller = null;
    },

    beforeDestroy() {
        clearTimeout(this.timer);

        this.abort();
    },

    computed: {
        isRadio() {
            return this.filter.kind === 'radio';
        },

        options() {
            return (Array.isArray(this.filter.options) ? this.filter.options : []).map((option) => [String(option[0]), String(option[1])]);
        },

        remoteUsable() {
            return !! (this.filter.remote && this.filter.remote.allowed) && ! this.remoteFailed;
        },

        searchable() {
            return !! (this.filter.remote && this.filter.remote.allowed) || (this.options.length > 8);
        },

        searchLabel() {
            return translate(this.texts.search, {name: this.filter.label});
        },

        term() {
            return this.query.trim();
        },

        hint() {
            return (this.filter.more && this.remoteUsable) ? translate(this.texts.first_shown, {count: this.options.length}) : '';
        },

        modes() {
            return [['include', this.texts.include], ['exclude', this.texts.exclude]];
        },

        mode() {
            return (this.value && (this.value.not === true)) ? 'exclude' : 'include';
        },

        modeLabel() {
            return translate(this.texts.include_or_exclude, {name: this.filter.label});
        },

        // The staged values as given, so Exclude can be chosen before any value
        stagedIds() {
            if (this.isRadio) {
                return this.value ? [String(this.value)] : [];
            }

            return (this.value && Array.isArray(this.value.ids)) ? this.value.ids.map(String) : [];
        },

        // Names from the last results as well, until the root has learnt them
        names() {
            const names = {};

            (this.results || []).forEach((option) => {
                names[option[0]] = option[1];
            });

            return Object.assign(names, this.learnt);
        },

        pinned() {
            const values = this.isRadio
                ? (this.applied ? [String(this.applied)] : [])
                : normalize(this.filter, this.applied).ids;

            return values.map((value) => [value, labelOf(this.filter, value, this.names)]);
        },

        others() {
            const pinned = this.pinned.map((option) => option[0]);
            let pool;

            if (this.term && this.remoteUsable && this.results && (this.resultsFor === this.term)) {
                pool = this.results;
            } else {
                pool = this.options.filter((option) => matchesSearch(option[1], this.term));

                // Values picked from an earlier search stay listed, so they can be unticked
                if (! this.term) {
                    const picked = this.stagedIds
                        .filter((id) => ! this.options.some((option) => option[0] === id))
                        .map((id) => [id, labelOf(this.filter, id, this.names)]);

                    pool = picked.concat(pool);
                }
            }

            return pool.filter((option) => ! pinned.includes(option[0]));
        },

        emptyText() {
            if (this.others.length || this.searching) {
                return '';
            }

            if (this.term) {
                return translate(this.texts.no_results, {search: this.term});
            }

            return (this.pinned.length || this.isRadio) ? '' : (this.texts.no_matching_data || '');
        },
    },

    watch: {
        query() {
            this.schedule();
        },
    },

    methods: {
        isChecked(value) {
            return this.isRadio ? (String(this.value || '') === value) : this.stagedIds.includes(value);
        },

        pick(option, checked) {
            const value = option[0];

            // A value found by the remote search keeps its name for the pills and summaries until the reload
            if ((value !== '') && ! this.options.some((known) => known[0] === value) && ! (this.filter.labels && (this.filter.labels[value] !== undefined))) {
                this.$emit('learn', {[value]: option[1]});
            }

            if (this.isRadio) {
                this.$emit('input', value);

                return;
            }

            const ids = this.stagedIds.filter((id) => id !== value);

            this.$emit('input', {
                ids: checked ? ids.concat([value]) : ids,
                not: this.mode === 'exclude',
            });
        },

        setMode(mode) {
            this.$emit('input', {ids: this.stagedIds.slice(), not: mode === 'exclude'});
        },

        onSearchKeydown(event) {
            if (event.key === 'ArrowDown') {
                event.preventDefault();

                const input = this.$refs.list ? this.$refs.list.querySelector('input') : null;

                if (input) {
                    input.focus();
                }
            } else if (event.key === 'Escape') {
                if (this.query !== '') {
                    event.preventDefault();
                    event.stopPropagation();

                    this.query = '';
                }
            } else if ((event.key === 'Enter') && ! event.ctrlKey && ! event.metaKey) {
                // Enter never runs the report from here: the list is still being chosen
                event.preventDefault();
            }
        },

        schedule() {
            clearTimeout(this.timer);

            this.abort();

            const term = this.term;

            if (! this.remoteUsable || (term.replace(/"/g, '').trim() === '')) {
                this.searching = false;

                return;
            }

            this.searching = true;

            this.timer = setTimeout(() => {
                this.search(term);
            }, 250);
        },

        abort() {
            if (this.controller) {
                this.controller.abort();
            }

            this.controller = null;
        },

        search(term) {
            const controller = (typeof AbortController === 'function') ? new AbortController() : null;

            this.controller = controller;

            this.$emit('announce', this.texts.searching);

            window.axios.get(remoteSearchUrl(this.filter.remote.url, term, window.location.href), controller ? {signal: controller.signal} : {})
                .then((response) => {
                    if (this.controller !== controller) {
                        return;
                    }

                    this.controller = null;
                    this.searching = false;
                    this.results = remoteOptions(response.data);
                    this.resultsFor = term;

                    this.$emit('announce', this.results.length
                        ? pluralize(this.texts.results, this.results.length)
                        : translate(this.texts.no_results, {search: term}));
                })
                .catch((error) => {
                    if (window.axios.isCancel(error) || (this.controller !== controller)) {
                        return;
                    }

                    // 401 or 403 when the listing is not the user's to read, or any other failure
                    this.controller = null;
                    this.searching = false;
                    this.results = null;

                    this.$emit('remote-failed');
                    this.$emit('announce', this.texts.search_failed);
                });
        },
    },
}
</script>
