<template>
    <div v-if="ready" @keydown="onKeydown">
        <p v-if="showSummary && summaryText" class="text-sm text-gray-500" :class="phone ? 'mb-3' : 'mb-4'">{{ summaryText }}</p>

        <!-- Phones: the applied filters scroll sideways above one button that opens every option -->
        <template v-if="phone">
            <div v-if="pills.length" class="flex gap-2 overflow-x-auto pb-1">
                <span v-for="pill in pills" :key="pill.id" class="inline-flex flex-none items-stretch h-11 max-w-xs rounded-lg bg-purple-lighter text-sm text-purple-700">
                    <button v-if="pill.key" :id="pill.id" type="button" :title="pill.title" class="min-w-0 ps-3 pe-1 rounded-s-lg font-medium truncate hover:bg-purple-100" @click="openFilter(pill.key)">{{ pill.text }}</button>

                    <span v-else :title="pill.title" class="self-center min-w-0 ps-3 pe-1 font-medium truncate">{{ pill.text }}</span>

                    <button :id="pill.id + '-remove'" type="button" :aria-label="pill.remove" :title="pill.remove" class="flex flex-none items-center justify-center w-11 rounded-e-lg hover:bg-purple-100" @click="removePill(pill)">
                        <span class="material-icons text-base" aria-hidden="true">close</span>
                    </button>
                </span>
            </div>

            <button v-if="pills.length" :id="ids.clear" type="button" class="inline-flex items-center min-h-11 -ms-2 px-2 text-sm font-medium text-purple hover:underline" @click="clearFilters(ids.clear)">
                {{ texts.clear_filters }}
            </button>

            <button
                v-if="hasOptions"
                :id="ids.options"
                type="button"
                class="flex items-center justify-between gap-2 w-full h-11 mt-3 px-4 rounded-lg border border-light-gray bg-white text-sm font-medium text-purple aria-expanded:border-purple aria-expanded:bg-purple-lighter"
                :aria-expanded="regionOpen ? 'true' : 'false'"
                :aria-controls="ids.region"
                @click="regionOpen = ! regionOpen"
            >
                <span class="truncate">{{ optionsText }}</span>

                <span class="material-icons flex-none text-lg" :class="{'rotate-180': regionOpen}" aria-hidden="true">expand_more</span>
            </button>

            <span class="sr-only" role="status">{{ statusText }}</span>
        </template>

        <!-- On phones this is the region the button opens; on wider screens it is always shown -->
        <div :id="phone ? ids.region : null" v-show="! phone || regionOpen" :class="{'mt-4': phone}">
            <section v-if="def.strip.length" :aria-labelledby="phone ? ids.base + '-report' : null">
                <h3 v-if="phone" :id="ids.base + '-report'" class="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-500">{{ texts.report }}</h3>

                <div :class="phone ? 'grid grid-cols-2 gap-x-4 gap-y-3' : 'grid grid-cols-2 sm:grid-cols-3 2xl:grid-cols-6 gap-x-4 gap-y-3 items-end'">
                    <template v-for="control in def.strip">
                        <report-date
                            v-if="(control.kind === 'range') || (control.kind === 'as_of')"
                            :key="control.key"
                            :control="control"
                            :value="staged[control.key]"
                            :texts="texts"
                            :changed="changedText(control)"
                            :error="errorText(control.key)"
                            :id-base="controlId(control)"
                            :phone="phone"
                            :locale="pickerLocale"
                            :date-format="def.date_format"
                            @input="setValue(control.key, $event)"
                            @submit="update"
                        ></report-date>

                        <div v-else :key="control.key" class="min-w-0" :class="{'col-span-2': phone}">
                            <report-switch
                                v-if="control.kind === 'switch'"
                                :name="controlId(control)"
                                :label="control.label"
                                :options="control.options"
                                :value="staged[control.key]"
                                :changed="changedText(control)"
                                @input="setValue(control.key, $event)"
                            ></report-switch>

                            <template v-else>
                                <label :for="controlId(control)" class="block mb-1 text-sm font-medium text-black truncate">
                                    {{ control.label }}<template v-if="changedText(control)"><span class="inline-block w-1.5 h-1.5 ms-1.5 align-middle rounded-full bg-purple" aria-hidden="true"></span><span class="sr-only">{{ ' ' + changedText(control) }}</span></template>
                                </label>

                                <select
                                    :id="controlId(control)"
                                    :value="staged[control.key]"
                                    class="h-9 w-full ps-3 pe-10 rounded-lg border border-light-gray bg-white text-sm focus:border-purple focus:ring-2 focus:ring-purple-200 rtl:bg-[position:left_0.5rem_center]"
                                    @change="setValue(control.key, $event.target.value)"
                                >
                                    <option v-for="option in control.options" :key="option[0]" :value="option[0]">{{ option[1] }}</option>
                                </select>
                            </template>
                        </div>
                    </template>
                </div>
            </section>

            <!-- Row 2: the toggle, the applied filters and the actions -->
            <div v-if="! phone && showRow" class="flex flex-wrap items-center gap-2 mt-4 pb-4 border-b border-gray-200">
                <button
                    v-if="hasToggle"
                    :id="ids.toggle"
                    type="button"
                    class="inline-flex items-center gap-1.5 h-9 max-w-xs px-3 rounded-lg border border-light-gray bg-white text-sm font-medium text-purple hover:bg-purple-lighter aria-expanded:border-purple aria-expanded:bg-purple-lighter"
                    :aria-expanded="panelOpen ? 'true' : 'false'"
                    :aria-controls="ids.panel"
                    @click="setPanel(! panelOpen)"
                >
                    <span class="material-icons flex-none text-lg" aria-hidden="true">tune</span>

                    <span class="truncate">{{ toggleText }}</span>
                </button>

                <span v-for="pill in pills" :key="pill.id" class="inline-flex flex-none items-stretch h-8 max-w-xs rounded-lg bg-purple-lighter text-sm text-purple-700">
                    <button v-if="pill.key" :id="pill.id" type="button" :title="pill.title" class="min-w-0 ps-3 pe-1 rounded-s-lg font-medium truncate hover:bg-purple-100" @click="openFilter(pill.key)">{{ pill.text }}</button>

                    <span v-else :title="pill.title" class="self-center min-w-0 ps-3 pe-1 font-medium truncate">{{ pill.text }}</span>

                    <button :id="pill.id + '-remove'" type="button" :aria-label="pill.remove" :title="pill.remove" class="flex flex-none items-center justify-center w-8 rounded-e-lg hover:bg-purple-100" @click="removePill(pill)">
                        <span class="material-icons text-base" aria-hidden="true">close</span>
                    </button>
                </span>

                <button v-if="pills.length" :id="ids.clear" type="button" class="px-1 text-sm font-medium text-purple hover:underline" @click="clearFilters(ids.clear)">
                    {{ texts.clear_filters }}
                </button>

                <div v-if="editable || def.has_query" class="flex flex-wrap items-center gap-x-4 gap-y-2 ms-auto">
                    <span v-if="editable" role="status" class="text-sm text-orange-800">{{ statusText }}</span>

                    <button v-if="dirty.length" :id="ids.discard" type="button" class="text-sm font-medium text-purple hover:underline" @click="discard(ids.update)">
                        {{ texts.discard }}
                    </button>

                    <button v-if="def.has_query" :id="ids.reset" type="button" :title="texts.reset_hint" class="text-sm font-medium text-purple hover:underline" @click="reset(ids.reset)">
                        {{ texts.reset }}
                    </button>

                    <button v-if="editable" :id="ids.update" type="button" :aria-disabled="canUpdate ? 'false' : 'true'" :title="updateTitle" :class="updateClass" @click="update(ids.update)">
                        <i v-if="navigating" :class="spinnerClass" aria-hidden="true"></i>

                        <span :class="{'opacity-0': navigating}">{{ updateText }}</span>
                    </button>
                </div>
            </div>

            <!-- The Customise panel: inline, it pushes the report down -->
            <div
                v-if="phone || hasToggle"
                :id="phone ? null : ids.panel"
                v-show="phone || panelOpen"
                :class="phone ? '' : 'grid grid-cols-1 lg:grid-cols-5 mt-4 rounded-xl border border-gray-200 bg-white'"
            >
                <section v-if="def.filters.length" :aria-labelledby="ids.base + '-filters'" :class="filtersClass">
                    <h3 :id="ids.base + '-filters'" class="text-xs font-semibold uppercase tracking-wider text-gray-500">{{ texts.filters }}</h3>

                    <div v-for="filter in def.filters" :key="filter.key" class="border-b border-gray-200 last:border-b-0">
                        <button
                            :id="controlId(filter)"
                            type="button"
                            class="flex items-center gap-2 w-full py-3 text-start text-sm"
                            :aria-expanded="expanded[filter.key] ? 'true' : 'false'"
                            :aria-controls="expanded[filter.key] ? controlId(filter) + '-body' : null"
                            @click="expanded[filter.key] = ! expanded[filter.key]"
                        >
                            <span class="flex-none font-medium text-black">
                                {{ filter.label }}<template v-if="changedText(filter)"><span class="inline-block w-1.5 h-1.5 ms-1.5 align-middle rounded-full bg-purple" aria-hidden="true"></span><span class="sr-only">{{ ' ' + changedText(filter) }}</span></template>
                            </span>

                            <span class="ms-auto min-w-0 text-gray-500 truncate">{{ summaryOf(filter) }}</span>

                            <span class="material-icons flex-none text-lg text-gray-500" :class="expanded[filter.key] ? 'rotate-90' : 'rtl:rotate-180'" aria-hidden="true">chevron_right</span>
                        </button>

                        <div v-if="expanded[filter.key]" :id="controlId(filter) + '-body'" class="pb-4">
                            <p v-if="resetNote(filter)" class="px-3 py-2 rounded-lg bg-orange-50 text-sm text-orange-800">{{ resetNote(filter) }}</p>

                            <report-amount
                                v-else-if="filter.kind === 'amount'"
                                :filter="filter"
                                :value="staged[filter.key]"
                                :texts="texts"
                                :error="errorText(filter.key)"
                                :id-base="controlId(filter)"
                                @input="setValue(filter.key, $event)"
                                @submit="update"
                            ></report-amount>

                            <report-checklist
                                v-else
                                :filter="filter"
                                :value="staged[filter.key]"
                                :applied="applied[filter.key]"
                                :texts="texts"
                                :learnt="labels[filter.key]"
                                :remote-failed="remoteFailed[filter.key]"
                                :id-base="controlId(filter)"
                                @input="setValue(filter.key, $event)"
                                @learn="learn(filter.key, $event)"
                                @remote-failed="remoteFailed[filter.key] = true"
                                @announce="announce"
                            ></report-checklist>
                        </div>
                    </div>
                </section>

                <section v-if="hasDisplay" :aria-labelledby="ids.base + '-display'" :class="displayClass">
                    <h3 :id="ids.base + '-display'" class="text-xs font-semibold uppercase tracking-wider text-gray-500">{{ texts.display }}</h3>

                    <!-- Two columns while the panel is one; stacked in the narrow display column beside the filters -->
                    <div v-if="def.display.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-x-4 gap-y-4 mt-3">
                        <template v-for="control in def.display">
                            <report-columns
                                v-if="control.kind === 'columns'"
                                :key="control.key"
                                class="sm:col-span-2 lg:col-span-1"
                                :control="control"
                                :value="staged[control.key]"
                                :applied="applied[control.key]"
                                :texts="texts"
                                :changed="changedText(control)"
                                :note="resetNote(control)"
                                :id-base="controlId(control)"
                                @input="setValue(control.key, $event)"
                                @announce="announce"
                            ></report-columns>

                            <div v-else :key="control.key" class="min-w-0">
                                <report-switch
                                    v-if="control.kind === 'switch'"
                                    :name="controlId(control)"
                                    :label="control.label"
                                    :options="control.options"
                                    :value="staged[control.key]"
                                    :changed="changedText(control)"
                                    @input="setValue(control.key, $event)"
                                ></report-switch>

                                <template v-else>
                                    <label :for="controlId(control)" class="block mb-1 text-sm font-medium text-black truncate">
                                        {{ control.label }}<template v-if="changedText(control)"><span class="inline-block w-1.5 h-1.5 ms-1.5 align-middle rounded-full bg-purple" aria-hidden="true"></span><span class="sr-only">{{ ' ' + changedText(control) }}</span></template>
                                    </label>

                                    <select
                                        :id="controlId(control)"
                                        :value="staged[control.key]"
                                        class="h-9 w-full ps-3 pe-10 rounded-lg border border-light-gray bg-white text-sm focus:border-purple focus:ring-2 focus:ring-purple-200 rtl:bg-[position:left_0.5rem_center]"
                                        @change="setValue(control.key, $event.target.value)"
                                    >
                                        <option v-for="option in control.options" :key="option[0]" :value="option[0]">{{ option[1] }}</option>
                                    </select>
                                </template>
                            </div>
                        </template>
                    </div>

                    <div v-if="def.readonly.length" class="mt-4">
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500">{{ texts.saved_preferences }}</h4>

                        <dl class="mt-2 space-y-1 text-sm">
                            <div v-for="(row, index) in def.readonly" :key="index" class="flex flex-wrap gap-x-1">
                                <dt class="text-gray-500">{{ row.label }}:</dt>

                                <dd class="text-black">{{ row.value }}</dd>
                            </div>
                        </dl>

                        <a v-if="def.can_edit && def.edit_url" :href="def.edit_url" class="inline-block mt-2 text-sm font-medium text-purple hover:underline">
                            {{ texts.edit_report }}
                        </a>
                    </div>
                </section>

                <!-- The actions again, kept in view while the panel is long -->
                <div v-if="! phone && (editable || def.has_query)" ref="actions" :class="actionsClass">
                    <span v-if="editable" class="text-sm text-orange-800">{{ statusText }}</span>

                    <button v-if="dirty.length" :id="ids.footerDiscard" type="button" class="text-sm font-medium text-purple hover:underline" @click="discard(ids.footerUpdate)">
                        {{ texts.discard }}
                    </button>

                    <button v-if="def.has_query" :id="ids.footerReset" type="button" :title="texts.reset_hint" class="text-sm font-medium text-purple hover:underline" @click="reset(ids.footerReset)">
                        {{ texts.reset }}
                    </button>

                    <button v-if="editable" :id="ids.footerUpdate" type="button" :aria-disabled="canUpdate ? 'false' : 'true'" :title="updateTitle" :class="updateClass" @click="update(ids.footerUpdate)">
                        <i v-if="navigating" :class="spinnerClass" aria-hidden="true"></i>

                        <span :class="{'opacity-0': navigating}">{{ updateText }}</span>
                    </button>
                </div>
            </div>

            <!-- Phones: the actions close the whole region, so they stay in view over the strip as well -->
            <div v-if="phone && (editable || def.has_query)" ref="actions" :class="actionsClass">
                <button v-if="def.has_query" :id="ids.footerReset" type="button" :title="texts.reset_hint" class="inline-flex items-center min-h-11 px-2 text-sm font-medium text-purple hover:underline" @click="reset(ids.footerReset)">
                    {{ texts.reset }}
                </button>

                <button v-if="dirty.length" :id="ids.footerDiscard" type="button" class="inline-flex items-center min-h-11 px-2 text-sm font-medium text-purple hover:underline" @click="discard(ids.footerUpdate)">
                    {{ texts.discard }}
                </button>

                <button v-if="editable" :id="ids.footerUpdate" type="button" :aria-disabled="canUpdate ? 'false' : 'true'" :title="updateTitle" :class="updateClass" @click="update(ids.footerUpdate)">
                    <i v-if="navigating" :class="spinnerClass" aria-hidden="true"></i>

                    <span :class="{'opacity-0': navigating}">{{ updateText }}</span>
                </button>
            </div>
        </div>

        <p class="sr-only" aria-live="polite">{{ announcement }}</p>
    </div>
