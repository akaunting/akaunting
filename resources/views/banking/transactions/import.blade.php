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
        <div class="pt-6">
            <div class="relative -m-2 flex items-center space-x-4 rounded-xl p-4 focus-within:ring-2 focus-within:ring-gray-500 hover:bg-gray-100">
                <div>
                    <h2 class="text-base font-semibold text-gray-900">
                        <a href="{{ module_is_enabled('bank-feeds') ? route('bank-feeds.bank-connections.create') : route('apps.app.show', 'bank-feeds') }}" class="focus:outline-none">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            <span>{{ trans_choice('general.bank_feeds', 2) }}</span>
                            <span aria-hidden="true"> &rarr;</span>
                        </a>
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ trans('import.bank_feeds') }}
                    </p>
                </div>
            </div>

            <ul role="list" class="mt-6 grid grid-cols-1 gap-x-8 gap-y-16 border-b border-t border-gray-200 py-6 sm:grid-cols-3">
                <li class="flow-root">
                    <div class="relative -m-2 flex items-center space-x-4 rounded-xl p-4 focus-within:ring-2 focus-within:ring-gray-500 hover:bg-gray-100">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">
                                <a href="{{ module_is_enabled('receipt') ? route('receipt.receipts.create') : route('apps.app.show', 'receipt') }}" class="focus:outline-none">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    <span>{{ trans_choice('general.receipts', 2) }}</span>
                                    <span aria-hidden="true"> &rarr;</span>
                                </a>
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                {{ trans('import.receipts') }}
                            </p>
                        </div>
                    </div>
                </li>

                <li class="flow-root">
                    <div class="relative -m-2 flex items-center space-x-4 rounded-xl p-4 focus-within:ring-2 focus-within:ring-gray-500 hover:bg-gray-100">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">
                                <a href="{{ module_is_enabled('ofx') ? route('ofx.ofx.create') : route('apps.app.show', 'ofx') }}" class="focus:outline-none">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    <span>{{ trans_choice('general.ofx', 2) }}</span>
                                    <span aria-hidden="true"> &rarr;</span>
                                </a>
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                {{ trans('import.ofx') }}
                            </p>
                        </div>
                    </div>
                </li>

                <li class="flow-root">
                    <div class="relative -m-2 flex items-center space-x-4 rounded-xl p-4 focus-within:ring-2 focus-within:ring-gray-500 hover:bg-gray-100">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">
                                <a href="{{ module_is_enabled('mt940') ? route('mt940.create') : route('apps.app.show', 'mt940') }}" class="focus:outline-none">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    <span>{{ trans_choice('general.mt940', 2) }}</span>
                                    <span aria-hidden="true"> &rarr;</span>
                                </a>
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                {{ trans('import.mt940') }}
                            </p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

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

