<div class="col-md-3 no-padding-left">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><a href="{{ url('apps/' . $module->slug) }}">{{ $module->name }}</a></h3>

            @if (isset($installed[$module->slug]))
                @php $color = 'bg-green'; @endphp

                @if (!$installed[$module->slug])
                    @php $color = 'bg-yellow'; @endphp
                @endif
                <span class="module-installed">
                    <small class="label {{ $color }}">{{ trans('modules.badge.installed') }}</small>
                </span>
            @endif
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->

        <div class="box-body text-center">
            <a href="{{ url('apps/' . $module->slug) }}">
                @foreach ($module->files as $file)
                    @if (($file->media_type == 'image') && ($file->pivot->zone == 'thumbnail'))
                        <img src="{{ $file->path_string }}" alt="{{ $module->name }}" class="item-image">
                    @endif
                @endforeach
            </a>
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            <div class="pull-left">
            @for($i = 1; $i <= $module->vote; $i++)
                <i class="fa fa-star fa-lg"></i>
            @endfor
            @for($i = $module->vote; $i < 5; $i++)
                <i class="fa fa-star-o fa-lg"></i>
            @endfor
            @if ($module->total_review)
                &nbsp; ({{ $module->total_review }})
            @endif
            </div>
            <div class="pull-right">
                @if ($module->price == '0.0000')
                    {{ trans('modules.free') }}
                @else
                    {!! $module->price_prefix !!}
                    @if (isset($module->special_price))
                        <del>{{ $module->price }}</del>
                        {{ $module->special_price }}
                    @else
                        {{ $module->price }}
                    @endif
                    {!! $module->price_suffix !!}
                @endif
            </div>
        </div>
        <!-- /.box-footer -->
    </div>
    <!-- /.box -->
</div>