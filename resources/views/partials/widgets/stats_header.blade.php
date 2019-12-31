<span>
    <div class="dropdown card-action-button">
        <a class="btn btn-sm items-align-center py-2 mr-0 shadow-none--hover" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-ellipsis-v text-white"></i>
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