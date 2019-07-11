@extends('layouts.admin')

@section('title', trans_choice('general.updates', 2))

@section('new_button')
<span class="new-button"><a href="{{ url('install/updates/check') }}" class="btn btn-warning btn-sm"><span class="fa fa-history"></span> &nbsp;{{ trans('updates.check') }}</a></span>
@endsection

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header with-border">
        <i class="fa fa-gear"></i>
        <h3 class="box-title">{{ $name }}</h3>
    </div>
    <!-- /.box-header -->

    <div class="box-body">
        <p>
            <div class="progress">
                <div id="progress-bar" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span class="sr-only">{{ trans('modules.installation.start', ['module' => $name]) }}</span>
                </div>
            </div>
            <div id="progress-text"></div>
        </p>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->
@endsection

@push('scripts')
<script type="text/javascript">
    var step = new Array();
    var total = 0;
    var path = '';

    $(document).ready(function() {
        $.ajax({
            url: '{{ url("install/updates/steps") }}',
            type: 'post',
            dataType: 'json',
            data: {name: '{{ $name }}', version: '{{ $version }}'},
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(json) {
                if (json['errorr']) {
                    $('#progress-bar').addClass('progress-bar-danger');
                    $('#progress-text').html('<div class="text-danger">' + json['error'] + '</div>');
                }

                if (json['step']) {
                    step = json['step'];
                    total = step.length;

                    next();
                }
            }
        });
    });

    function next() {
        data = step.shift();

        if (data) {
            $('#progress-bar').css('width', (100 - (step.length / total) * 100) + '%');

            $.each($('#progress-text .text-default'), function( index, value ) {
                // Remove Loading font
                $(this).find('.update-spin').remove();
                // Remove Check font
                $(this).find('.update-check').remove();
                // Add Check font
                $(this).append(' <i class="fa fa-check update-check text-success"></i>');
            });

            $('#progress-text').append('<span class="text-default"><i class="fa fa-spinner fa-spin update-spin"></i> ' + data['text'] + '</span> </br>');

            setTimeout(function() {
                $.ajax({
                    url: data.url,
                    type: 'post',
                    dataType: 'json',
                    data: {path: path, alias: '{{ $alias }}', installed: '{{ $installed }}', version: '{{ $version }}'},
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    success: function(json) {
                        if (json['errors']) {
                            $('#progress-bar').addClass('progress-bar-danger');
                            $('#progress-text').append('<div class="text-danger"><i class="fa fa-times update-error"></i> ' + json['errors'] + '</div>');
                        }

                        if (json['success']) {
                            $('#progress-bar').removeClass('progress-bar-danger');
                            $('#progress-bar').addClass('progress-bar-success');
                        }

                        if (json['data']['path']) {
                            path = json['data']['path'];
                        }

                        if (!json['errors'] && !json['redirect']) {
                            next();
                        }

                        if (json['redirect']) {
                            window.location = json['redirect'];
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }, 800);
        }
    }
</script>
@endpush
