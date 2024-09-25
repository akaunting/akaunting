@props(['id', 'name', 'href', 'active', 'type', 'tab'])

@if (empty($href))
    <x-tabs.nav :id="$id" :active="$active ?? false">
        <div class="flex items-center">
            {{ $name }}

            <livewire:tab.pin :type="$type" :id="$id" :tab="$tab" :pinned="$active ?? false" />
        </div>
    </x-tabs.nav>
@else
    <x-tabs.nav-link :id="$id" :href="$href" :active="$active ?? false">
        <div class="flex items-center">
            <a href="{{ $href }}">
                {{ $name }}
            </a>

            <livewire:tab.pin :type="$type" :id="$id" :tab="$tab" :pinned="$active ?? false" />
        </div>
    </x-tabs.nav-link>
@endif
