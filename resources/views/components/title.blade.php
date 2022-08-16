@if (strlen($slot) >= 25 )
    <x-tooltip id="page-title" placement="bottom" message="{!! $slot !!}">
        <div class="truncate" style="width: 22rem;">
            {!! $slot !!}
        </div>
    </x-tooltip>
@else 
    {!! $slot !!}
@endif