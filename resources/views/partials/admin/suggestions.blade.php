
@foreach($modules as $module)
    <span>
        <a href="{{ url($module->action_url) . '?' . http_build_query((array) $module->action_parameters) }}" class="btn btn-white btn-sm header-button-bottom" target="{{ $module->action_target }}"><span class="fa fa-rocket"></span> &nbsp;{{ $module->name }}</a>
    </span>
@endforeach
