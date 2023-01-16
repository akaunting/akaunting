@props(['module'])

<div class="flex flex-col gap-20">
    @foreach ($module->call_to_actions as $cta)
        <div @class([
                'flex flex-col',
                'lg:flex-row-reverse' => $cta->position == 'left',
                'lg:flex-row' => $cta->position != 'left',
                'justify-between gap-12'
            ])
        >
            <div class="w-full lg:w-1/2 flex flex-col gap-6 justify-center">
                <h2 class="font-bold text-3xl">
                    {!! $cta->title !!}
                </h2>

                <div class="flex flex-col gap-4 divide-y">
                    <div class="font-thin text-sm leading-6 tracking-widest">
                        @markdown($cta->description)
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-1/2">
                <img src="{{ $cta->thumb->path_string }}" alt="{{ $cta->thumb->alt_attribute }}" />
            </div>
        </div>
    @endforeach
</div>
