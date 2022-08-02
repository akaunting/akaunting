@if (strlen($slot) >= 25 )
    <x-tooltip id="page-title" placement="bottom" message="{!! $slot !!}">
        <div class="w-96 truncate">
            {!! $slot !!}
        </div>
    </x-tooltip>
@else 
    {!! $slot !!}
@endif