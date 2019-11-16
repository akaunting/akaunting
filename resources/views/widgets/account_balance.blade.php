<div id="widgets-account-balance" class="{{ $config->width }}">
    <div class="card">
        <div class="card-header border-bottom-0">
            <div class="row align-items-center">

                <div class="col-6 text-nowrap">
                    <h4 class="mb-0">{{ trans('dashboard.account_balance') }}</h4>
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
