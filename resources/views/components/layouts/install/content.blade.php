@stack('content_start')
<div id="app">
    @stack('content_content_start')
    <x-form id="form-install" :url="url()->current()">

        <div class="card-body">
            <div class="text-center text-muted mt-2 mb-4">
                <small>
                    {!! $attributes->get('title') !!}
                </small>
            </div>

            @include('flash::message')

            {!! $slot !!}
        </div>

        <div class="card-footer">
            <div class="float-right">
                @if (Request::is('install/requirements'))
                    <x-link href="{{ route('install.requirements') }}" class="btn btn-success" override="class">
                        {{ trans('install.refresh') }}
                    </x-link>
                @else
                    <x-button
                        type="submit"
                        id="next-button"
                        ::disabled="loading"
                        class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100 sm:col-span-6"
                        override="class"
                        data-loading-text="{{ trans('general.loading') }}"
                    >
                        <i v-if="loading" class="submit-spin absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto"></i> 
                        <span :class="[{'opacity-0': loading}]">
                            {{ trans('install.next') }}
                        </span>
                    </x-button>
                @endif
            </div>
        </div>
    </x-form>
    @stack('content_content_end')

    <notifications></notifications>

    <form id="form-dynamic-component" method="POST" action="#"></form>

    <component v-bind:is="component"></component>
</div>
@stack('content_end')