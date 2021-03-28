@php
    $column_name = !empty($column_name) ? $column_name : 'attachment';
    $options = !empty($options) ? $options : false;
@endphp
<div class="card mb-0">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-auto">
                @if ($file->aggregate_type != 'image')
                    <i class="fas fa-file-image display-3"></i>
                @else
                    <a href="#" class="avatar">
                        <img src="{{ route('uploads.get', $file->id) }}" alt="{{ $file->basename }}">
                    </a>
                @endif
            </div>

            <div class="col ml--2 long-texts">
                <h4 class="mb-0">
                    <span><i class="fas fa-paperclip"></i></span>
                    <span>{{ $file->basename }}</span>
                </h4>

                <small>{{ $file->readableSize() }}</small>
            </div>

            <div class="col-auto">
                @can('delete-common-uploads')
                    <a href="javascript:void();" id="remove-{{ $column_name }}" @click="onDeleteFile('{{ $file->id }}', '{{ route('uploads.destroy', $file->id) }}', '{{ trans('general.title.delete', ['type' => $column_name]) }}', '{{ trans('general.delete_confirm', ['name' => $file->basename, 'type' => $column_name]) }} ', '{{ trans('general.cancel') }}', '{{ trans('general.delete') }}')" type="button" class="btn btn-sm btn-danger text-white">
                        <i class="fas fa-times"></i>
                    </a>

                    @if ($options)
                        <input type="hidden" name="page_{{ $file->id}}" id="file-page-{{ $file->id}}" value="{{ $options['page'] }}" />
                        <input type="hidden" name="key_{{ $file->id}}" id="file-key-{{ $file->id}}" value="{{ $options['key'] }}" />
                        <input type="hidden" name="value_{{ $file->id}}" id="file-value-{{ $file->id}}" value="{{ $file->id }}" />
                    @endif
                @endcan

                <a href="{{ route('uploads.download', $file->id) }}" type="button" class="btn btn-sm btn-info text-white">
                    <i class="fas fa-file-download"></i>
                </a>
            </div>
        </div>
    </div>
</div>
