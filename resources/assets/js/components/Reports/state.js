/**
 * The report options bar's logic as pure functions: no Vue, no DOM and no requests, so it can be tested on its own.
 *
 * The bar keeps two sets of values keyed by control key: applied, what the page shows, and staged, what Update
 * will load. The definition comes from App\Abstracts\Report::getFilterControls().
 */

const DATE = /^\d{4}-\d{2}-\d{2}$/;

// A plain decimal, as the readers parse it: no thousands separator, no decimal comma
const NUMBER = /^-?(\d+(\.\d*)?|\.\d+)$/;

const EMPTY_DEFINITION = {
    id: 0,
    action: '',
    edit_url: '',
    has_query: false,
    can_edit: false,
    locale: 'en',
    date_format: 'Y-m-d',
};

const compare = (a, b) => (a < b ? -1 : (a > b ? 1 : 0));

const text = (value) => ((value === undefined) || (value === null) ? '' : String(value));

const list = (value) => (Array.isArray(value) ? value : []);

const isControl = (control) => !! control && (typeof control === 'object') && !! control.key && !! control.kind;

export function clone(value) {
    return (value === undefined) ? undefined : JSON.parse(JSON.stringify(value));
}

export function isDate(value) {
    return DATE.test(text(value));
}

/**
 * The definition with every list and object it may lack, or null when there is nothing to render.
 */
export function normalizeDefinition(definition) {
    if (! definition || (typeof definition !== 'object') || Array.isArray(definition)) {
        return null;
    }

    return Object.assign({}, EMPTY_DEFINITION, definition, {
        action: text(definition.action),
        strip: list(definition.strip).filter(isControl),
        display: list(definition.display).filter(isControl),
        filters: list(definition.filters).filter(isControl),
        readonly: list(definition.readonly).filter((row) => row && (typeof row === 'object')),
        extras: list(definition.extras).map(text).filter((term) => term !== ''),
        summary: list(definition.summary).filter((row) => Array.isArray(row) && (row.length > 1)),
        texts: (definition.texts && (typeof definition.texts === 'object')) ? definition.texts : {},
    });
}

export function allControls(definition) {
    return definition.strip.concat(definition.display, definition.filters);
}

export function findControl(definition, key) {
    return allControls(definition).find((control) => control.key === key) || null;
}

function uniqueStrings(values) {
    const unique = [];

    values.forEach((value) => {
        value = text(value);

        if ((value !== '') && ! unique.includes(value)) {
            unique.push(value);
        }
    });

    return unique;
}

/**
 * A value in the shape the bar compares and writes: checklist ids as unique strings, without "not" when nothing
 * is chosen; columns as strings, or null for the report's default columns; amounts as trimmed strings.
 */
export function normalize(control, value) {
    switch (control.kind) {
        case 'checklist': {
            const ids = uniqueStrings(value && Array.isArray(value.ids) ? value.ids : []);

            return {ids: ids, not: ids.length ? (value.not === true) : false};
        }
        case 'columns':
            return Array.isArray(value) ? uniqueStrings(value) : null;
        case 'amount':
            return {min: text(value && value.min).trim(), max: text(value && value.max).trim()};
        case 'range':
            return {start: text(value && value.start), end: text(value && value.end)};
        default:
            return text(value);
    }
}

/**
 * Whether two values of a control are the same: checklist ids as sets, columns in their order.
 */
export function isSame(control, a, b) {
    const x = normalize(control, a);
    const y = normalize(control, b);

    switch (control.kind) {
        case 'checklist':
            return (x.not === y.not) && (x.ids.length === y.ids.length) && x.ids.every((id) => y.ids.includes(id));
        case 'columns':
            if ((x === null) || (y === null)) {
                return x === y;
            }

            return (x.length === y.length) && x.every((column, index) => column === y[index]);
        case 'amount':
            return (x.min === y.min) && (x.max === y.max);
        case 'range':
            return (x.start === y.start) && (x.end === y.end);
        default:
            return x === y;
    }
}

