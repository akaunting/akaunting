@if (! empty($modules) && ! empty($modules[0]))
    @foreach ($modules as $item)
        {!! $item !!}
    @endforeach
@else
    <div class="w-full lg:h-48 px-6 bg-dark-blue rounded-lg flex flex-col lg:flex-row justify-between items-center my-8">
        <div class="w-full lg:w-1/2 flex flex-col self-end py-6">
            <div
                class="lg:h-28"
                x-data="{
                    text: '',
                    textArray : ['{{ trans('modules.premium_banner') }}'],
                    textIndex: 0,
                    charIndex: 0,
                    typeSpeed: 100,
                 }"
                x-init="setInterval(function(){
                    let current = $data.textArray[ $data.textIndex ];
                    $data.text = current.substring(0, $data.charIndex);
                    $data.charIndex += 1;
                 }, $data.typeSpeed);"
            >
                <h1 class="text-lg lg:text-7xl font-semibold text-white" x-text="text"></h1>
            </div>

            <x-link href="https://akaunting.com/plans" class="text-white transition-all hover:underline" override="class">
                {{ trans('modules.learn_more') }}
            </x-link>
        </div>

        <div class="hidden lg:block">
            <img src="{{ asset('/public/img/akaunting-logo-gold.png') }}" class="h-40" alt="Akaunting" />
        </div>
    </div>
</div>
@endif
