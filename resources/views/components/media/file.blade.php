<div class="divide-y">
    <div class="flex items-center justify-between py-4 -mb-4">
        <div class="flex items-center">
            @if ($file->aggregate_type == 'image')
                <div class="avatar-attachment">
                    <img src="{{ route('uploads.get', $file->id) }}" alt="{{ $file->basename }}" class="avatar-img h-full rounded object-cover">
                </div>
            @else
                <div class="avatar-attachment">
                    <span class="material-icons text-base">attach_file</span>
                </div>
            @endif  

            <div class="flex flex-col text-gray-500 ltr:ml-3 rtl:mr-3 gap-y-1">
                <span class="w-64 text-sm truncate">
                    {{ $file->basename }}
                </span>

                <span class="text-xs mb-0">
                    {{ ! is_int($file->size) ? '0 B' : $file->readableSize() }}
                </span>
            </div>
        </div>  

        <div class="flex flex-row lg:flex-col gap-x-1">
            @can('delete-common-uploads')
                <x-link href="javascript:void();" id="remove-{{ $column_name }}" @click="onDeleteFile('{{ $file->id }}', '{{ route('uploads.destroy', $file->id) }}', '{{ trans('general.title.delete', ['type' => $column_name]) }}', '{{ trans('general.delete_confirm', ['name' => $file->basename, 'type' => $column_name]) }} ', '{{ trans('general.cancel') }}', '{{ trans('general.delete') }}')" type="button" class="group" override="class">
                    <span class="material-icons-outlined text-base text-gray-300 px-1.5 py-1 rounded-lg group-hover:bg-gray-100">delete</span>
                </x-link>

                @if ($options)
                    <input type="hidden" name="page_{{ $file->id}}" id="file-page-{{ $file->id}}" value="{{ $options['page'] }}" />
                    <input type="hidden" name="key_{{ $file->id}}" id="file-key-{{ $file->id}}" value="{{ $options['key'] }}" />
                    <input type="hidden" name="value_{{ $file->id}}" id="file-value-{{ $file->id}}" value="{{ $file->id }}" />
                @endif  
            @endcan

            <x-link href="{{ route('uploads.download', $file->id) }}" type="button" class="group" override="class">
                <span class="material-icons text-base text-gray-300 px-1.5 py-1 rounded-lg group-hover:bg-gray-100">download</span>
            </x-link>
        </div>
    </div>
</div>