/**
 * What a filter holds when nothing is chosen, which is also what × and Clear filters stage.
 */
export function emptyValue(control) {
    switch (control.kind) {
        case 'checklist':
            return {ids: [], not: false};
        case 'amount':
            return {min: '', max: ''};
        case 'columns':
            return null;
        case 'range':
            return {start: '', end: ''};
        default:
            return '';
    }
}

/**
 * The applied values as the definition gives them, a staged copy to edit, and the search terms no control owns.
 */
export function initialState(definition) {
    const applied = {};

    allControls(definition).forEach((control) => {
        applied[control.key] = normalize(control, control.value);
    });

    return {
        applied: applied,
        staged: clone(applied),
        extras: definition.extras.slice(),
    };
}

/**
 * A staged value as it would load: restoring the default columns (null) while the URL has no columns token loads
 * the columns already applied, so it is no change.
 */
export function loadedValue(control, value, applied) {
    if ((control.kind === 'columns') && (normalize(control, value) === null) && ! control.in_url) {
        return applied;
    }

    return value;
}

/**
 * The keys whose staged value differs from the applied one, in display order: the date range is one key.
 */
export function dirtyKeys(definition, applied, staged) {
    return allControls(definition)
        .filter((control) => ! isSame(control, applied[control.key], loadedValue(control, staged[control.key], applied[control.key])))
        .map((control) => control.key);
}

export function isActive(filter, value) {
    const normalized = normalize(filter, value);

    switch (filter.kind) {
        case 'checklist':
            return normalized.ids.length > 0;
        case 'radio':
            return normalized !== '';
        case 'amount':
            return (normalized.min !== '') || (normalized.max !== '');
        default:
            return false;
    }
}

export function activeFilters(definition, values) {
    return definition.filters.filter((filter) => isActive(filter, values[filter.key]));
}

/**
 * The strip or display control whose staged change resets this one (its resets list), or null: the reset
 * control's body gives way to a note and its token is left out on Update.
 */
export function resetBy(definition, control, applied, staged) {
    for (const key of list(control.resets)) {
        const other = findControl(definition, key);

        if (other && ! isSame(other, applied[key], staged[key])) {
            return other;
        }
    }

    return null;
}

function validateControl(control, value) {
    const normalized = normalize(control, value);

    switch (control.kind) {
        case 'range':
            if (! isDate(normalized.start) || ! isDate(normalized.end)) {
                return 'enter_dates';
            }

            return (normalized.start > normalized.end) ? 'date_order' : null;
        case 'as_of':
            return isDate(normalized) ? null : 'enter_date';
        case 'amount':
            if ([normalized.min, normalized.max].some((number) => (number !== '') && ! NUMBER.test(number))) {
                return 'enter_amount';
            }

            if ((normalized.min === '') || (normalized.max === '')) {
                return null;
            }

            return (Number(normalized.min) > Number(normalized.max)) ? 'amount_order' : null;
        default:
            return null;
    }
}

/**
 * The staged values that cannot be loaded, as control key => texts key of the message, in display order. A control
 * a staged setting resets is left out: Update drops its token, and its fields are not shown.
 */
export function validate(definition, staged, applied) {
    const errors = {};

    allControls(definition).forEach((control) => {
        if (applied && resetBy(definition, control, applied, staged)) {
            return;
        }

        const message = validateControl(control, staged[control.key]);

        if (message) {
            errors[control.key] = message;
        }
    });

    return errors;
}

/**
 * Ids in the order the URL writes them, so equal choices share one report cache key: numerically when they are
 * all digits, else alphabetically.
 */
export function sortIds(ids) {
    const sorted = ids.slice();

    if (sorted.every((id) => /^\d+$/.test(id))) {
        return sorted.sort((a, b) => (Number(a) - Number(b)) || compare(a, b));
    }

    return sorted.sort(compare);
}

