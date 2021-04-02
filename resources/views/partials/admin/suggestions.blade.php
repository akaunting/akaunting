
@foreach($modules as $module)
    <a href="{{ url($module->action_url) . '?' . http_build_query((array) $module->action_parameters) }}" class="btn btn-white btn-sm" target="{{ $module->action_target }}">
        {{ $module->name }}
    </a>
@endforeach
