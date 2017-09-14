@extends('layouts.admin')

@section('title', trans('offline::offline.offline'))

@section('content')
    <div class="col-md-4 no-padding-left">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('offline::offline.add_new') }}</h3>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->

            {!! Form::open(['url' => 'modules/offline/settings', 'files' => true, 'role' => 'form']) !!}

            <div class="box-body">
                {{ Form::textGroup('name', trans('general.name'), 'id-card-o', ['required' => 'required'], null, 'col-md-12') }}

                {{ Form::textGroup('code', trans('offline::offline.code'), 'key', ['required' => 'required'], null, 'col-md-12') }}

                {{ Form::textGroup('order', trans('offline::offline.order'), 'sort', [], 0, 'col-md-12') }}

                {{ Form::textareaGroup('description', trans('general.description')) }}
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                {{ Form::saveButtons('modules/offline/settings') }}
            </div>
            <!-- /.box-footer -->

            {!! Form::close() !!}
        </div>
        <!-- /.box -->
    </div>
    <div class="col-md-8 no-padding-left">
        <!-- Default box -->
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('offline::offline.payment_gateways') }}</h3>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="tbl-items">
                        <thead>
                        <tr>
                            <th class="col-md-3">{{ trans('general.name') }}</th>
                            <th class="col-md-3">{{ trans('offline::offline.code') }}</th>
                            <th class="col-md-3">{{ trans('offline::offline.order') }}</th>
                            <th class="col-md-3">{{ trans('general.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($items)
                        @foreach($items as $item)
                            <tr id="method-{{ $item->code }}">
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->order }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-xs method-edit" id="edit-{{ $item->code }}" title="{{ trans('general.edit') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ trans('general.edit') }}</button>
                                    <button type="button" class="btn btn-danger btn-xs method-delete" id="delete-{{ $item->code }}" title="{{ trans('general.delete') }}"><i class="fa fa-trash-o" aria-hidden="true"></i> {{ trans('general.delete') }}</button>
                                </td>
                            </tr>
                        @endforeach
                        @else

                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.method-edit').on('click', function() {
                var code = $(this).attr('id').replace('edit-', '');

                $.ajax({
                    url: '{{ url("modules/offline/settings/get") }}',
                    type: 'post',
                    dataType: 'json',
                    data: {code: code},
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    success: function(json) {
                        if (json['error']) {
                        }

                        if (json['success']) {
                            $('input[name="name"]').val(json['data']['name']);
                            $('input[name="code"]').val(json['data']['code']);
                            $('input[name="sort"]').val(json['data']['sort']);
                            $('input[name="description"]').val(json['data']['description']);

                            $('input[name="method"]').remove();

                            $('.col-md-4 .box-body').append('<input type="hidden" name="method" value="' + json['data']['code'] + '">');
                        }
                    }
                });
            });

            $('.method-delete').on('click', function() {
                var code = $(this).attr('id').replace('delete-', '');

                $.ajax({
                    url: '{{ url("modules/offline/settings/delete") }}',
                    type: 'post',
                    dataType: 'json',
                    data: {code: code},
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    success: function(json) {
                        if (json['error']) {
                        }

                        if (json['success']) {
                            $('#method-' + code).remove();
                        }
                    }
                });
            });
        });
    </script>
@endsection

