@extends('layouts.admin')

@section('title', trans('offlinepayment::general.title'))

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('offlinepayment::general.add_new') }}</h3>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->

                {!! Form::open(['route' => 'offlinepayment.update', 'files' => true, 'role' => 'form', 'class' => 'form-loading-button']) !!}

                <div class="box-body">
                    <div id="install-loading"></div>

                    {{ Form::textGroup('name', trans('general.name'), 'id-card-o', ['required' => 'required'], null, 'col-md-12') }}

                    {{ Form::textGroup('code', trans('offlinepayment::general.form.code'), 'key', ['required' => 'required'], null, 'col-md-12') }}

                    {{ Form::radioGroup('customer', trans('offlinepayment::general.form.customer'), '', ['required' => 'required'], 0, 'col-md-12') }}

                    {{ Form::textGroup('order', trans('offlinepayment::general.form.order'), 'sort', [], null, 'col-md-12') }}

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
                    <h3 class="box-title">{{ trans('offlinepayment::general.payment_gateways') }}</h3>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div id="delete-loading"></div>

                    <div class="table table-responsive">
                        <table class="table table-striped table-hover" id="tbl-items">
                            <thead>
                            <tr>
                                <th class="col-md-3">{{ trans('general.name') }}</th>
                                <th class="col-md-4">{{ trans('offlinepayment::general.form.code') }}</th>
                                <th class="col-md-2 text-center">{{ trans('offlinepayment::general.form.order') }}</th>
                                <th class="col-md-3 text-center">{{ trans('general.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($items)
                            @foreach($items as $item)
                                <tr id="method-{{ $item->code }}">
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->code }}</td>
                                    <td class="text-center">{{ $item->order }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">
                                                <i class="fa fa-ellipsis-h"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li><a href="#" class="method-edit" id="edit-{{ $item->code }}">{{ trans('general.edit') }}</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#" class="method-delete" id="delete-{{ $item->code }}">{{ trans('general.delete') }}</a></li>
                                            </ul>
                                        </div>
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
        #install-loading.active, #delete-loading.active {
            font-size: 35px;
            position: absolute;
            z-index: 500;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
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
            padding: 45% 40%;
        }

        #delete-loading .install-loading-spin {
            padding: 8% 40%;
        }
    </style>
@endpush

@push('scripts')
    <script type="text/javascript">
        var text_yes = '{{ trans('general.yes') }}';
        var text_no = '{{ trans('general.no') }}';
        var code = '';
        var tr = '';

        $(document).ready(function() {
            $('.method-edit').on('click', function() {
                code = $(this).attr('id').replace('edit-', '');

                $.ajax({
                    url: '{{ route("offlinepayment.get") }}',
                    type: 'post',
                    dataType: 'json',
                    data: {code: code},
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    beforeSend: function() {
                        $('#install-loading').addClass('active');
                        $('#install-loading').html('<span class="install-loading-bar"><span class="install-loading-spin"><i class="fa fa-spinner fa-spin"></i></span></span>');
                    },
                    complete: function() {
                       $('#install-loading').removeClass('active');
                       $('#install-loading .install-loading-bar').remove();
                    },
                    success: function(json) {
                        if (json['error']) {
                        }

                        if (json['success']) {
                            $('.col-md-4.no-padding-left .box-header.with-border .box-title').html(json['data']['title']);
                            $('input[name="name"]').val(json['data']['name']);
                            $('input[name="code"]').val(json['data']['code']);

                            if (json['data']['customer'] == 1) {
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
                code = $(this).attr('id').replace('delete-', '');
                tr =  $(this).parent().parent();

                $.ajax({
                    url: '{{ route("offlinepayment.delete") }}',
                    type: 'post',
                    dataType: 'json',
                    data: {code: code},
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    beforeSend: function() {
                        $('#delete-loading').addClass('active');
                        $('#delete-loading').html('<span class="install-loading-bar"><span class="install-loading-spin"><i class="fa fa-spinner fa-spin"></i></span></span>');
                    },
                    complete: function() {
                        //$('#delete-loading').removeClass('active');
                        //$('#delete-loading .install-loading-bar').remove();
                    },
                    success: function(json) {
                        if (json['error']) {
                        }

                        if (json['success']) {
                            $('#method-' + code).remove();
                            location.reload();
                        }
                    }
                });
            });
        });
    </script>
@endpush
