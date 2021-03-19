@stack('status_message_start')
@if ($document->status == 'draft')
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-danger fade show" role="alert">
                @stack('status_message_body_start')
                    <span class="alert-text">
                        <strong>{!!  trans($textStatusMessage) !!}</strong>
                    </span>
                @stack('status_message_body_end')
            </div>
        </div>
    </div>
@endif
@stack('status_message_end')