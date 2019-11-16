@if (trim($__env->yieldContent('widget-title')))
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-4 text-nowrap">
                <h4 class="mb-0">@yield('widget-title')</h4>
            </div>

            <div class="col-8 text-right hidden-sm">
                @yield('button')

                <span>
                    <div class="dropdown">
                        <a class="btn btn-sm items-align-center py-2 mr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-v text-muted"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a class="dropdown-item" href="#" @click.prevent="onAction('edit')">{{ trans('general.edit') }}</a>
                            <div class="dropdown-divider"></div>
                            {!! Form::deleteLink($config->widget, 'common/widgets') !!}
                        </div>
                    </div>
                </span>
            </div>
        </div>
    </div>
@else
    <span>
        <div class="dropdown card-action-button">
            <a class="btn btn-sm items-align-center py-2 mr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-v text-white"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                <a class="dropdown-item" href="#" @click.prevent="onAction('edit')">{{ trans('general.edit') }}</a>
                <div class="dropdown-divider"></div>
                {!! Form::deleteLink($config->widget, 'common/widgets') !!}
            </div>
        </div>
    </span>
@endif
