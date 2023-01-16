<div class="{{ $class }}">
    <x-button
        type="button"
        class="{{ $textClass }}"
        @click="onDeleteViaConfirmation('delete-{{ $modelTable }}-{{ $id }}')"
        override="class"
        {{ $attributes }}
    >
       
        @if ($slot->isNotEmpty())
            {!! $slot !!}
        @else
            {!! $label !!}
        @endif
        
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
</div>
