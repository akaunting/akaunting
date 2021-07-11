@if ($attachment)
    <div class="row align-items-center">
        @foreach ($attachment as $file)
            <div class="col-xs-12 col-sm-4 mb-4">
                @include('partials.media.file')
            </div>
        @endforeach
    </div>
@endif
