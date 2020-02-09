@php
    if (\Str::contains($url, ['.'])) {
        $page = explode('.', $url)[0];

        $url = route($url, $item->$id);
    } else {
        $page = explode('/', $url)[1];

        $url = url($url, $item->$id);
    }

    $text = $text ? $text : $page;
@endphp

{!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> ' . trans('general.delete'), array(
    'type'    => 'button',
    'class'   => 'btn btn-danger btn-xs',
    'title'   => trans('general.delete'),
    '@click'  => 'confirmDelete("' . $url . '", "' . trans_choice('general.' . $text, 2) . '", "' . trans('general.delete_confirm', ['name' => '<strong>' . $item->$value . '</strong>', 'type' => mb_strtolower(trans_choice('general.' . $text, 1))]) . '", "' . trans('general.cancel') . '", "' . trans('general.delete') . '")'
)) !!}
