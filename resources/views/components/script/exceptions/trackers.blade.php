@if (in_array($channel, ['bugsnag', 'sentry']))
    <script type="text/javascript"><!--
        var exception_tracker = {
            channel: '{{ $channel }}',
            action: '{{ $action }}',
            user: {
                id: '{{ user_id() }}',
                name: '{{ user()?->name }}',
                email: '{{ user()?->email }}',
            },
            ip: '{{ $ip }}',
            tags: {!! json_encode($tags) !!},
            params: {!! json_encode($params) !!},
        };
    //--></script>
@endif
