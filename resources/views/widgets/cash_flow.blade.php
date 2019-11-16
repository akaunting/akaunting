<div id="widgets-cash-flow" class="{{ $config->width }}">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-4 text-nowrap">
                    <h4 class="mb-0">{{ trans('dashboard.cash_flow') }}</h4>
                </div>

                <div class="col-8 text-right hidden-sm">
                    <span>
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

        <div class="card-body mt--4" id="cashflow">
            <div class="chart">
                {!! $cashflow->container() !!}
            </div>
        </div>
    </div>
</div>

@push('charts')
    <script>
        var cash_flow = new Vue({
            el: '#cashflow',
        });
    </script>
@endpush

@push('body_scripts')
    {!! $cashflow->script() !!}
@endpush