</template>

<script>
import flatpickr from 'flatpickr';

import ReportAmount from './ReportAmount';
import ReportChecklist from './ReportChecklist';
import ReportColumns from './ReportColumns';
import ReportDate from './ReportDate';
import ReportSwitch from './ReportSwitch';

import {
    activeFilters, allControls, buildUrl, clone, dirtyKeys, emptyValue, extraText, filterSummary, hasDateControl,
    hasToggle, initialState, isDate, isOutputUrl, normalize, normalizeDefinition, optionsText, pillText, resetBy,
    statusText, summaryLine, toggleText, translate, updateText, validate, valueText,
} from './state';

// Tailwind's sm breakpoint: below it the bar takes its phone layout
const PHONE = '(max-width: 639.98px)';

// Storage may be missing or throw (private windows, blocked site data): the bar then works without it
function readStorage(name, key) {
    try {
        return window[name].getItem(key);
    } catch (e) {
        return null;
    }
}

function writeStorage(name, key, value) {
    try {
        if (value === null) {
            window[name].removeItem(key);
        } else {
            window[name].setItem(key, value);
        }
    } catch (e) {
    }
}

// The flatpickr locale, loaded as AkauntingDate loads it
function loadPickerLocale(code) {
    if (! code || (code === 'en')) {
        return null;
    }

    try {
        return require(`flatpickr/dist/l10n/${code}.js`).default[code] || null;
    } catch (e) {
        return null;
    }
}

