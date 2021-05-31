<div id="releases-items">
    @if (!empty($releases))
        @foreach($releases->data as $release)
            <p>{{ $release->version }} - @date($release->released_at)</p>

            {!! $release->changelog !!}
        @endforeach
    @endif
</div>