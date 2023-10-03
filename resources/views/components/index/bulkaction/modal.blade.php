<x-form 
    id="form--bulk-action-modal"
    method="POST"
    url="{{ $url }}"
>
    <x-form.input.hidden name="handle" :value="$handle" />

    @foreach ($selected as $key => $value)
        <input type="checkbox" name="selected" id="selected-{{ $key }}" value="{{ $value }}" data-type="multiple" checked="checked" class="hidden" />
    @endforeach

    {!! $html !!}
</x-form>
