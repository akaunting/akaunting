@php
    if (($page == 'create') || !$model->recurring()->count()) {
        $frequency = 'no';
        $interval = 1;
        $custom_frequency = 'monthly';
        $count = 0;
    } else {
        $r = $model->recurring;
        $frequency = ($r->interval != 1) ? 'custom' : $r->frequency;
        $interval = $r->interval;
        $custom_frequency = $r->frequency;
        $count = $r->count;
    }
@endphp

<div class="col-md-6 input-group-recurring">
    <div class="form-group col-md-12 {{ $errors->has('recurring_frequency') ? 'has-error' : ''}}">
        {!! Form::label('recurring_frequency', trans('recurring.recurring'), ['class' => 'control-label']) !!}
        <div class="input-group">
            <div class="input-group-addon"><i class="fa fa-refresh"></i></div>
            {!! Form::select('recurring_frequency', $recurring_frequencies, $frequency, ['class' => 'form-control']) !!}
        </div>
        {!! $errors->first('recurring_frequency', '<p class="help-block">:message</p>') !!}
    </div>

    <div class="form-group col-md-2 hidden {{ $errors->has('recurring_interval') ? 'has-error' : '' }}">
        {!! Form::label('recurring_interval', trans('recurring.every'), ['class' => 'control-label']) !!}
        {!! Form::number('recurring_interval', $interval, ['class' => 'form-control']) !!}
        {!! $errors->first('recurring_interval', '<p class="help-block">:message</p>') !!}
    </div>

    <div class="form-group col-md-4 hidden {{ $errors->has('recurring_custom_frequency') ? 'has-error' : ''}}">
        {!! Form::label('recurring_custom_frequency', trans('recurring.period'), ['class' => 'control-label']) !!}
        {!! Form::select('recurring_custom_frequency', $recurring_custom_frequencies, $custom_frequency, ['class' => 'form-control']) !!}
        {!! $errors->first('recurring_custom_frequency', '<p class="help-block">:message</p>') !!}
    </div>

    <div class="form-group col-md-2 hidden {{ $errors->has('recurring_count') ? 'has-error' : '' }}">
        {!! Form::label('recurring_count', trans('recurring.times'), ['class' => 'control-label']) !!}
        {!! Form::number('recurring_count', $count, ['class' => 'form-control']) !!}
        {!! $errors->first('recurring_count', '<p class="help-block">:message</p>') !!}
    </div>
</div>
