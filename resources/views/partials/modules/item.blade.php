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
                {{ $module->vendor_name }}
            </div>
            <div class="pull-right">
                @if ($module->price == '0.0000')
                    {{ trans('modules.free') }}
                @else
                    @if (isset($module->special_price))
                        <del>{{ $module->price }}</del>
                        {{ $module->special_price }}
                    @else
                        {{ $module->price }}
                    @endif
                @endif
            </div>
        </div>
        <!-- /.box-footer -->
    </div>
    <!-- /.box -->
</div>