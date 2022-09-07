<x-layouts.error>
    <x-slot name="title">
        {{ trans('errors.title.403') }}
    </x-slot>

    <x-slot name="content">
        <div class="h-full flex flex-col sm:flex-row items-center justify-center sm:justify-between xl:-ml-64">
            <div class="flex flex-col items-start gap-y-4 mb-10 sm:mb-0 sm:-mt-24">
                <h1 class="font-medium text-5xl lg:text-8xl">
                    {{ trans('errors.header.403') }}
                </h1>

                <span class="text-lg">
                    {{ trans('errors.title.403') }}
                </span>

                @if (! empty($message))
                <span class="text-lg">
                    {{ $message }}
                </span>
                @endif

                @php $landing_page = user() ? user()->getLandingPageOfUser() : route('login'); @endphp
                <x-link
                    href="{{ $landing_page }}"
                    class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100 mt-3"
                    override="class"
                >
                    {{ trans('general.go_to_dashboard') }}
                </x-link>
            </div>

            <img src="{{ asset('public/img/errors/403.png') }}" alt="403" />
        </div>
    </x-slot>
</x-layouts.error>
