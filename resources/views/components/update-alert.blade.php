@foreach ($alerts as $type => $messages)
    @foreach ($messages as $message)
        <x-alert :type="$type" :message="$message" />
    @endforeach
@endforeach
