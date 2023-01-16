<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.updates', 2) }}
    </x-slot>

    <x-slot name="buttons">
        <x-link href="{{ route('updates.check') }}" kind="primary">
            {{ trans('updates.check') }}
        </x-link>
    </x-slot>

    <x-slot name="content">
        <div class="mt-10">
            <div class="flex items-center">
                <div class="relative px-4 text-sm text-center pb-2 text-purple font-medium border-purple transition-all after:absolute after:w-full after:h-0.5 after:left-0 after:right-0 after:bottom-0 after:bg-purple after:rounded-tl-md after:rounded-tr-md">
                    <span>{{ $name }}</span>
                </div>
            </div>

            <div class="my-2">
                <el-progress :text-inside="true" :stroke-width="24" :percentage="update.total" :status="update.status"></el-progress>

                <div id="progress-text" class="mt-3" v-html="update.html"></div>

                <x-form.input.hidden name="page" value="update" />
                <x-form.input.hidden name="name" :value="$name" />
                <x-form.input.hidden name="version" :value="$version" />
                <x-form.input.hidden name="alias" :value="$alias" />
                <x-form.input.hidden name="installed" :value="$installed" />
            </div>
        </div>
    </x-slot>

    <x-script folder="install" file="update" />
</x-layouts.admin>
