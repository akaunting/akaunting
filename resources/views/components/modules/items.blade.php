<div class="py-6 font-medium">
    <div class="flex items-center justify-between mb-5 lg:mb-0">
        @if (! empty($title) && $title->isNotEmpty())
            {!! $title !!}
        @elseif ($attributes->has('title'))
            <h4 class="py-3 font-medium lg:text-2xl">
                {!! $attributes->get('title') !!}
            </h4>
        @endif

        @if ($attributes->has('route'))
            <div class="flex justify-center items-center group">
                <a href="{{ route($attributes->get('route')) }}">
                    <span class="border-b border-transparent transition-all group-hover:border-black">
                        {{ trans('modules.see_all_type', ['type' => $attributes->get('title')]) }}
                    </span>
                </a>

                <i class="material-icons text-sm ltr:ml-1 rtl:mr-1 transform transition-all group-hover:translate-x-1">arrow_forward</i>
            </div>
        @endif
    </div>

    @if ($modules)
        <div class="grid sm:grid-cols-4 grid-rows-flow gap-8" data-apps-content>
            @foreach ($modules as $item)
                <x-modules.item :model="$item" />
            @endforeach
        </div>

        @if ($seeMore)
            <div class="flex items-center mt-10">
                <button type="button" id="button-pre-load" @click="onloadMore"
                    :disabled="loadMoreLoading"
                    class="w-48 m-auto relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100 js-learn-more js-button-modal-submit"
                >
                    <i v-if="loadMoreLoading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i>
                    <span :class="[{'opacity-0': loadMoreLoading}]">
                        {{ trans('modules.see_more') }}
                    </span>
                </button>

                <x-form.input.hidden name="see_more_path" value="{{ route('apps.load-more', ['type' => $type]) }}" />
                <x-form.input.hidden name="see_more_alias" value="{{ request()->alias }}" />
            </div>
        @endif
    @else
        <x-modules.no-apps />
    @endif
</div>
