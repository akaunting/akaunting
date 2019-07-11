<h2>{{ $gateway['name'] }}</h2>

@if ($gateway['description'])
<div class="well well-sm">
    {{ $gateway['description'] }}
</div>
@endif

<div class="buttons">
    <div class="pull-right">
        <input type="button" value="{{ trans('offlinepayment::general.confirm') }}" id="button-confirm" class="btn btn-success" data-loading-text="{{ trans('offlinepayment::general.loading') }}" />
    </div>
</div>
<script type="text/javascript"><!--
    $('#button-confirm').on('click', function() {
        $.ajax({
            url: '{{ url("customers/invoices/" . $invoice->id . "/offlinepayment/confirm") }}',
            type: 'POST',
            dataType: 'JSON',
            data: {payment_method: '{{ $gateway['code'] }}'},
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
