@canany(['update-common-widgets', 'delete-common-widgets'])
<div class="card-header{{ !empty($header_class) ? ' ' . $header_class : '' }}">
    <div class="row align-items-center">

        <div class="col-10 text-nowrap">
            <h4 class="mb-0 long-texts" title="{{ $class->model->name }}">{{ $class->model->name }}</h4>
        </div>

        <div class="col-2">
            <span class="float-right">
                <div class="dropdown">
                    <a class="btn btn-sm items-align-center py-2 mr-0 shadow-none--hover" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-v text-muted"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                        @can('update-common-widgets')
                        {!! Form::button(trans('general.edit'), [
                            'type'    => 'button',
                            'class'   => 'dropdown-item',
                            'title'   => trans('general.edit'),
                            '@click'  => 'onEditWidget(' . $class->model->id . ')'
                        ]) !!}
                        @endcan
                        @can('delete-common-widgets')
                        <div class="dropdown-divider"></div>
                        {!! Form::deleteLink($class->model, 'widgets.destroy') !!}
                        @endcan
                    </div>
                </div>
            </span>
        </div>
    </div>
</div>
@endcanany
