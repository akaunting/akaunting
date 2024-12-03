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
        <div class="py-6">
            <div class="w-full">
                <div class="w-full lg:mx-0">
                    <h2 class="text-pretty text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">
                        {{ trans_choice('general.bank_feeds', 2) }}
                    </h2>

                    <p class="mt-2 text-lg/8 text-gray-600">
                        {{ trans('import.bank_feeds') }}
                    </p>
                </div>

                <div class="w-full mt-10 grid grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                    <article class="flex max-w-xl flex-col items-start justify-between">
                        <div class="group relative">
                            <h3 class="mt-3 text-lg/6 font-semibold text-gray-900 group-hover:text-gray-600">
                                <a href="#">
                                    <span class="absolute inset-0"></span>
                                    {{ trans_choice('general.receipts', 2) }}
                                </a>
                            </h3>

                            <p class="mt-5 line-clamp-3 text-sm/6 text-gray-600">
                                {{ trans('import.receipts') }}
                            </p>
                        </div>
                    </article>

                    <article class="flex max-w-xl flex-col items-start justify-between">
                        <div class="group relative">
                            <h3 class="mt-3 text-lg/6 font-semibold text-gray-900 group-hover:text-gray-600">
                                <a href="#">
                                    <span class="absolute inset-0"></span>
                                    {{ trans_choice('general.ofx', 2) }}
                                </a>
                            </h3>

                            <p class="mt-5 line-clamp-3 text-sm/6 text-gray-600">
                                {{ trans('import.ofx') }}
                            </p>
                        </div>
                    </article>

                    <article class="flex max-w-xl flex-col items-start justify-between">
                        <div class="group relative">
                            <h3 class="mt-3 text-lg/6 font-semibold text-gray-900 group-hover:text-gray-600">
                                <a href="#">
                                    <span class="absolute inset-0"></span>
                                    {{ trans_choice('general.mt940', 2) }}
                                </a>
                            </h3>

                            <p class="mt-5 line-clamp-3 text-sm/6 text-gray-600">
                                {{ trans('import.mt940') }}
                            </p>
                        </div>
                    </article>
                </div>
            </div>
        </div>
          
        <div class="card border-t border-gray-200">
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

        <x-tabs active="" class="w-full mt-8 hidden">
            <x-slot name="navs">
                @stack('bank_feeds_nav_start')

                <li 
                    id="tab-bank-feeds"
                    data-id="tab-bank-feeds"
                    data-tabs="bank-feeds"
                    x-on:click="active = 'bank-feeds'"
                    x-bind:class="active != 'documents' ? 'text-black' : 'active-tabs text-purple border-purple transition-all after:absolute after:w-full after:h-0.5 after:left-0 after:right-0 after:bottom-0 after:bg-purple after:rounded-tl-md after:rounded-tr-md'"
                    class="relative flex-auto px-4 text-sm text-center pb-2 cursor-pointer transition-all border-b whitespace-nowrap tabs-link text-black"
                >
                    <p class="text-sm/6 font-semibold text-gray-900">
                        {{ trans_choice('general.bank_feeds', 2) }}
                    </p>

                    <p class="mt-1 truncate text-xs/5 text-gray-500 text-wrap">
                        {{ trans('import.bank_feeds') }}
                    </p>
                </li>

                @stack('receipts_nav_start')

                <li 
                    id="tab-receipts"
                    data-id="tab-receipts"
                    data-tabs="receipts"
                    x-on:click="active = 'receipts'"
                    x-bind:class="active != 'documents' ? 'text-black' : 'active-tabs text-purple border-purple transition-all after:absolute after:w-full after:h-0.5 after:left-0 after:right-0 after:bottom-0 after:bg-purple after:rounded-tl-md after:rounded-tr-md'"
                    class="relative flex-auto px-4 text-sm text-center pb-2 cursor-pointer transition-all border-b whitespace-nowrap tabs-link text-black"
                >
                    <p class="text-sm/6 font-semibold text-gray-900">
                        {{ trans_choice('general.receipts', 2) }}
                    </p>

                    <p class="mt-1 truncate text-xs/5 text-gray-500 text-wrap">
                        {{ trans('import.receipts') }}
                    </p>
                </li>

                @stack('ofx_nav_start')

                <li 
                    id="tab-ofx"
                    data-id="tab-ofx"
                    data-tabs="ofx"
                    x-on:click="active = 'ofx'"
                    x-bind:class="active != 'documents' ? 'text-black' : 'active-tabs text-purple border-purple transition-all after:absolute after:w-full after:h-0.5 after:left-0 after:right-0 after:bottom-0 after:bg-purple after:rounded-tl-md after:rounded-tr-md'"
                    class="relative flex-auto px-4 text-sm text-center pb-2 cursor-pointer transition-all border-b whitespace-nowrap tabs-link text-black"
                >
                    <p class="text-sm/6 font-semibold text-gray-900">
                        {{ trans_choice('general.ofx', 2) }}
                    </p>

                    <p class="mt-1 truncate text-xs/5 text-gray-500 text-wrap">
                        {{ trans('import.ofx') }}
                    </p>
                </li>

                @stack('mt940_nav_start')

                <li 
                    id="tab-mt940"
                    data-id="tab-mt940"
                    data-tabs="mt940"
                    x-on:click="active = 'mt940'"
                    x-bind:class="active != 'documents' ? 'text-black' : 'active-tabs text-purple border-purple transition-all after:absolute after:w-full after:h-0.5 after:left-0 after:right-0 after:bottom-0 after:bg-purple after:rounded-tl-md after:rounded-tr-md'"
                    class="relative flex-auto px-4 text-sm text-center pb-2 cursor-pointer transition-all border-b whitespace-nowrap tabs-link text-black"
                >
                    <p class="text-sm/6 font-semibold text-gray-900">
                        {{ trans_choice('general.mt940', 2) }}
                    </p>

                    <p class="mt-1 truncate text-xs/5 text-gray-500 text-wrap">
                        {{ trans('import.mt940') }}
                    </p>
                </li>

                @stack('core_nav_start')

                <x-tabs.nav
                    id="transactions"
                    name="{{ trans_choice('general.transactions', 2) }}"
                    active
                />

                @stack('transactions_nav_end')
            </x-slot>

            <x-slot name="content">
                @stack('documents_tab_start')

                <x-tabs.tab id="documents">
                </x-tabs.tab>

                @stack('transactions_tab_start')

                <x-tabs.tab id="transactions">
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
                </x-tabs.tab>

                @stack('transactions_tab_end')
            </x-slot>
        </x-tabs>
    </x-slot>

    <x-script folder="common" file="imports" />
</x-layouts.admin>

