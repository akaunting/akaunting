@stack('pagination_start')
    @if ($items->firstItem())
        <div class="col-6 d-flex align-items-center">
            {!! Form::select('limit', $limits, request('limit', setting('default.list_limit', '25')), ['class' => 'form-control form-control-sm d-inline-block w-auto d-none d-md-block', '@change' => 'onChangePaginationLimit($event)']) !!}
            <span class="table-text d-none d-lg-block ml-2">
                {{ trans('pagination.page') }}
                {{ trans('pagination.showing', ['first' => $items->firstItem(), 'last' => $items->lastItem(), 'total' => $items->total()]) }}
            </span>
        </div>

        <div class="col-6">
            <nav class="float-right">
                {!! $items->withPath(request()->url())->appends(request()->except('page'))->links() !!}
            </nav>
        </div>
    @else
        <div class="col-12" id="datatable-basic_info" role="status" aria-live="polite">
            <small>{{ trans('general.no_records') }}</small>
        </div>
    @endif
@stack('pagination_end')
