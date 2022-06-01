@if (! empty($modules) && ! empty($modules[0]))
    @foreach ($modules as $item)
        {!! $item !!}
    @endforeach
@else
    <div class="w-full lg:h-48 px-6 bg-dark-blue rounded-lg flex flex-col lg:flex-row justify-between items-center my-8">
        <div class="w-full lg:w-1/2 flex flex-col self-end py-6">
            <h1 class="text-lg lg:text-7xl font-semibold text-white">
                {{ trans('modules.premium_banner') }}
            </h1>

            <a href="https://akaunting.com/plans" class="text-white transition-all hover:underline">
                {{ trans('modules.learn_more') }}
            </a>
        </div>

        <div class="hidden lg:block">
            <img src="{{ asset('/public/img/akaunting-logo-gold.png') }}" class="h-40" alt="Akaunting" />
        </div>
    </div>
@endif
