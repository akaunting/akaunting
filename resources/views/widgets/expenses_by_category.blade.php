<div id="widgets-expenses-by-category" class="{{ $config->width }}">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">

                <div class="col-6 text-nowrap">
                    <h4 class="mb-0">{{ trans('dashboard.expenses_by_category') }}</h4>
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

        <div class="card-body" id="expense-category-doughnut">
            <div class="chart dashboard-categories">
                {!! $donut_expenses->container() !!}
            </div>
        </div>
    </div>
</div>

@push('charts')
    <script>
        var expense_category_doughnut = new Vue({
            el: '#expense-category-doughnut',
        });
    </script>
@endpush

@push('body_scripts')
    {!! $donut_expenses->script() !!}
@endpush
