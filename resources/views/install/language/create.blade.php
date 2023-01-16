<x-layouts.install>
    <x-slot name="title">
        {{ trans('install.steps.language') }}
    </x-slot>

    <x-slot name="content">
        <div class="mb-0">
            <select name="lang" id="lang" size="14" class="w-full text-black text-sm font-medium">
                @foreach (language()->allowed() as $code => $name)
                <option value="{{ $code }}" @if ($code=='en-GB' ) {{ 'selected="selected"' }} @endif>{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </x-slot>
</x-layouts.install>