/**
 * The report options bar, which replaces the search string chips on report pages. The strip holds the settings
 * that always have a value, the Customise panel the data filters and display options, and the applied filters
 * show as pills. Edits are staged until Update report loads the page once, with the full state in its URL.
 */
export default {
    name: 'report-filter',

    components: {
        ReportAmount,
        ReportChecklist,
        ReportColumns,
        ReportDate,
        ReportSwitch,
    },

    props: {
        // App\Abstracts\Report::getFilterControls()
        definition: {
            type: [Object, Boolean],
            default: null,
        },
    },

    data() {
        const definition = normalizeDefinition(this.definition) || normalizeDefinition({});
        const state = initialState(definition);
        const expanded = {};
        const labels = {};
        const remoteFailed = {};

        definition.filters.forEach((filter) => {
            expanded[filter.key] = false;
            labels[filter.key] = {};
            remoteFailed[filter.key] = false;
        });

        return {
            applied: state.applied,
            staged: state.staged,
            extras: state.extras,
            // Names of the values picked from remote results, by filter key, for the pills and summaries
            labels: labels,
            // The filter rows that are open
            expanded: expanded,
            // The filters whose remote search failed, kept here as a checklist is rebuilt whenever its row opens
            remoteFailed: remoteFailed,
            panelOpen: false,
            regionOpen: false,
            phone: false,
            navigating: false,
            announcement: '',
        };
    },

    computed: {
        ready() {
            return normalizeDefinition(this.definition) !== null;
        },

        def() {
            return normalizeDefinition(this.definition) || normalizeDefinition({});
        },

        texts() {
            return this.def.texts;
        },

        ids() {
            const base = 'report-filter-' + this.idPart(this.def.id);

            return {
                base: base,
                toggle: base + '-toggle',
                panel: base + '-panel',
                options: base + '-options',
                region: base + '-region',
                clear: base + '-clear',
                discard: base + '-discard',
                reset: base + '-reset',
                update: base + '-update',
                footerDiscard: base + '-footer-discard',
                footerReset: base + '-footer-reset',
                footerUpdate: base + '-footer-update',
            };
        },

        panelKey() {
            return 'akaunting-report-filter-panel-' + this.def.id;
        },

        focusKey() {
            return 'akaunting-report-filter-focus-' + this.def.id;
        },

        dirty() {
            return dirtyKeys(this.def, this.applied, this.staged);
        },

        pending() {
            return this.dirty.length;
        },

        // Not "errors": vee-validate gives every component a computed errors bag of its own
        invalid() {
            return validate(this.def, this.staged, this.applied);
        },

        hasErrors() {
            return Object.keys(this.invalid).length > 0;
        },

        canUpdate() {
            return (this.pending > 0) && ! this.hasErrors && ! this.navigating;
        },

        // Whether anything can be changed here: without it there is no Update to offer
        editable() {
            return (this.def.strip.length + this.def.display.length + this.def.filters.length) > 0;
        },

        hasToggle() {
            return hasToggle(this.def);
        },

        hasDisplay() {
            return (this.def.display.length + this.def.readonly.length) > 0;
        },

        hasOptions() {
            return this.editable || this.hasToggle || this.def.has_query;
        },

        showRow() {
            return this.hasToggle || (this.pills.length > 0) || this.def.has_query || this.editable;
        },

        // Phones always show what ran; wider screens only where no date control states the window
        showSummary() {
            return this.phone || ! hasDateControl(this.def);
        },

        summaryText() {
            return summaryLine(this.def);
        },

        toggleText() {
            return toggleText(this.def, this.applied, this.texts, this.labels);
        },

        optionsText() {
            return optionsText(this.def, this.applied, this.pending, this.texts);
        },

        statusText() {
            return statusText(this.texts, this.pending);
        },

        updateText() {
            return updateText(this.texts, this.pending);
        },

        updateTitle() {
            if (this.hasErrors) {
                return this.texts.fix_errors;
            }

            return this.pending ? null : this.texts.no_changes;
        },

        // x-button's classes: the Blade component cannot be used here. Green 700, as white text needs 4.5:1; on
        // phones it is a 44px touch target
        updateClass() {
            return [
                'relative rounded-xl text-sm font-medium leading-6 whitespace-nowrap',
                this.phone ? 'h-11 px-4' : 'px-3 py-1.5',
                (this.canUpdate || this.navigating) ? 'bg-green-700 hover:bg-green-800 text-white' : 'bg-gray-100 text-gray-500 cursor-default',
            ];
        },

        // x-button.loading's dots
        spinnerClass() {
            return 'animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]';
        },

        filtersClass() {
            if (this.phone) {
                return 'mt-6';
            }

            return this.hasDisplay ? 'p-4 lg:col-span-3' : 'p-4 lg:col-span-5';
        },

        displayClass() {
            if (this.phone) {
                return 'mt-6';
            }

            return this.def.filters.length ? 'p-4 lg:col-span-2 border-t lg:border-t-0 lg:border-s border-gray-200' : 'p-4 lg:col-span-5';
        },

        actionsClass() {
            if (this.phone) {
                return 'sticky bottom-0 z-10 flex flex-wrap items-center justify-end gap-x-2 gap-y-2 mt-4 py-2 border-t border-gray-200 bg-white';
            }

            return 'lg:col-span-5 sticky bottom-0 z-10 flex flex-wrap items-center justify-end gap-x-4 gap-y-2 px-4 py-3 border-t border-gray-200 bg-white rounded-b-xl';
        },

        pills() {
            const pills = activeFilters(this.def, this.applied).map((filter) => ({
                id: this.ids.base + '-pill-' + this.idPart(filter.key),
                key: filter.key,
                text: pillText(filter, this.applied[filter.key], this.texts, this.labels[filter.key]),
                title: pillText(filter, this.applied[filter.key], this.texts, this.labels[filter.key], true),
                remove: translate(this.texts.remove_filter, {name: filter.label}),
            }));

            this.extras.forEach((term, index) => {
                const text = extraText(term, this.texts);

                pills.push({
                    id: this.ids.base + '-extra-' + index,
                    key: null,
                    index: index,
                    text: text,
                    title: text,
                    remove: translate(this.texts.remove_filter, {name: term}),
                });
            });

            return pills;
        },
    },

    watch: {
        // Leaving the page asks first while changes are staged
        pending(count) {
            if (count && ! this.guarding) {
                window.addEventListener('beforeunload', this.onBeforeUnload);

                this.guarding = true;
            } else if (! count && this.guarding) {
                window.removeEventListener('beforeunload', this.onBeforeUnload);

                this.guarding = false;
            }
        },

        phone() {
            this.$nextTick(this.syncScrollPadding);
        },

        regionOpen() {
            this.$nextTick(this.syncScrollPadding);
        },
    },

    created() {
        this.pickerLocale = loadPickerLocale(this.def.locale);
        this.media = null;
        this.guarding = false;
        this.paddedScroll = false;
        this.leavingForOutput = false;
        this.announceTimer = null;
        this.outputTimer = null;
        this.busyTimer = null;

        if (! this.ready) {
            return;
        }

        this.media = window.matchMedia ? window.matchMedia(PHONE) : null;
        this.phone = !! (this.media && this.media.matches);
        this.panelOpen = this.hasToggle && (readStorage('localStorage', this.panelKey) === '1');
    },

    mounted() {
        if (! this.ready) {
            return;
        }

        if (this.media) {
            if (this.media.addEventListener) {
                this.media.addEventListener('change', this.onMediaChange);
            } else {
                this.media.addListener(this.onMediaChange);
            }
        }

        document.addEventListener('click', this.onDocumentClick, true);
        window.addEventListener('pageshow', this.onPageShow);

        this.restoreFocus();
        this.syncScrollPadding();
    },

    updated() {
        this.syncScrollPadding();
    },

    beforeDestroy() {
        if (this.media) {
            if (this.media.removeEventListener) {
                this.media.removeEventListener('change', this.onMediaChange);
            } else {
                this.media.removeListener(this.onMediaChange);
            }
        }

        document.removeEventListener('click', this.onDocumentClick, true);
        document.removeEventListener('keydown', this.onStopKey);
        window.removeEventListener('pageshow', this.onPageShow);
        window.removeEventListener('beforeunload', this.onBeforeUnload);

        clearTimeout(this.announceTimer);
        clearTimeout(this.outputTimer);
        clearTimeout(this.busyTimer);

        if (this.paddedScroll) {
            document.documentElement.style.scrollPaddingBottom = '';
        }
    },

    methods: {
        idPart(value) {
            return String(value).replace(/[^A-Za-z0-9_-]/g, '-');
        },

        controlId(control) {
            return this.ids.base + '-' + this.idPart(control.key);
        },

        setValue(key, value) {
            this.$set(this.staged, key, value);
        },

        learn(key, names) {
            this.$set(this.labels, key, Object.assign({}, this.labels[key], names));
        },

        formatDate(date) {
            const parsed = isDate(date) ? flatpickr.parseDate(date, 'Y-m-d') : null;

            if (! parsed) {
                return date;
            }

            const locale = this.pickerLocale ? Object.assign({}, flatpickr.l10ns.default, this.pickerLocale) : undefined;

            return flatpickr.formatDate(parsed, this.def.date_format || 'Y-m-d', locale);
        },

        // The hidden text of a changed control's marker, empty while the control is unchanged
        changedText(control) {
            if (! this.dirty.includes(control.key)) {
                return '';
            }

            return translate(this.texts.changed, {
                value: valueText(control, this.applied[control.key], this.texts, this.labels[control.key], this.formatDate),
            });
        },

        errorText(key) {
            return this.invalid[key] ? translate(this.texts[this.invalid[key]]) : '';
        },

        summaryOf(filter) {
            return filterSummary(filter, this.staged[filter.key], this.texts, this.labels[filter.key]);
        },

        resetNote(control) {
            const other = resetBy(this.def, control, this.applied, this.staged);

            return other ? translate(this.texts.resets_because, {name: other.label}) : '';
        },

        // Cleared first and set a moment later, so the same message is read again
        announce(message) {
            clearTimeout(this.announceTimer);

            this.announcement = '';

            this.announceTimer = setTimeout(() => {
                this.announcement = message || '';
            }, 100);
        },

        setPanel(open) {
            this.panelOpen = open;

            writeStorage('localStorage', this.panelKey, open ? '1' : '0');
        },

        // A pill's body opens its filter
        openFilter(key) {
            if (this.phone) {
                this.regionOpen = true;
            } else {
                this.setPanel(true);
            }

            this.expanded[key] = true;

            this.$nextTick(() => {
                this.focusFirst([this.controlId({key: key})]);
            });
        },

        focusFirst(ids) {
            for (const id of ids) {
                const element = id ? document.getElementById(id) : null;

                // Skip what is hidden (a closed panel) or disabled
                if (element && ! element.disabled && element.getClientRects().length) {
                    element.focus();

                    // Focus opens a date's calendar, which would cover the report: flatpickr keeps its instance on
                    // the hidden input before the one people see
                    const picker = element.previousElementSibling ? element.previousElementSibling._flatpickr : null;

                    if (picker && picker.isOpen) {
                        picker.close();
                    }

                    return true;
                }
            }

            return false;
        },

        // The inputs of a control with an error, the one to fix first leading
        fieldIds(control) {
            const base = this.controlId(control);
            const value = normalize(control, this.staged[control.key]);

            switch (control.kind) {
                case 'range':
                    return (isDate(value.start) && ! isDate(value.end)) ? [base + '-end', base + '-start'] : [base + '-start', base + '-end'];
                case 'as_of':
                    return [base + '-date'];
                case 'amount':
                    return [base + '-min', base + '-max'];
                default:
                    return [base];
            }
        },

        focusFirstInvalid() {
            const key = Object.keys(this.invalid)[0];
            const control = key ? allControls(this.def).find((item) => item.key === key) : null;

            if (! control) {
                return;
            }

            const isFilter = this.def.filters.includes(control);

            if (this.phone) {
                this.regionOpen = true;
            } else if (isFilter) {
                this.setPanel(true);
            }

            if (isFilter) {
                this.expanded[key] = true;
            }

            this.$nextTick(() => {
                this.focusFirst(this.fieldIds(control));
            });
        },

        // Ctrl or Cmd+Enter runs the report from anywhere in the bar
        onKeydown(event) {
            if (event.defaultPrevented || event.isComposing || (event.key !== 'Enter') || ! (event.ctrlKey || event.metaKey)) {
                return;
            }

            event.preventDefault();

            this.update(event.target ? event.target.id : '');
        },

        update(trigger) {
            if (this.navigating) {
                return;
            }

            if (this.hasErrors) {
                this.announce(this.texts.fix_errors);
                this.focusFirstInvalid();

                return;
            }

            if (! this.pending) {
                this.announce(this.texts.no_changes);

                // Enter in a date: flatpickr blurs the input first, which would leave the focus nowhere
                if (trigger && (document.activeElement === document.body)) {
                    this.focusFirst([trigger]);
                }

                return;
            }

            this.navigate(buildUrl(this.def, this.staged, this.extras, this.applied), trigger);
        },

        // × and Clear filters apply at once, with every other staged change. What they load is checked, not the
        // staged state: emptying the filter in error is a way out of the error
        applyNow(changes, extras, trigger) {
            if (this.navigating) {
                return;
            }

            const staged = Object.assign(clone(this.staged), changes);

            if (Object.keys(validate(this.def, staged, this.applied)).length) {
                this.announce(this.texts.fix_errors);
                this.focusFirstInvalid();

                return;
            }

            this.navigate(buildUrl(this.def, staged, extras, this.applied), trigger);
        },

        removePill(pill) {
            const trigger = pill.id + '-remove';

            if (pill.key === null) {
                this.applyNow({}, this.extras.filter((term, index) => index !== pill.index), trigger);

                return;
            }

            const filter = this.def.filters.find((item) => item.key === pill.key);

            this.applyNow({[pill.key]: emptyValue(filter)}, this.extras, trigger);
        },

        clearFilters(trigger) {
            const changes = {};

            this.def.filters.forEach((filter) => {
                changes[filter.key] = emptyValue(filter);
            });

            this.applyNow(changes, [], trigger);
        },

        // Back to the saved settings: the bare URL, no confirmation, as Back undoes it
        reset(trigger) {
            if (! this.navigating) {
                this.navigate(this.def.action, trigger);
            }
        },

        discard(focus) {
            this.staged = clone(this.applied);

            this.announce(this.texts.changes_discarded);

            this.$nextTick(() => {
                this.focusFirst([focus, this.ids.update, this.ids.footerUpdate]);
            });
        },

        navigate(url, trigger) {
            writeStorage('sessionStorage', this.focusKey, trigger || this.ids.update);

            this.navigating = true;
            this.setBusy(true);

            // A load that is stopped (Esc) or never ends gives the bar back, so the changes can be applied again
            document.addEventListener('keydown', this.onStopKey);

            clearTimeout(this.busyTimer);

            this.busyTimer = setTimeout(this.stopNavigating, 20000);

            window.location.assign(url);
        },

        stopNavigating() {
            clearTimeout(this.busyTimer);

            document.removeEventListener('keydown', this.onStopKey);

            this.navigating = false;
            this.setBusy(false);
        },

        // Esc stops a page load, unless a widget, such as an open calendar, has taken it
        onStopKey(event) {
            if ((event.key === 'Escape') && ! event.defaultPrevented) {
                this.forgetFocus();
                this.stopNavigating();
            }
        },

        // The load will not happen, so a later visit must not take the focus back
        forgetFocus() {
            writeStorage('sessionStorage', this.focusKey, null);
        },

        setBusy(busy) {
            const content = document.getElementById('report-content');

            if (! content) {
                return;
            }

            if (busy) {
                content.setAttribute('aria-busy', 'true');
            } else {
                content.removeAttribute('aria-busy');
            }
        },

        // After an Update: the control that ran it gets the focus back, else the toggle or Update
        restoreFocus() {
            const id = readStorage('sessionStorage', this.focusKey);

            if (! id) {
                return;
            }

            writeStorage('sessionStorage', this.focusKey, null);

            this.$nextTick(() => {
                this.focusFirst([id, this.ids.toggle, this.ids.options, this.ids.update, this.ids.footerUpdate]);

                this.announce(this.texts.report_updated);
            });
        },

        syncScrollPadding() {
            const actions = this.$refs.actions;
            const open = this.phone ? this.regionOpen : (this.hasToggle && this.panelOpen);
            const height = (open && actions) ? actions.offsetHeight : 0;
            const root = document.documentElement;

            // The sticky actions must not cover the focused field (WCAG 2.2 SC 2.4.11)
            if (height) {
                root.style.scrollPaddingBottom = height + 'px';

                this.paddedScroll = true;
            } else if (this.paddedScroll) {
                root.style.scrollPaddingBottom = '';

                this.paddedScroll = false;
            }
        },

        onMediaChange(event) {
            this.phone = event.matches;
        },

        // Back from the next page through the browser's page cache: this page is live again
        onPageShow(event) {
            if (event.persisted) {
                this.forgetFocus();
                this.stopNavigating();
            }
        },

        // Print, PDF and Export output the applied state, so following them needs no confirmation. A click that
        // opens another tab (Print does) leaves this page in place, so the guard stays
        onDocumentClick(event) {
            const link = (event.target && event.target.closest) ? event.target.closest('a[href]') : null;

            if (! link || (link.target === '_blank') || event.ctrlKey || event.metaKey || event.shiftKey || (event.button !== 0)) {
                return;
            }

            if (! isOutputUrl(this.def.action, link.href, window.location.href)) {
                return;
            }

            this.leavingForOutput = true;

            clearTimeout(this.outputTimer);

            // A download leaves the page in place, maybe without asking first, so the pass lapses
            this.outputTimer = setTimeout(() => {
                this.leavingForOutput = false;
            }, 1000);
        },

        onBeforeUnload(event) {
            if (this.navigating || ! this.pending) {
                return undefined;
            }

            if (this.leavingForOutput) {
                this.leavingForOutput = false;

                return undefined;
            }

            event.preventDefault();
            event.returnValue = '';

            return '';
        },
    },
}
</script>