/**
 * The search string terms of the staged state, in a fixed order: strip, display, filters, then the other terms
 * word for word.
 */
export function buildTokens(definition, staged, extras, applied) {
    applied = applied || staged;

    const tokens = [];
    const isReset = (control) => resetBy(definition, control, applied, staged) !== null;

    definition.strip.concat(definition.display).forEach((control) => {
        const value = normalize(control, staged[control.key]);

        switch (control.kind) {
            case 'as_of':
            case 'select':
            case 'switch':
                if (value !== '') {
                    tokens.push(control.key + ':' + value);
                }

                break;
            case 'columns':
                // The default columns need no token, and a column choice survives a reload only once it is in the URL
                if (value && value.length
                    && (control.in_url || ! isSame(control, applied[control.key], value))
                    && ! isReset(control)) {
                    tokens.push(control.key + ':' + value.join(','));
                }

                break;
        }
    });

    definition.filters.forEach((filter) => {
        const value = normalize(filter, staged[filter.key]);

        if (! isActive(filter, value) || isReset(filter)) {
            return;
        }

        switch (filter.kind) {
            case 'checklist':
                tokens.push((value.not ? 'not ' : '') + filter.key + ':' + sortIds(value.ids).join(','));

                break;
            case 'radio':
                // Written as it is: Overdue is "partial,sent,viewed due_at<=today"
                tokens.push(filter.key + ':' + value);

                break;
            case 'amount': {
                const keys = filter.keys || {};

                if (value.min !== '') {
                    tokens.push((keys.min || ('min_' + filter.key)) + ':' + value.min);
                }

                if (value.max !== '') {
                    tokens.push((keys.max || ('max_' + filter.key)) + ':' + value.max);
                }

                break;
            }
        }
    });

    return tokens.concat(list(extras).map(text).filter((term) => term !== ''));
}

/**
 * The query Update loads: the search string, always present, then the dates of a date range control.
 */
export function buildQuery(definition, staged, extras, applied) {
    const query = {
        search: buildTokens(definition, staged, extras, applied).join(' '),
    };

    const range = definition.strip.find((control) => control.kind === 'range');

    if (range) {
        const value = normalize(range, staged[range.key]);

        query.start_date = value.start;
        query.end_date = value.end;
    }

    return query;
}

export function buildUrl(definition, staged, extras, applied) {
    return definition.action + '?' + new URLSearchParams(buildQuery(definition, staged, extras, applied)).toString();
}

/**
 * Whether a link opens this report's print, PDF or export output, which print the applied state, so leaving the
 * page for it needs no confirmation.
 */
export function isOutputUrl(action, href, base) {
    try {
        const path = (url) => new URL(url, base).pathname.replace(/\/+$/, '');
        const report = path(action);
        const target = path(href);

        return ['print', 'pdf', 'export'].some((output) => target === (report + '/' + output));
    } catch (e) {
        return false;
    }
}

/**
 * The listing URL a remote search calls: its search string plus the quoted query and a limit.
 */
