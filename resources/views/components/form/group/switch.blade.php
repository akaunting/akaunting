@stack($name . '_input_start')

  <akaunting-switch
    name="{{ $name }}"
    value="{{ (int) $value }}"
    label="{{ trans('general.enabled') }}"

    @if (! empty($attributes['model']))
    :model="{{ $attributes['model'] }}"
    @endif

    @if (! empty($attributes['v-model']))
    @interface="form.errors.clear('{{ $attributes['v-model'] }}'); {{ $attributes['v-model'] . ' = $event' }}"
    @elseif (!empty($attributes['data-field']))
    @interface="form.errors.clear('{{ 'form.' . $attributes['data-field'] . '.' . enabled }}'); {{ 'form.' . $attributes['data-field'] . '.' . enabled . ' = $event' }}"
    @else
    @interface="form.errors.clear('enabled'); form.enabled = $event"
    @endif

    @if (!empty($attributes['change']))
    @change="{{ $attributes['change'] }}($event)"
    @endif
    >
  </akaunting-switch>

@stack($name . '_input_end')
