<div>
    <div class="d-none">
        @if (!empty($setting['name']))
            <h2>{{ $setting['name'] }}</h2>
        @endif

        @if (!empty($setting['description']))
            <div class="well well-sm">
                {{ $setting['description'] }}
            </div>
        @endif
    </div>
    <br>

    <div class="buttons">
        <div class="pull-right">
            <input type="button" value="{{ trans('offline-payments::general.confirm') }}" id="button-confirm" class="btn btn-success" data-loading-text="{{ trans('offline-payments::general.loading') }}" />
        </div>
    </div>
</div>

<script type="text/javascript"><!--
    $('#button-confirm').on('click', function() {
        $.ajax({
            url: '{{ route("portal.invoices.offline-payments.confirm", $invoice->id) }}',
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
