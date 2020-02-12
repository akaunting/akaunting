@php
    $column_name = !empty($column_name) ? $column_name : 'attachment';
    $options = !empty($options) ? $options : false;
@endphp
<div class="card mb-0">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-auto">
                @if ($file->aggregate_type != 'image')
                    <i class="fas fa-file-image display-3"></i>
                @else
                    <a href="#" class="avatar">
                        <img src="{{ url('uploads/' . $file->id) }}" alt="{{ $file->basename }}">
                    </a>
                @endif
            </div>

            <div class="col ml--2 long-texts">
                <h4 class="mb-0">
                    <span><i class="fas fa-paperclip"></i></span>
                    <span>{{ $file->basename }}</span>
                </h4>

                <small>{{ $file->readableSize() }}</small>
            </div>

            <div class="col-auto">
                @permission('delete-common-uploads')
                    {!! Form::open([
                        'id' => $column_name. '-' . $file->id,
                        'method' => 'DELETE',
                        'url' => [url('uploads/' . $file->id)],
                        'class' => 'd-inline'
                    ]) !!}

                        <a href="javascript:void();" id="remove-{{ $column_name }}" type="button" class="btn btn-sm btn-danger text-white header-button-top">
                            <i class="fas fa-times"></i>
                        </a>

                        @if ($options)
                            <input type="hidden" name="page" value="{{ $options['page'] }}" />
                            <input type="hidden" name="key" value="{{ $options['key'] }}" />
                            <input type="hidden" name="value" value="{{ $file->id }}" />
                        @endif
                    {!! Form::close() !!}
                @endpermission

                <a href="{{ url('uploads/' . $file->id . '/download') }}" type="button" class="btn btn-sm btn-info text-white header-button-top">
                    <i class="fas fa-file-download"></i>
                </a>
            </div>
        </div>
    </div>
</div>