export function remoteSearchUrl(url, query, base) {
    const parsed = new URL(url, base);
    const search = text(parsed.searchParams.get('search')).trim();
    const term = text(query).replace(/"/g, '').trim();

    parsed.searchParams.set('search', [search, '"' + term + '"', 'limit:20'].filter((part) => part !== '').join(' '));

    return parsed.toString();
}

/**
 * A listing's rows as [value, label] options: ids first, as DoubleEntry categories also have a code.
 */
export function remoteOptions(body) {
    let rows = body ? body.data : null;

    // A paginated listing nests its rows once more
    if (rows && ! Array.isArray(rows) && Array.isArray(rows.data)) {
        rows = rows.data;
    }

    const options = [];

    list(rows).forEach((row) => {
        if (! row || (typeof row !== 'object')) {
            return;
        }

        const value = text(((row.id !== undefined) && (row.id !== null)) ? row.id : row.code);

        if (value !== '') {
            options.push([value, text(row.title || row.display_name || row.name) || value]);
        }
    });

    return options;
}

export function matchesSearch(label, query) {
    const term = text(query).trim().toLowerCase();

    return (term === '') || text(label).toLowerCase().includes(term);
}

function escapeRegExp(value) {
    return value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

/**
 * A line with its :name placeholders replaced, as Laravel does: :Name and :NAME give the value capitalised and
 * upper case. One pass, so a value is never replaced again.
 */
export function translate(line, replace) {
    const value = text(line);
    const pairs = {};

    Object.keys(replace || {}).forEach((key) => {
        const replacement = text(replace[key]);

        pairs[':' + key.toUpperCase()] = replacement.toUpperCase();
        pairs[':' + key.charAt(0).toUpperCase() + key.slice(1)] = replacement.charAt(0).toUpperCase() + replacement.slice(1);
        pairs[':' + key] = replacement;
    });

    const placeholders = Object.keys(pairs).sort((a, b) => b.length - a.length);

    if (! placeholders.length) {
        return value;
    }

    return value.replace(new RegExp(placeholders.map(escapeRegExp).join('|'), 'g'), (placeholder) => pairs[placeholder]);
}

const CONDITION = /^\s*[{[]([^[\]{}]*)[}\]]/;

function conditionMatches(condition, count) {
    if (condition.includes(',')) {
        const [from, to] = condition.split(',', 2).map((part) => part.trim());

        return ((from === '*') || (count >= Number(from))) && ((to === '*') || (count <= Number(to)));
    }

    return (condition.trim() !== '') && (Number(condition) === count);
}

/**
 * The form of a Laravel plural line for a count: the form whose {n} or [a,b] condition holds, else the first form
 * for one and the last otherwise, without its condition.
 */
export function choosePlural(line, count) {
    const forms = text(line).split('|');

    for (const form of forms) {
        const condition = form.match(CONDITION);

        if (condition && conditionMatches(condition[1], count)) {
            return form.replace(CONDITION, '').trim();
        }
    }

    const form = ((count === 1) || (forms.length === 1)) ? forms[0] : forms[forms.length - 1];

    return form.replace(CONDITION, '').trim();
}

export function pluralize(line, count, replace) {
    return translate(choosePlural(line, count), Object.assign({count: count}, replace));
}

/**
 * Up to two names, then how many more there are.
 */
export function shortList(names, texts) {
    if (names.length <= 2) {
        return names.join(', ');
    }

    return names.slice(0, 2).join(', ') + ' ' + translate(texts.more, {count: names.length - 2});
}

export function optionLabel(options, value) {
    const option = list(options).find((option) => text(option[0]) === value);

    return option ? text(option[1]) : value;
}

/**
 * The name of a filter value: from the preloaded options, the names the server resolved, or the names learnt
 * from remote results, else the value itself.
 */
export function labelOf(filter, value, learnt) {
    const option = list(filter.options).find((option) => text(option[0]) === value);

    if (option) {
        return text(option[1]);
    }

    if (filter.labels && (filter.labels[value] !== undefined)) {
        return text(filter.labels[value]);
    }

    if (learnt && (learnt[value] !== undefined)) {
        return text(learnt[value]);
    }

    return value;
}

/**
 * What a filter's disclosure row says about its value.
 */
export function filterSummary(filter, value, texts, learnt) {
    const normalized = normalize(filter, value);

    switch (filter.kind) {
        case 'checklist': {
            if (! normalized.ids.length) {
                return text(filter.all);
            }

            const names = shortList(normalized.ids.map((id) => labelOf(filter, id, learnt)), texts);

            return normalized.not ? translate(texts.not, {values: names}) : names;
        }
        case 'radio':
            return (normalized === '') ? text(filter.all) : labelOf(filter, normalized, learnt);
        case 'amount':
            if ((normalized.min !== '') && (normalized.max !== '')) {
                return normalized.min + ' – ' + normalized.max;
            }

            if (normalized.min !== '') {
                return '≥ ' + normalized.min;
            }

            return (normalized.max !== '') ? '≤ ' + normalized.max : text(texts.any);
        default:
            return '';
    }
}

/**
 * A pill's text for an applied filter; full lists every name, for the pill's title.
 */
export function pillText(filter, value, texts, learnt, full) {
    const normalized = normalize(filter, value);

    if (filter.kind !== 'checklist') {
        return filter.label + ': ' + filterSummary(filter, normalized, texts, learnt);
    }

    const names = normalized.ids.map((id) => labelOf(filter, id, learnt));
    const values = full ? names.join(', ') : shortList(names, texts);

    if (normalized.not) {
        return translate(texts.is_not, {name: filter.label, values: values});
    }

    return (filter.pill || filter.label) + ': ' + values;
}

export function extraText(term, texts) {
    return translate(texts.search_term, {term: term});
}

export function presetIndex(control, value) {
    const normalized = normalize(control, value);

    return list(control.presets).findIndex((preset) => (control.kind === 'range')
        ? ((preset.start === normalized.start) && (preset.end === normalized.end))
        : (preset.date === normalized));
}

export function presetFor(control, value) {
    const index = presetIndex(control, value);

    return (index < 0) ? null : control.presets[index];
}

/**
 * A value as text, for the hidden "changed, applied value …" of a changed control. Dates are formatted by the
 * caller's function.
 */
export function valueText(control, value, texts, learnt, formatDate) {
    const format = formatDate || ((date) => date);
    const normalized = normalize(control, value);

    switch (control.kind) {
        case 'range':
        case 'as_of': {
            const dates = (control.kind === 'range')
                ? format(normalized.start) + ' – ' + format(normalized.end)
                : format(normalized);
            const preset = presetFor(control, normalized);

            return preset ? preset.label + ' (' + dates + ')' : dates;
        }
        case 'select':
        case 'switch':
            return optionLabel(control.options, normalized);
        case 'columns':
            return (normalized === null)
                ? text(texts.default_columns)
                : normalized.map((column) => optionLabel(control.options, column)).join(', ');
        default:
            return filterSummary(control, normalized, texts, learnt);
    }
}

export function hasDateControl(definition) {
    return definition.strip.some((control) => (control.kind === 'range') || (control.kind === 'as_of'));
}

/**
 * Whether the Customise toggle has anything to open: a filter, a display option or a saved preference.
 */
export function hasToggle(definition) {
    return (definition.filters.length + definition.display.length + definition.readonly.length) > 0;
}

/**
 * The toggle's text: a lone filter names itself and its value, otherwise "Customise" and the active filters.
 */
export function toggleText(definition, applied, texts, labels) {
    if ((definition.filters.length === 1) && ! definition.display.length && ! definition.readonly.length) {
        const filter = definition.filters[0];

        return filter.label + ': ' + filterSummary(filter, applied[filter.key], texts, (labels || {})[filter.key]);
    }

    const count = activeFilters(definition, applied).length;

    return text(texts.customise) + (count ? ' · ' + count : '');
}

export function optionsText(definition, applied, pending, texts) {
    const count = activeFilters(definition, applied).length;

    return text(texts.report_options)
        + (count ? ' · ' + count : '')
        + (pending ? ' · ' + translate(texts.pending, {count: pending}) : '');
}

export function updateText(texts, pending) {
    return text(texts.update) + (pending ? ' · ' + pending : '');
}

export function statusText(texts, pending) {
    return pending ? pluralize(texts.changes_not_applied, pending) : '';
}

/**
 * The applied settings on one line, as label: value pairs.
 */
export function summaryLine(definition) {
    return definition.summary.map((row) => text(row[0]) + ': ' + text(row[1])).join(' · ');
}
