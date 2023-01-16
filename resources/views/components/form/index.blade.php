<form
    method="POST"
    action="{{ $action }}"
    @if ($class)
    class="{{ $class }}"
    @endif
    @if ($role)
    role="form"
    @endif
    @if ($novalidate)
    novalidate="{{ $novalidate }}"
    @endif
    @if ($enctype)
    enctype="{{ $enctype }}"
    @endif
    @if ($acceptCharset)
    accept-charset="{{ $acceptCharset }}"
    @endif
    @submit.prevent="{{ $submit }}"
    @keydown="form.errors ? form.errors.clear($event.target.name): null"
    {{ $attributes }}
>
    @csrf
    @method($method)
    {{ $slot }}
</form>
