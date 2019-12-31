<div class="card-header border-bottom-0">
    <div class="row align-items-center">

        <div class="col-6 text-nowrap">
            <h4 class="mb-0">{{ $model->name }}</h4>
        </div>

        <div class="col-6 hidden-sm">
            <span class="float-right">
                <div class="dropdown">
                    <a class="btn btn-sm items-align-center py-2 mr-0 shadow-none--hover" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-v text-muted"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                        {!! Form::button(trans('general.edit'), [
                            'type'    => 'button',
                            'class'   => 'dropdown-item',
                            'title'   => trans('general.edit'),
                            '@click'  => 'onEditWidget(' . $model->id . ')'
                        ]) !!}
                        <div class="dropdown-divider"></div>
                        {!! Form::deleteLink($model, 'common/widgets') !!}
                    </div>
                </div>
            </span>
        </div>
    </div>
</div>