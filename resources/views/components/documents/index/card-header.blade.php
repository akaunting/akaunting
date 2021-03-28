@if (!$hideBulkAction)
    <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
        {!! Form::open([
            'method' => 'GET',
            'route' => $formCardHeaderRoute,
            'role' => 'form',
            'class' => 'mb-0'
        ]) !!}
            @if (!$hideSearchString)
                <div class="align-items-center" v-if="!bulk_action.show">
                    <x-search-string model="{{ $searchStringModel }}" />
                </div>
            @endif

            {{ Form::bulkActionRowGroup($textBulkAction, $bulkActions, $bulkActionRouteParameters) }}
        {!! Form::close() !!}
    </div>
@else 
    @if (!$hideSearchString)
        <div class="card-header border-bottom-0">
            {!! Form::open([
                'method' => 'GET',
                'route' => $formCardHeaderRoute,
                'role' => 'form',
                'class' => 'mb-0'
            ]) !!}
                <div class="align-items-center">
                    <x-search-string model="{{ $searchStringModel }}" />
                </div>
            {!! Form::close() !!}
        </div>
    @endif
@endif
