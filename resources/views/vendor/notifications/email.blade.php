@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level == 'error')
# {{ trans('notifications.whoops') }}
@else
# {{ trans('notifications.hello') }}
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
            $color = 'green';
            break;
        case 'error':
            $color = 'red';
            break;
        default:
            $color = 'blue';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

<!-- Salutation -->
@if (! empty($salutation))
{{ $salutation }}
@else
{!! trans('notifications.salutation', ['company_name' => setting('company.name', config('mail.from.name', config('app.name')))]) !!}
@endif

<!-- Subcopy -->
@isset($actionText)
@component('mail::subcopy')
{!! trans('notifications.subcopy', ['text' => $actionText, 'url' => $actionUrl]) !!}
@endcomponent
@endisset
@endcomponent
