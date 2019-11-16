@php
    $page = explode('/', $url)[1];
    $text = $text ? $text : $page;
@endphp

{!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> ' . trans('general.delete'), array(
    'type'    => 'button',
    'class'   => 'btn btn-danger btn-xs',
    'title'   => trans('general.delete'),
    '@click'  => 'confirmDelete("' . url($url, $item->$id) . '", "' . trans_choice('general.' . $text, 2) . '", "' . trans('general.delete_confirm', ['name' => '<strong>' . $item->$value . '</strong>', 'type' => mb_strtolower(trans_choice('general.' . $text, 1))]) . '", "' . trans('general.cancel') . '", "' . trans('general.delete') . '")'
)) !!}

@push('content_content_end')
    <akaunting-modal
        :show="confirm.show"
        :title="confirm.title"
        :message="confirm.message"
        :button_cancel="confirm.button_cancel"
        :button_delete="confirm.button_delete"
        v-if='confirm.show'
        @confirm='onDelete'
        @cancel="cancelDelete">
    </akaunting-modal>
@endpush

