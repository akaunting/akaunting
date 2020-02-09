@php
    if (\Str::contains($url, ['.'])) {
        $page = explode('.', $url)[0];

        $url = route($url, $item->$id);
    } else {
        $page = explode('/', $url)[1];

        $url = url($url, $item->$id);
    }

    $text = $text ? $text : $page;

    $name = addslashes($item->$value);
@endphp

{!! Form::button(trans('general.delete'), array(
    'type'    => 'button',
    'class'   => 'dropdown-item action-delete',
    'title'   => trans('general.delete'),
    '@click'  => 'confirmDelete("' . $url . '", "' . trans_choice('general.' . $text, 2) . '", "' . trans('general.delete_confirm', ['name' => '<strong>' . $name . '</strong>', 'type' => mb_strtolower(trans_choice('general.' . $text, 1))]) . '", "' . trans('general.cancel') . '", "' . trans('general.delete') . '")'
)) !!}
