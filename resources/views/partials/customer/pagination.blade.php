<div class="pull-left" style="margin-top: 7px;">
    <small>{{ trans('pagination.showing', ['first' => $items->firstItem(), 'last' => $items->lastItem(), 'total' => $items->total(), 'type' => strtolower(trans_choice('general.' . $type, 2))]) }}</small>
</div>
<div class="pull-right">
    {!! $items->appends(request()->except('page'))->links() !!}
</div>