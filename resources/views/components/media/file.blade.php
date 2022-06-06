<div class="flex items-center justify-between border-b py-4">
    <div class="flex items-center">
        @if ($file->aggregate_type == 'image')
            <span class="material-icons text-base">image</span>
            @else
                <span class="material-icons text-base">attach_file</span>
        @endif  
        <div class="flex flex-col text-gray-500 ml-3 gap-y-1">
            <span class="w-64 text-sm truncate">{{ $file->basename }}</span>
            <span class="text-xs mb-0">{{ $file->readableSize() }}</span>
        </div>
    </div>  
    <div class="gap-x-1">
        @can('delete-common-uploads')
            <a href="javascript:void();" id="remove-{{ $column_name }}" @click="onDeleteFile('{{ $file->id }}', '{{ route('uploads.destroy', $file->id) }}', '{{ trans('general.title.delete', ['type' => $column_name]) }}', '{{ trans('general.delete_confirm', ['name' => $file->basename, 'type' => $column_name]) }} ', '{{ trans('general.cancel') }}', '{{ trans('general.delete') }}')" type="button" class="group">
                <span class="material-icons text-base text-red px-1.5 py-1 rounded-lg group-hover:bg-gray-100">delete</span>
            </a>    
            @if ($options)
                <input type="hidden" name="page_{{ $file->id}}" id="file-page-{{ $file->id}}" value="{{ $options['page'] }}" />
                <input type="hidden" name="key_{{ $file->id}}" id="file-key-{{ $file->id}}" value="{{ $options['key'] }}" />
                <input type="hidden" name="value_{{ $file->id}}" id="file-value-{{ $file->id}}" value="{{ $file->id }}" />
            @endif  
            <a href="{{ route('uploads.download', $file->id) }}" type="button" class="group">
                <span class="material-icons text-base text-purple px-1.5 py-1 rounded-lg group-hover:bg-gray-100">download</span>
            </a>
        @endcan
    </div>
</div>
