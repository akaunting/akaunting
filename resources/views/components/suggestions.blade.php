@foreach($suggestions as $suggestion)
    <x-link
        href="{{ url($suggestion->action_url) . '?' . http_build_query((array) $suggestion->action_parameters) }}"
        id="suggestion-{{ $suggestion->alias }}-{{ str_replace('.', '-', request()->route()->getName()) }}"
        class="px-3 py-1.5 mb-3 sm:mb-0 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm font-medium leading-6"
        target="{{ $suggestion->action_target }}"
        override="class"
    >
        {{ $suggestion->name }}
    </x-link>
@endforeach