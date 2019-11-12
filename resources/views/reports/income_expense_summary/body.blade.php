<div class="box-body">
    {!! $chart->render() !!}

    <hr>

    <div class="table table-responsive table-report">
        <table class="table table-bordered table-striped table-hover" id="tbl-payments">
            <thead>
            <tr>
                <th>{{ trans_choice('general.categories', 1) }}</th>
                @foreach($dates as $date)
                    <th class="text-right">{{ $date }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @if ($compares)
                @foreach($compares as $type =>  $categories)
                    @foreach($categories as $category_id =>  $category)
                        <tr>
                            @if($type == 'income')
                                <td>{{ $income_categories[$category_id] }}</td>
                            @else
                                <td>{{ $expense_categories[$category_id] }}</td>
                            @endif
                            @foreach($category as $item)
                                @if($type == 'income')
                                    <td class="text-right">@money($item['amount'], setting('general.default_currency'), true)</td>
                                @else
                                    <td class="text-right">@money(-$item['amount'], setting('general.default_currency'), true)</td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                @endforeach
            @else
                <tr>
                    <td colspan="13">
                        <h5 class="text-center">{{ trans('general.no_records') }}</h5>
                    </td>
                </tr>
            @endif
            </tbody>
            <tfoot>
            <tr>
                <th>{{ trans_choice('general.totals', 1) }}</th>
                @foreach($totals as $total)
                    <th class="text-right">
                        @if($total['amount'] == 0)
                            <span>@money($total['amount'], $total['currency_code'], true)</span>
                        @elseif($total['amount'] > 0)
                            <span class="text-green">@money($total['amount'], $total['currency_code'], true)</span>
                        @else
                            <span class="text-red">@money($total['amount'], $total['currency_code'], true)</span>
                        @endif
                    </th>
                @endforeach
            </tr>
            </tfoot>
        </table>
    </div>
</div>

@push('js')
{!! Charts::assets() !!}
@endpush
