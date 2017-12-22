@extends('layouts.admin')

@section('title', trans('offlinepayment::offlinepayment.offlinepayment'))

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('offlinepayment::offlinepayment.add_new') }}</h3>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->

                {!! Form::open(['url' => 'apps/offlinepayment/settings', 'files' => true, 'role' => 'form']) !!}

                <div class="box-body">
                    <div id="install-loading"></div>

                    {{ Form::textGroup('name', trans('general.name'), 'id-card-o', ['required' => 'required'], null, 'col-md-12') }}

                    {{ Form::textGroup('code', trans('offlinepayment::offlinepayment.code'), 'key', ['required' => 'required'], null, 'col-md-12') }}

                    {{ Form::radioGroup('customer', trans('offlinepayment::offlinepayment.customer'), '', ['required' => 'required'], 0, 'col-md-12') }}

                    {{ Form::textGroup('order', trans('offlinepayment::offlinepayment.order'), 'sort', [], null, 'col-md-12') }}

                    {{ Form::textareaGroup('description', trans('general.description')) }}
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    {{ Form::saveButtons('apps/offlinepayment/settings') }}
                </div>
                <!-- /.box-footer -->

                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-8">
            <!-- Default box -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('offlinepayment::offlinepayment.payment_gateways') }}</h3>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table table-responsive">
                        <table class="table table-striped table-hover" id="tbl-items">
                            <thead>
                            <tr>
                                <th class="col-md-3">{{ trans('general.name') }}</th>
                                <th class="col-md-4">{{ trans('offlinepayment::offlinepayment.code') }}</th>
                                <th class="col-md-2 text-center">{{ trans('offlinepayment::offlinepayment.order') }}</th>
                                <th class="col-md-3">{{ trans('general.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($items)
                            @foreach($items as $item)
                                <tr id="method-{{ $item->code }}">
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->code }}</td>
                                    <td class="text-center">{{ $item->order }}</td>
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
    </div>
@endsection

@push('stylesheet')
    <style type="text/css">
        .install-loading-bar {
            font-size: 35px;
            position: absolute;
            z-index: 500;
            top: 0px;
            left: 0px;
            width: 100%;
            background: rgb(136, 136, 136);
            opacity: 0.2;
            -moz-border-radius-bottomleft: 1px;
            -moz-border-radius-bottomright: 1px;
            border-bottom-left-radius: 1px;
            border-bottom-right-radius: 1px;
        }

        .install-loading-spin {
            font-size: 100px;
            position: absolute;
            margin: auto;
            color: #fff;
            padding: 28% 40%;
        }
    </style>
@endpush

@push('scripts')
    <script type="text/javascript">
        var text_yes = '{{ trans('general.yes') }}';
        var text_no = '{{ trans('general.no') }}';

        $(document).ready(function() {
            $('.method-edit').on('click', function() {
                var code = $(this).attr('id').replace('edit-', '');

                $.ajax({
                    url: '{{ url("apps/offlinepayment/settings/get") }}',
                    type: 'post',
                    dataType: 'json',
                    data: {code: code},
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    beforeSend: function() {
                        $('#install-loading').html('<span class="install-loading-bar"><span class="install-loading-spin"><i class="fa fa-spinner fa-spin"></i></span></span>');
                        $('.install-loading-bar').css({"height": $('.col-md-4.no-padding-left').height() - 23});
                    },
                    complete: function() {
                       $('#install-loading .install-loading-bar').remove();
                    },
                    success: function(json) {
                        if (json['error']) {
                        }

                        if (json['success']) {
                            $('.col-md-4.no-padding-left .box-header.with-border .box-title').html(json['data']['title']);
                            $('input[name="name"]').val(json['data']['name']);
                            $('input[name="code"]').val(json['data']['code']);

                            if (json['data']['customer']) {
                                $('#customer_1 input').trigger('click');
                            } else {
                                $('#customer_0 input').trigger('click');
                            }

                            $('input[name="order"]').val(json['data']['order']);
                            $('textarea[name="description"]').val(json['data']['description']);

                            $('input[name="method"]').remove();

                            $('.col-md-4 .box-body').append('<input type="hidden" name="method" value="' + json['data']['method'] + '">');
                        }
                    }
                });
            });

            $('.method-delete').on('click', function() {
                var code = $(this).attr('id').replace('delete-', '');

                $.ajax({
                    url: '{{ url("apps/offlinepayment/settings/delete") }}',
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
@endpush
