<div id="widgets-latest-expenses" class="{{ $config->width }}">
    <div class="card">
        <div class="card-header border-bottom-0">
            <div class="row align-items-center">
                <div class="col-6 text-nowrap">
                    <h4 class="mb-0">{{ trans('dashboard.latest_expenses') }}</h4>
                </div>

                <div class="col-6 hidden-sm">
                    <span class="float-right">
                        <div class="dropdown">
                            <a class="btn btn-sm items-align-center py-2 mr-0 shadow-none--hover" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-ellipsis-v text-muted"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                {!! Form::button(trans('general.edit'), array(
                                    'type'    => 'button',
                                    'class'   => 'dropdown-item',
                                    'title'   => trans('general.edit'),
                                    '@click'  => 'onEditWidget(' . $config->widget->id . ')'
                                )) !!}
                                <div class="dropdown-divider"></div>
                                {!! Form::deleteLink($config->widget, 'common/widgets') !!}
                            </div>
                        </div>
                    </span>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-xs-4 col-md-4 text-left">{{ trans('general.date') }}</th>
                        <th class="col-xs-4 col-md-4 text-center">{{ trans_choice('general.categories', 1) }}</th>
                        <th class="col-xs-4 col-md-4 text-right">{{ trans('general.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($latest_expenses->count())
                        @foreach($latest_expenses as $item)
                            <tr class="row border-top-1">
                                <td class="col-xs-4 col-md-4 text-left">@date($item->paid_at)</td>
                                <td class="col-xs-4 col-md-4 text-center">{{ $item->category ? $item->category->name : trans_choice('general.bills', 2) }}</td>
                                <td class="col-xs-4 col-md-4 text-right">@money($item->amount, $item->currency_code, true)</td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="border-top-1">
                            <td colspan="3">
                                <div class="text-muted" id="datatable-basic_info" role="status" aria-live="polite">
                                    {{ trans('general.no_records') }}
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
