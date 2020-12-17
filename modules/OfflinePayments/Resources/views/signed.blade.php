<h2>{{ $setting['name'] }}</h2>

@if ($setting['description'])
    <div class="well well-sm">
        {{ $setting['description'] }}
    </div>
@endif

<div class="buttons">
    <div class="float-right">
        <input type="button" value="{{ trans('offline-payments::general.confirm') }}" id="button-confirm" class="btn btn-success" data-loading-text="{{ trans('offline-payments::general.loading') }}" />
    </div>
</div>
<script type="text/javascript"><!--
    $('#button-confirm').on('click', function() {
        $.ajax({
            url: '{!! urldecode($confirm_url) !!}',
            type: 'POST',
            dataType: 'JSON',
            data: {payment_method: '{{ $setting['code'] }}'},
            cache: false,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            beforeSend: function() {
                $('#button-confirm').button('loading');
            },
            complete: function() {
                $('#button-confirm').button('reset');
            },
            success: function(data) {
                if (data['error']) {
                    alert(data['error']);
                }

                if (data['success']) {
                    location.reload();
                }
            }
        });
    });
    //--></script>

<div>
    <div class="d-none">
        @if (!empty($setting['name']))
            <h2>{{ $setting['name'] }}</h2>
        @endif

        @if (!empty($setting['description']))
            <div class="well well-sm">{{ $setting['description'] }}</div>
        @endif
    </div>
    <br>

    <div class="buttons">
        <div class="pull-right">
            {!! Form::open([
                'url' => urldecode($confirm_url),
                'id' => 'redirect-form',
                'role' => 'form',
                'autocomplete' => "off",
                'novalidate' => 'true'
            ]) !!}
                <button @click="onRedirectConfirm" type="button" id="button-confirm" class="btn btn-success">
                    {{ trans('general.confirm') }}
                </button>
                {!! Form::hidden('payment_method', $setting['code'], ['v-model' => 'form.payment_method']) !!}
            {!! Form::close() !!}
        </div>
    </div>
</div>
