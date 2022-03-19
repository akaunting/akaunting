<div id="widget-{{ $class->model->id }}" class="{{ $class->model->settings->width }}">
    <div class="card">
        @include($class->views['header'], ['header_class' => 'border-bottom-0'])

        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-xs-6 col-md-6 text-left">{{ trans('general.name') }}</th>
                        <th class="col-xs-6 col-md-6 text-right">{{ trans('general.balance') }}</th>
                    </tr>
                </thead>
                <tbody class="thead-light">
                    @php $total = 0; @endphp
                    @foreach($accounts as $item)
                        @php $total += $item->balance @endphp
                        <tr class="row border-top-1 tr-py">
                            <td class="col-xs-6 col-md-6 text-left long-texts">{{ $item->name }}</td>
                            <td class="col-xs-6 col-md-6 text-right">@money($item->balance, $item->currency_code, true)</td>
                        </tr>
                    @endforeach
                </tbody>
                <div class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-xs-6 col-md-6 text-left text-lg @if ($total < 0) text-danger  @endif">TOTAL</th>
                        <th class="col-xs-6 col-md-6 text-right text-xl  @if ($total < 0) text-danger  @endif">@money($total, $item->currency_code, true)</th>
                    </tr>
                </div>
            </table>
        </div>
    </div>
</div>
