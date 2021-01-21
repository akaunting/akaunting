@if ($attachment)
    <div class="row align-items-center mb-4">
        <div class="col-xs-12 col-sm-4">
            @php 
                $file = $attachment;
            @endphp

            @include('partials.media.file')
        </div>
    </div>
@endif
