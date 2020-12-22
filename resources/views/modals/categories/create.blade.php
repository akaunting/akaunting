{!! Form::open([
    'id' => 'form-create-category',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'route' => 'categories.store',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::textGroup('name', trans('general.name'), 'font') }}

        @stack('color_input_start')
            <div class="form-group col-md-6 required {{ $errors->has('color') ? 'has-error' : ''}}">
                {!! Form::label('color', trans('general.color'), ['class' => 'form-control-label']) !!}
                <div class="input-group input-group-merge" id="category-color-picker">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <el-color-picker v-model="color" size="mini" :predefine="predefineColors" @change="onChangeColor"></el-color-picker>
                        </span>
                    </div>
                    {!! Form::text('color', '#55588b', ['v-model' => 'form.color', '@input' => 'onChangeColorInput', 'id' => 'color', 'class' => 'form-control color-hex', 'required' => 'required']) !!}
                </div>
                {!! $errors->first('color', '<p class="help-block">:message</p>') !!}
            </div>
        @stack('color_input_end')

        {!! Form::hidden('type', $type, []) !!}
        {!! Form::hidden('enabled', '1', []) !!}
    </div>
{!! Form::close() !!}
