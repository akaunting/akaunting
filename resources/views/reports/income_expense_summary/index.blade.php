@extends('layouts.admin')

@section('title', trans_choice('reports.summary.income_expense', 1))

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header">
        <div class="pull-left" style="margin-left: 23px">
            <a href="{{ url('reports/income-expense-summary') }}?year={{ request('year', $this_year) }}"><span class="badge @if (request('status') == '') bg-green @else bg-default @endif">{{ trans('general.all') }}</span></a>
            <a href="{{ url('reports/income-expense-summary') }}?status=paid&year={{ request('year', $this_year) }}"><span class="badge @if (request('status') == 'paid') bg-green @else bg-default @endif">{{ trans('invoices.paid') }}</span></a>
            <a href="{{ url('reports/income-expense-summary') }}?status=upcoming&year={{ request('year', $this_year) }}"><span class="badge @if (request('status') == 'upcoming') bg-green @else bg-default @endif">{{ trans('general.upcoming') }}</span></a>
        </div>
        {!! Form::open(['url' => 'reports/income-expense-summary', 'role' => 'form', 'method' => 'GET']) !!}
        <div class="pull-right">
            {!! Form::select('year', $years, request('year', $this_year), ['class' => 'form-control input-filter input-sm', 'onchange' => 'this.form.submit()']) !!}
        </div>
        {!! Form::close() !!}
    </div>
    <div class="box-body">
        {!! $chart->render() !!}

        <hr>

        <div class="table table-responsive">
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
                            <td class="text-right">@money($item['amount'], $item['currency_code'], true)</td>
                            @else
                            <td class="text-right">@money(-$item['amount'], $item['currency_code'], true)</td>
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
    <!-- /.box-body -->
</div>
<!-- /.box -->
@endsection

@push('js')
{!! Charts::assets() !!}
@endpush
