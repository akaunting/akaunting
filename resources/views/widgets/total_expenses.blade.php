<div id="widgets-total-expenses" class="{{ $config->width }}">
    <div class="card bg-gradient-danger card-stats">
        <span>
            <div class="dropdown card-action-button">
                <a class="btn btn-sm items-align-center py-2 mr-0 shadow-none--hover" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-v text-white"></i>
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

        <div class="card-body">
            <div class="row">
                <div class="col">
                    <h5 class="text-uppercase text-white mb-0">{{ trans('dashboard.total_expenses') }}</h5>
                    <span class="font-weight-bold text-white mb-0">@money($total_expenses['total'], setting('default.currency'), true)</span>
                </div>

                <div class="col-auto">
                    <div class="icon icon-shape  bg-white text-danger rounded-circle shadow">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                </div>
            </div>

            <p class="mt-3 mb-0 text-sm"  title="{{ trans('dashboard.open_bills') }}: {{ $total_expenses['open_bill'] }}<br>{{ trans('dashboard.overdue_bills') }}: {{ $total_expenses['overdue_bill'] }}" data-toggle="tooltip" data-html="true">
                <span class="text-white">{{ trans('dashboard.payables') }}</span>
                <span class="text-white font-weight-bold float-right">{{ $total_expenses['open_bill'] }} / {{ $total_expenses['overdue_bill'] }}</span>
            </p>
        </div>
    </div>
</div>
