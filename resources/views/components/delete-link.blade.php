<x-button
    type="button"
    class="{{ $class }}"
    @click="onDeleteViaConfirmation('delete-{{ $modelTable }}-{{ $id }}')"
    override="class"
>
    <span class="{{ $textClass }}">
        @if ($slot->isNotEmpty())
            {!! $slot !!}
        @else
            {!! $label !!}
        @endif
    </span>

    <x-form.input.hidden
        name="delete-{{ $modelTable }}-{{ $id }}"
        id="delete-{{ $modelTable }}-{{ $id }}"
        data-field="delete"
        data-action="{{ $action }}"
        data-title="{!! $title !!}"
        data-message="{!! $message !!}"
        data-cancel="{!! $cancelText !!}"
        data-delete="{!! $deleteText !!}"
    />
</x-button>
