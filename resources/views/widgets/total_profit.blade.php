<div id="widgets-total-profit" class="{{ $model->settings->width }}">
    <div class="card bg-gradient-success card-stats">
        @include('partials.widgets.stats_header', ['header_class' => 'border-bottom-0'])

        <div class="card-body">
            <div class="row">
                <div class="col">
                    <h5 class="text-uppercase text-white mb-0">{{ $model->name }}</h5>
                    <span class="font-weight-bold text-white mb-0">@money($totals['current'], setting('default.currency'), true)</span>
                </div>

                <div class="col-auto">
                    <div class="icon icon-shape bg-white text-success rounded-circle shadow">
                        <i class="fa fa-heart"></i>
                    </div>
                </div>
            </div>

            <p class="mt-3 mb-0 text-sm cursor-default">
                <span class="text-white">{{ trans('general.upcoming') }}</span>
                <el-tooltip
                content="{{ trans('widgets.open_profit') }}: {{ $totals['open'] }} / {{ trans('widgets.overdue_profit') }}: {{ $totals['overdue'] }}"
                effect="light"
                :open-delay="200"
                placement="top"
                popper-class="text-dark">
                    <span class="text-white font-weight-bold float-right">{{ $totals['open'] }} / {{ $totals['overdue'] }}</span>
                </el-tooltip>
            </p>
        </div>
    </div>
</div>
