<x-layouts.admin>
    <x-slot name="title">
        {{ trans('import.title', ['type' => $title_type]) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans('import.title', ['type' => $title_type]) }}"
        icon="import_export"
        url="{{ route('import.create', ['group' => $group, 'type' => $type]) }}"
    ></x-slot>

    <x-slot name="content">
        <div class="card">
            <x-form id="import" :route="$form_params['route']" :url="$form_params['url']">
                <div class="flex flex-col lg:flex-row">
                    <div class="hidden lg:flex w-4/12 ltr:-ml-10 rtl:-mr-10 ltr:mr-10 rtl:ml-10">
                        <img src="{{ asset('public/img/import.png') }}" alt="{{ trans('import.title', ['type' => $title_type]) }}">
                    </div>

                    <!-- 
                    <div class="hidden lg:flex w-4/12 mt-18 mr-14">
                        <iframe width="560" height="244" src="https://www.youtube.com/embed/p98z142g2yY" frameborder="0"  title="{{ trans('import.title', ['type' => $title_type]) }}" class="rounded-lg"></iframe>
                    </div>
                     -->

                    <div class="card-body mt-8 lg:w-8/12 w-full">
                        <div class="w-full mt-8 bg-blue-100 rounded-lg text-blue-700 px-4 py-2" role="alert">
                            <div class="flex">
                                <span class="material-icons ltr:mr-3 rtl:ml-3">error_outline</span>

                                <div class="font-semibold text-sm mt-1">
                                    {!! trans('import.sample_file_and_document', [
                                        'download_link' => $sample_file,
                                        'document_link' => $document_link
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <x-form.group.import
                            name="import"
                            dropzone-class="form-file"
                            singleWidthClasses
                            :options="['acceptedFiles' => '.xls,.xlsx']"
                            form-group-class="mt-8"
                        />
                    </div>
                </div>

                <div class="mt-8">
                    <div class="sm:col-span-6 flex items-center justify-end">
                        @if (! empty($route))
                            <x-link href="{{ route(\Str::replaceFirst('.import', '.index', $route)) }}" class="px-6 py-1.5 mr-2 hover:bg-gray-200 rounded-lg" override="class">
                                {{ trans('general.cancel') }}
                            </x-link>
                        @else
                            <x-link href="{{ url($path) }}" class="px-6 py-1.5 hover:bg-gray-200 rounded-lg ltr:mr-2 rtl:ml-2" override="class">
                                {{ trans('general.cancel') }}
                            </x-link>
                        @endif

                        <x-button
                            type="submit"
                            class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100"
                            ::disabled="form.loading"
                            override="class"
                        >
                            <x-button.loading>
                                {{ trans('import.import') }}
                            </x-button.loading>
                        </x-button>
                    </div>
                </div>
            </x-form>
        </div>
    </x-slot>

    <x-script folder="common" file="imports" />
</x-layouts.admin>

