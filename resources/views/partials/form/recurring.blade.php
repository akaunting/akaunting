@php
    if (($page == 'create') || !$model->recurring()->count()) {
        $frequency = old('recurring_frequency', 'no');
        $interval = old('recurring_interval', 1);
        $custom_frequency = old('recurring_custom_frequency', 'monthly');
        $count = old('recurring_count', 0);
    } else {
        $r = $model->recurring;

        $frequency = old('recurring_frequency', ($r->interval != 1) ? 'custom' : $r->frequency);
        $interval = old('recurring_interval', $r->interval);
        $custom_frequency = old('recurring_custom_frequency', $r->frequency);
        $count = old('recurring_count', $r->count);
    }
@endphp

<akaunting-recurring
    :form-classes="[{'has-error': form.errors.get('recurring_frequency')}]"
    title="{{ trans('recurring.recurring') }}"
    placeholder="{{ trans('general.form.select.field', ['field' => trans('recurring.recurring')]) }}"

    :frequency-options="{{ json_encode($recurring_frequencies) }}"
    :frequency-value="'{{ $frequency }}'"
    :frequency-error="form.errors.get('recurring_frequency')"

    :interval-value="'{{ $interval }}'"
    :interval-error="form.errors.get('recurring_interval')"

    :custom-frequency-options="{{ json_encode($recurring_custom_frequencies) }}"
    :custom-frequency-value="'{{ $custom_frequency }}'"
    :custom-frequency-error="form.errors.get('custom_frequency')"

    :count-value="'{{ $count }}'"
    :count-error="form.errors.get('recurring_count')"

    @recurring_frequency="form.recurring_frequency = $event"
    @recurring_interval="form.recurring_interval = $event"
    @recurring_custom_frequency="form.recurring_custom_frequency = $event"
    @recurring_count="form.recurring_count = $event"
>
</akaunting-recurring>
