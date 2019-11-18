@stack('pagination_start')
    @if ($items->firstItem())
        <div class="col-6">
            <span class="table-text hidden-lg">
                {{ trans('general.show') }}
            </span>
            {!! Form::select('limit', $limits, request('limit', setting('default.list_limit', '25')), ['class' => 'form-control form-control-sm table-header-search hidden-md', 'onchange' => 'this.form.submit()']) !!}
            <span class="table-text hidden-lg">
                {{ trans('pagination.page') }}
                {{ trans('pagination.showing', ['first' => $items->firstItem(), 'last' => $items->lastItem(), 'total' => $items->total(), 'type' => strtolower((isset($title)) ? $title : trans_choice('general.' . $type, 2))]) }}
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
