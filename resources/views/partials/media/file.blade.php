@php
$column_name = !empty($column_name) ? $column_name : 'attachment';
$options = !empty($options) ? $options : false;
@endphp

<ul class="mailbox-attachments clearfix margin-top">
    <li>
        @if ($file->aggregate_type != 'image')
        <span class="mailbox-attachment-icon">
            <i class="fa fa-file-{{ $file->aggregate_type }}-o"></i>
        </span>
        @else
        <span class="mailbox-attachment-icon has-img">
            <img src="{{ url('uploads/' . $file->id) }}" alt="{{ $file->basename }}">
        </span>
        @endif

        <div class="mailbox-attachment-info">
            <a href="javascript:void();" class="mailbox-attachment-name">
                <p class="mailbox-attachment-file-name">
                    <i class="fa fa-paperclip"></i> {{ $file->basename }}
                </p>
            </a>

            <span class="mailbox-attachment-size">
              {{ $file->readableSize() }}

                @permission('delete-common-uploads')
                {!! Form::open([
                'id' => $column_name. '-' . $file->id,
                'method' => 'DELETE',
                'url' => [url('uploads/' . $file->id)],
                'style' => 'display:inline'
            ]) !!}
                <a href="javascript:void();" id="remove-{{ $column_name }}" class="btn btn-danger btn-xs pull-right mailbox-attachment-remove">
                    <i class="fa fa fa-times"></i>
                </a>

                @if ($options)
                <input type="hidden" name="page" value="{{ $options['page'] }}" />
                <input type="hidden" name="key" value="{{ $options['key'] }}" />
                <input type="hidden" name="value" value="{{ $file->id }}" />
                @endif
                {!! Form::close() !!}
                @endpermission

                <a href="{{ url('uploads/' . $file->id . '/download') }}" class="btn btn-info btn-xs pull-right mailbox-attachment-download">
                    <i class="fa fa-cloud-download"></i>
                </a>
            </span>
        </div>
    </li>
</ul>
