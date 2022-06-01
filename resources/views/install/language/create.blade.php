<x-layouts.install>
    <x-slot name="title">
        {{ trans('install.steps.language') }}
    </x-slot>

    <x-slot name="content">
        <div class="form-group mb-0">
            <select name="lang" id="lang" size="14" class="w-full form-control-label">
                @foreach (language()->allowed() as $code => $name)
                <option value="{{ $code }}" @if ($code=='en-GB' ) {{ 'selected="selected"' }} @endif>{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </x-slot>
</x-layouts.install>
