<div class="col-md-3 no-padding-left">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><a href="{{ url('modules/item/' . $module->slug) }}">{{ $module->name }}</a></h3>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->

        <div class="box-body">
            <a href="{{ url('modules/item/' . $module->slug) }}">
                <img src="{{ $module->files[0]->path_string }}" alt="{{ $module->name }}" width="280" height="140">
                <br>
                {!! str_limit($module->description, 70) !!}
            </a>
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            <div class="pull-left">
                {{ $module->category->name }}
            </div>
            <div class="pull-right">
                @if ($module->price == '0.0000')
                    {{ trans('modules.free') }}
                @else
                    {{ $module->price }}
                @endif
            </div>
        </div>
        <!-- /.box-footer -->
    </div>
    <!-- /.box -->
</div>