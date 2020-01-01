<div id="widgets-account-balance" class="{{ $model->settings->width }}">
    <div class="card">
        @include('partials.widgets.standard_header', ['header_class' => 'border-bottom-0'])

        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-xs-6 col-md-6 text-left">{{ trans('general.name') }}</th>
                        <th class="col-xs-6 col-md-6 text-right">{{ trans('general.balance') }}</th>
                    </tr>
                </thead>
                <tbody class="thead-light">
                    @foreach($accounts as $item)
                        <tr class="row border-top-1">
                            <td class="col-xs-6 col-md-6 text-left">{{ $item->name }}</td>
                            <td class="col-xs-6 col-md-6 text-right">@money($item->balance, $item->currency_code, true)</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
