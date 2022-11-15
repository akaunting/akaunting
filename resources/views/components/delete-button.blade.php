@mobile
<button
    type="button"
    class="rw-full flex items-center text-red sm:text-purple px-2 h-9 leading-9"
    @click="onDeleteViaConfirmation('delete-{{ $modelTable }}-{{ $id }}')"
    override="class"
    {{ $attributes }}
>

    @if ($slot->isNotEmpty())
        {!! $slot !!}
    @else
        <span class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100">{!! $label !!}</span>
    @endif
@else
<button
    type="button"
    class="relative bg-white hover:bg-gray-100 border py-0.5 px-1 cursor-pointer group/tooltip index-actions"
    @click="onDeleteViaConfirmation('delete-{{ $modelTable }}-{{ $id }}')"
    override="class"
    {{ $attributes }}
>

    @if ($slot->isNotEmpty())
        {!! $slot !!}
    @else
        <span class="material-icons-outlined text-purple text-lg pointer-events-none">delete</span>
        <div class="inline-block absolute invisible z-10 py-1 px-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 shadow-sm opacity-0 whitespace-nowrap -top-10 -left-2 group-hover/tooltip:opacity-100 group-hover/tooltip:visible" data-tooltip-placement="top">
            <span>{!! $label !!}</span>
            <div class="absolute w-2 h-2 -bottom-1 before:content-[' '] before:absolute before:w-2 before:h-2 before:bg-white before:border-gray-200 before:transform before:rotate-45 before:border before:border-t-0 before:border-l-0" data-popper-arrow></div>
        </div>
    @endif
@endmobile

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
</button>
